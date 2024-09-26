<?php
	require "../db_connect.php";
	require "../message_display.php";
	require "../header.php";
?>

<html>
	<head>
		<title>Librarian Registration</title>
		<link rel="stylesheet" type="text/css" href="../css/global_styles.css">
		<link rel="stylesheet" type="text/css" href="../css/form_styles.css">
		<link rel="stylesheet" href="css/register_style.css">
	</head>
	<body>
		<form class="cd-form" method="POST" action="#">
			<center><legend>Librarian Registration</legend><p>Please fill up the form below:</p></center>
			
			<div class="error-message" id="error-message">
				<p id="error"></p>
			</div>

			<div class="icon">
				<input class="m-name" type="text" name="m_name" placeholder="Full Name" required />
			</div>

			<div class="icon">
				<input class="m-email" type="email" name="m_email" id="m_email" placeholder="Email" required />
			</div>
			
			<div class="icon">
				<input class="m-user" type="text" name="m_user" id="m_user" placeholder="Username" required />
			</div>
			
			<div class="icon">
				<input class="m-pass" type="password" name="m_pass" placeholder="Password" required />
			</div>
		
            <div class="icon">
				<input class="m-m_balance" type="number" name="m_balance" id="m_balance" placeholder="Experience in Years" required />
			</div>
			
			<br />
			<input type="submit" name="m_register" value="Submit" />
		</form>

		<?php
		if(isset($_POST['m_register'])) {
			// Check if experience is sufficient
			if($_POST['m_balance'] < 5) {
				echo error_with_field("Experience must be at least 5 years to create an account", "m_balance");
			} else {
				// Check if username already exists
				$query = $con->prepare("SELECT username FROM librarian WHERE username = ?;");
				$query->bind_param("s", $_POST['m_user']);
				$query->execute();
				$result = $query->get_result();
				
				if(mysqli_num_rows($result) != 0) {
					echo error_with_field("The username you entered is already taken", "m_user");
				} else {
					// Check if email already exists
					$query = $con->prepare("SELECT email FROM librarian WHERE email = ?;");
					$query->bind_param("s", $_POST['m_email']);
					$query->execute();
					$result = $query->get_result();
					
					if(mysqli_num_rows($result) != 0) {
						echo error_with_field("An account is already registered with that email", "m_email");
					} else {
						// Insert new librarian
						$query = $con->prepare("INSERT INTO librarian(username, password, name, email, experience) VALUES(?, ?, ?, ?, ?);");
						$query->bind_param("ssssd", $_POST['m_user'], sha1($_POST['m_pass']), $_POST['m_name'], $_POST['m_email'], $_POST['m_balance']);
						
						if($query->execute()) {
							session_start();
							$_SESSION['type'] = "librarian";
							$_SESSION['id'] = $con->insert_id; 
							$_SESSION['username'] = $_POST['m_user'];

							// Redirect to librarian home after registration
							header('Location: home.php');
							exit();
						} else {
							echo error_without_field("Couldn't record details. Please try again later.");
						}
					}
				}
			}
		}
		?>
	</body>
</html>
