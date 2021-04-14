<?php
if (file_exists("Credentials.txt")){
    $credentials = fopen("Credentials.txt", "r");
    $counter = 1;
    $servername = "";
    $username = "";
    $pswd = "";
    
    if ($credentials) {
        while (($line = fgets($credentials)) !== false) {
         if ($counter ==1)
         {
            $servername = str_replace("\n", '', $line);	
            $servername = str_replace("\r", '', $servername);	
                        
            }else if ($counter == 2){
                $username = str_replace("\n", '', $line);	
                $username = str_replace("\r", '', $username);	

                } else if($counter == 3){
                    $pswd = str_replace("\n", '', $line);	
                    $pswd = str_replace("\r", '', $pswd);
                } else{
                    break;
                }
                $counter++;
            }
            fclose($credentials);
            $con = new mysqli($servername, $username, $pswd);
            if ($con->connect_error) {
            echo ($con->connect_error);
            } else{
                $sql = "CREATE DATABASE IF NOT EXISTS Shopping_Cart";
                if ($con->query($sql)){
                    // echo "Database Created";
                }
                else {
                    echo "Database Not Created";
                    die();
                }

                $db = "Shopping_Cart";
                $con = new mysqli($servername,$username,$pswd,$db);
                $sql = "CREATE TABLE IF NOT EXISTS `tbl_product` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `name` varchar(255) NOT NULL UNIQUE,
                    `image` varchar(255) NOT NULL,
                    `price` double(10,2) NOT NULL,
                    PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;";

                if ($con->query($sql) ===TRUE) {
                    // echo "Table created";
                    $query1 = "INSERT INTO `tbl_product` (`name`, `image`, `price`) VALUES
                    ('Apple MacBook Pro', './images/product1.png', 1799.00),
                    ('Sony E7 Headphones', './images/product2.png', 200.00),
                    ('Sony Xperia Z4', './images/product3.png', 600.00);";
    
                    if ($con->query($query1)=== TRUE) {
                        // echo "Data inserted";
                    }else{
                        // echo "Data not inserted";
                        // die();
                        }
                }else{
                    echo "Table not created.";
                    die();
                }
                // echo "Connected Successfully!";
                // header("location:Home.php");
                
            }
        }else{
        echo "error opening the file.";
        die();
    } 
}else{
    header("location:cred-inst.php");
	die();
}

session_start();
if(isset($_POST["add_to_cart"]))
{
	if(isset($_SESSION["shopping_cart"]))
	{
		$item_array_id = array_column($_SESSION["shopping_cart"], "item_id");
		if(!in_array($_GET["id"], $item_array_id))
		{
			$count = count($_SESSION["shopping_cart"]);
			$item_array = array(
				'item_id'			=>	$_GET["id"],
				'item_name'			=>	$_POST["hidden_name"],
				'item_price'		=>	$_POST["hidden_price"],
				'item_quantity'		=>	$_POST["quantity"]
			);
			$_SESSION["shopping_cart"][$count + 1] = $item_array;
		}
		else
		{
			echo '<script>alert("Item Already Added")</script>';
		}
	}
	else
	{
		$item_array = array(
			'item_id'			=>	$_GET["id"],
			'item_name'			=>	$_POST["hidden_name"],
			'item_price'		=>	$_POST["hidden_price"],
			'item_quantity'		=>	$_POST["quantity"]
		);
		$_SESSION["shopping_cart"][0] = $item_array;
	}
}

if(isset($_GET["action"]))
{
	if($_GET["action"] == "delete")
	{
		foreach($_SESSION["shopping_cart"] as $keys => $values)
		{
			if($values["item_id"] == $_GET["id"])
			{
				unset($_SESSION["shopping_cart"][$keys]);
				echo '<script>alert("Item Removed")</script>';
				echo '<script>window.location="index.php"</script>';
			}
		}
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script> -->
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <title>Home</title>
</head>
<body>

    <div class="container" style= "margin-top: 10px">
    <?php
				$query = "SELECT * FROM tbl_product ORDER BY id ASC";
				$result = mysqli_query($con, $query);
				if(mysqli_num_rows($result) > 0)
				{
					while($row = mysqli_fetch_array($result))
					{
				?>

        

        <div class="col-md-4" style = "height: 100px width: 100px;" >
        <form method="post" action="index.php?action=add&id=<?php echo $row["id"]; ?>">
					<div width="100" height="100" style="border:1px solid #333; background-color:#f1f1f1; border-radius:5px; padding:16px;" align="center">
						<img src="<?php echo $row["image"]; ?>"  class="img-responsive" width="200" height="200" />

						<h4 class="text-info"><?php echo $row["name"]; ?></h4>

						<h4 class="text-danger">$ <?php echo $row["price"]; ?></h4>

						<input type="number" name="quantity" value="1" class="form-control" />

						<input type="hidden" name="hidden_name" value="<?php echo $row["name"]; ?>" />

						<input type="hidden" name="hidden_price" value="<?php echo $row["price"]; ?>" />

						<input type="submit" name="add_to_cart" style="margin-top:5px;" class="btn btn-success" value="Add to Cart" />

					</div>
				</form>


        
        </div>
        <?php
					}
				}
			?>
			<div style="clear:both"></div>
			<h3>Order Details</h3>
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<th width="40%">Item Name</th>
						<th width="10%">Quantity</th>
						<th width="20%">Price</th>
						<th width="15%">Total</th>
						<th width="5%">Action</th>
					</tr>
					<?php
					if(!empty($_SESSION["shopping_cart"]))
					{
						$total = 0;
						foreach($_SESSION["shopping_cart"] as $keys => $values)
						{
					?>
					<tr>
						<td><?php echo $values["item_name"]; ?></td>
						<td><?php echo $values["item_quantity"]; ?></td>
						<td>$ <?php echo $values["item_price"]; ?></td>
						<td>$ <?php echo number_format($values["item_quantity"] * $values["item_price"], 2);?></td>
						<td><a href="index.php?action=delete&id=<?php echo $values["item_id"]; ?>"><span class="text-danger">Remove</span></a></td>
					</tr>
					<?php
							$total = $total + ($values["item_quantity"] * $values["item_price"]);
						}
					?>
					<tr>
						<td colspan="3" align="right">Total</td>
						<td align="right">$ <?php echo number_format($total, 2); ?></td>
						<td></td>
					</tr>
					<?php
					}
					?>
						
				</table>
			</div>
		</div>
</div>
</body>
</html>