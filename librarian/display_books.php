<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$con = new mysqli("localhost", "root", "", "librarygh");
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

require_once __DIR__ . '/../db_connect.php';
require_once __DIR__ . '/../message_display.php';
require_once __DIR__ . '/../verify_logged_out.php';
require_once __DIR__ . '/header_librarian.php';

$query = $con->prepare("SELECT isbn, title, author, category, price, copies FROM book ORDER BY title");
if (!$query) {
    die("ERROR: Couldn't prepare query: " . $con->error);
}

$query->execute();
$result = $query->get_result();
$rows = $result->num_rows;

if ($rows == 0) {
    echo "<h2 align='center'>No books available</h2>";
} else {
    echo "<h2 align='center'>$rows books found</h2>";
}

?>

<html>

<head>
    <title>LMS</title>
    <link rel="stylesheet" type="text/css" href="../member/css/home_style.css" />
    <link rel="stylesheet" type="text/css" href="../css/global_styles.css">
    <link rel="stylesheet" type="text/css" href="../css/home_style.css">
    <link rel="stylesheet" type="text/css" href="../member/css/custom_radio_button_style.css">
    <div id="verticalLine">
        <div id="librarian">
                <br />
                Book
        </div>
    </div>
</head>

<body>
   
    <?php
    if ($rows > 0) {
        echo "<form class='cd-form'>";
        echo "<div class='error-message' id='error-message'>
            <p id='error'></p>
          </div>";

        echo "<table width='100%' cellpadding='10' cellspacing='10'>";
        echo "<tr>
            <th>ISBN<hr></th>
            <th>Book Title<hr></th>
            <th>Author<hr></th>
            <th>Category<hr></th>
            <th>Price<hr></th>
            <th>Copies<hr></th>
          </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['isbn'] . "</td>";
            echo "<td>" . $row['title'] . "</td>";
            echo "<td>" . $row['author'] . "</td>";
            echo "<td>" . $row['category'] . "</td>";
            echo "<td>Rs." . $row['price'] . "</td>";
            echo "<td>" . $row['copies'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
        echo "</form>";
    }
    ?>

</body>

</html>