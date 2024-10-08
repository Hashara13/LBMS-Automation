<?php
	require "db_connect.php";
	require_once __DIR__ . '/src/Librarian.php';

	require "header.php";
	session_start();
	
	if(empty($_SESSION['type'])) {
	} else if(strcmp($_SESSION['type'], "librarian") == 0) {
		header("Location: librarian/home.php");
		exit();
	} else if(strcmp($_SESSION['type'], "member") == 0) {
		header("Location: member/home.php");
		exit();
	}
?>

<html>
	<head>
		<title>LMS</title>
		<link rel="stylesheet" type="text/css" href="css/index_style.css" />
	</head>
	<body>
		<div id="allTheThings">
			<div id="member">
				<a href="member">
					<img src="img/pngwing.com.png" width="250px" height="auto"/><br />
					&nbsp;Member Login
				</a>
			</div>
			<div id="verticalLine">
				<div id="librarian">
					<a id="librarian-link" href="librarian">
						<img src="img/pngwing.com(1).png" width="250px" height="220" /><br />
						&nbsp;&nbsp;&nbsp;Librarian Login
					</a>
				</div>
			</div>
		</div>
	</body>
</html>