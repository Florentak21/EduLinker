<?php
ob_start();
?>

<div class="error-details">
    <i class="fas fa-server fa-3x"></i>
    <p><?= htmlspecialchars(isset($message) ? $message : (isset($_SESSION['error']) ? $_SESSION['error'] : 'Une erreur serveur est survenue. Nos équipes sont sur le coup !')) ?></p>
</div>

<?php
$content = ob_get_clean();
$title = isset($title) ? $title : '500 - Erreur serveur';
require_once dirname(__DIR__) . '/layouts/error.php';
?>