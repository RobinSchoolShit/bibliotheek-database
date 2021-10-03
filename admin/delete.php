<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Controleer of de contact-ID bestaat
if (isset($_GET['id'])) {
    // Selecteer de record die verwijderd gaat worden
    $stmt = $pdo->prepare('SELECT * FROM contacts WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contact) {
        exit('Contact doesn\'t exist with that ID!');
    }
    // Een extra confirm zodat de gebruiker zeker weet dat hij het wil verwijderen
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM contacts WHERE id = ?');
            $stmt->execute([$_GET['id']]);
            $msg = 'You have deleted the contact!';
        } else {
            // Gebruiker klikte op de knop "Nee", stuur ze terug naar de gelezen pagina
            header('Location: read.php');
            exit;
        }
    }
} else {
    exit('No ID specified!');
}

// Dit is de code die ervoor zorgt dat je niet op de pagina kan komen zonder in te loggen
session_start();
// Als de gebruiker niet inlogd wordt hij teruggestuurd
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../cms-login/index.html');
	exit;
}

?>

<?=template_header('Delete')?>

<div class="content delete">
	<h2>Delete Contact #<?=$contact['id']?></h2>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php else: ?>
	<p>Are you sure you want to delete contact #<?=$contact['id']?>?</p>
    <div class="yesno">
        <a href="delete.php?id=<?=$contact['id']?>&confirm=yes">Yes</a>
        <a href="delete.php?id=<?=$contact['id']?>&confirm=no">No</a>
    </div>
    <?php endif; ?>
</div>

<?=template_footer()?>