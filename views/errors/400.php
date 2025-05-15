<?php
ob_start();
?>

<div class="error-details">
    <i class="fas fa-exclamation-triangle fa-3x"></i>
    <p><?= htmlspecialchars(isset($message) ? $message : (isset($_SESSION['error']) ? $_SESSION['error'] : 'Une erreur est survenue lors du traitement de votre requête.')) ?></p>
</div>

<?php
$content = ob_get_clean();
$title = isset($title) ? $title : '400 - Requête incorrecte';
require_once dirname(__DIR__) . '/layouts/error.php';
?>