<?php
// this will become the account page

// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
     <style>
    body {
        font: 14px sans-serif;
        background-image: url('https://images.pexels.com/photos/165505/pexels-photo-165505.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
    }
    .wrapper {
        width: 360px;
        margin: auto; /* this centers the element horizontally */
        padding: 20px;
        background-color: white;
        border-radius: 10px;
        margin-top: 50px;
        box-shadow: 0px 0px 20px rgba(0,0,0,0.2);
    }
</style>
</head>
<body>
    <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to JustWaterfront.com</h1>
   <h5>What would you liked to do?</h5>
   <style>
.btn:hover {
  background-color: orange;
  font-size: 2em;
  transform: scale(1);
  transition: all 0.3s ease-in-out;
}
</style>
   <div style="background-color: rgba(251, 192, 147, 0.3); padding: 20px;">
  <div style="text-align: center;">
    <a href="listaplace.php" class="btn btn-warning">List A Place</a>
    <br/><br/>
    <a href="reset-password.php" class="btn btn-warning">Edit your listing</a>
    <br/><br/>
    <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
    <br/><br/>
    <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
  </div>
</div>


</body>
</html>