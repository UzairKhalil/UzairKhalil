<?php
if(isset($_POST['submit']) && !empty($_POST['submit'])) {
$servername = $_POST['servernm']; 
$username = $_POST['usernm'];
$pswd = $_POST['serverpswd'];
if($servername == "" && $username == "" && $pswd == ""){
    echo "Please Set Server Credentials";
}else{
$myfile  = fopen($_SERVER['DOCUMENT_ROOT'] . "/Cart/Credentials.txt","wb");
fwrite($myfile,  $servername . "\r\n");
fwrite($myfile,   $username. "\r\n");
fwrite($myfile,  $pswd);
fclose($myfile); 

$con = new mysqli($servername,$username,$pswd);
    if(!$con){
        echo "Server Credentials are incorrect";
    }else{
        header("location:index.php");
    }
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<body>
    <div class="container">
    <div class="wrapper">
    <h3 class="text-center" style="margin-top: 25px;">Please Enter Server Credentials</h3>
        <div class="row justify-content-center">  
            <Form method="POST">
                <label>Enter Server Name</label> 
                <input type="text"  class="form-control" name="servernm" id="snm">
                <label> Enter User Name</label> 
                <input type="text"  class="form-control" name="usernm" id="user">
                <label> Enter Server Password</label> 
                <input type="password"  class="form-control" name="serverpswd" id="spwd"><hr>
                <input type="submit" class="btn btn-primary" name = "submit">
            </Form>
        </div> 
    </div> 
    </div> 
</body>
</html>