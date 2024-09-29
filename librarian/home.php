<?php
	require "../db_connect.php";
	require "verify_librarian.php";
	require "header_librarian.php";
?>

<html>
	<head>
		<title>LMS</title>
		<link rel="stylesheet" type="text/css" href="css/home_style.css" />
	</head>
	<body>
		<div id="allTheThings">
			
			<div class="card">
				<a href="insert_book.php">
					<input type="button" value="Insert New Book Record" />
				</a>
				<div class="popup">Add new books to the system</div>
			</div>

			<div class="card">
				<a href="update_copies.php">
					<input type="button" value="Update Copies of a Book" />
				</a>
				<div class="popup">Modify existing book records</div>
			</div>

			<div class="card">
				<a href="delete_book.php">
					<input type="button" value="Delete Book Records" />
				</a>
				<div class="popup">Remove outdated or damaged books</div>
			</div>

			<div class="card">
				<a href="display_books.php">
					<input type="button" value="Display Available Books" />
				</a>
				<div class="popup">View all books currently available</div>
			</div>

			<div class="card">
				<a href="pending_book_requests.php">
					<input type="button" value="Manage Pending Book Requests" />
				</a>
				<div class="popup">Handle book requests from members</div>
			</div>

			<div class="card">
				<a href="pending_registrations.php">
					<input type="button" value="Manage Pending Membership Registrations" />
				</a>
				<div class="popup">Approve or deny membership registrations</div>
			</div>

			<div class="card">
				<a href="update_balance.php">
					<input type="button" value="Update Balance of Members" />
				</a>
				<div class="popup">Adjust membership balances</div>
			</div>

			<div class="card">
				<a href="due_handler.php">
					<input type="button" value="Today's Reminder" />
				</a>
				<div class="popup">View today's outstanding tasks</div>
			</div>

		</div>
	</body>
</html>
