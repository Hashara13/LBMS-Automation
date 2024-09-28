<?php
$con = new mysqli("localhost", "root", "", "librarygh");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$query = $con->prepare("SELECT * FROM book");
$query->execute();
$result = $query->get_result();

if ($result) {
    while ($row = $result->fetch_assoc()) {
        print_r($row);
    }
} else {
    echo "ERROR: Couldn't fetch books";
}

$con->close();
?>
