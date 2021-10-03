<?php
include '../admin/functions.php';
// Connecten met de database
$pdo = pdo_connect_mysql();

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
// Aantal die wordt getoond op een pagina
$records_per_page = 3;

// Bereid de SQL-instructie voor en haal records uit onze contactentabel, LIMIT bepaalt de pagina
$stmt = $pdo->prepare('SELECT * FROM contacts ORDER BY id LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();

$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Krijg het totale aantal contacten, dit is zodat we kunnen bepalen of er een volgende en vorige knop moet zijn
$num_contacts = $pdo->query('SELECT COUNT(*) FROM contacts')->fetchColumn();

// Dit is de code die ervoor zorgt dat je niet op de pagina kan komen zonder in te loggen
// We moeten sessies gebruiken, dus je moet sessies altijd starten met de onderstaande code.
session_start();
// Als de gebruiker niet inlogd wordt hij teruggestuurd
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../gasten-login/index.html');
	exit;
}

?>

<?=template_header('Read')?>

<div class="content read">
	<h2>Bibliotheek Website</h2>
	<table>
        <thead>
            <tr>
                <td>#</td>
                <td>Title</td>
                <td>Author</td>
                <td>Isbn 13</td>
                <td>Format</td>
                <td>Publisher</td>
                <td>Pages</td>
                <td>Dimensions</td>
                <td>Overview</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contacts as $contact): ?>
            <tr>
                <td><?=$contact['id']?></td>
                <td><?=$contact['title']?></td>
                <td><?=$contact['author']?></td>
                <td><?=$contact['isbn13']?></td>
                <td><?=$contact['format']?></td>
                <td><?=$contact['publisher']?></td>
                <td><?=$contact['pages']?></td>
                <td><?=$contact['dimensions']?></td>
                <td><?=$contact['overview']?></td>
                <td class="actions">
                    <a href="lenen.php?id=<?=$contact['id']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	<div class="pagination">
		<?php if ($page > 1): ?>
		<a href="index.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
		<?php endif; ?>
		<?php if ($page*$records_per_page < $num_contacts): ?>
		<a href="index.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
		<?php endif; ?>
	</div>
</div>

<?=template_footer()?>