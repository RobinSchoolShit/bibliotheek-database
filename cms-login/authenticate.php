<?php
session_start();
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'database-bieb';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}


if ( !isset($_POST['username'], $_POST['password']) ) {
	exit('Please fill both the username and password fields!');
}

if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	// Sla het resultaat op zodat we kunnen controleren of het account in de database bestaat.
	$stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        // Account bestaat, nu het wachtwoord verifiëren.
        if (password_verify($_POST['password'], $password)) {
            // Als de verificatie is gelukt ben je ingelogd
            // Dit maakt een sessie aan die onthoudt dat je ingelogd bent
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id;
            header('Location: ../admin/index.php');
        } else {
            // Geen correct password
            echo 'Incorrect username and/or password!';
        }
    } else {
        // Geen correcte username
        echo 'Incorrect username and/or password!';
    }

	$stmt->close();
}
?>