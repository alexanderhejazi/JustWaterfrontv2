<?php
$servername = "localhost";
$username = "root";
$password = "W1ll1@m!!";
$dbname = "JustWaterfront";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get form data
$sq_ft = $_POST['sq_ft'];
$state = $_POST['state'];
$town = $_POST['town'];
$price = $_POST['price'];
$photos = $_POST['photos']
$description = $_POST['description']
$rental_or_sale = $_POST['rental or sale']


// Insert data into table
$sql = "INSERT INTO listings (rental_or_sale,sq_ft, state, town, price, description, photos) VALUES ('$rental_or_sale', '$sq_ft', '$state', '$town', '$price', '$description', '$photos',)";

if (mysqli_query($conn, $sql)) {
    echo "Your listing has been submitted for approval.";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>
