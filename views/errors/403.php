<?php
ob_start();
?>

<div class="error-details">
    <i class="fas fa-lock fa-3x"></i>
    <p><?= htmlspecialchars(isset($message) ? $message : (isset($_SESSION['error']) ? $_SESSION['error'] : 'Vous n\'êtes pas autorisé à accéder à cette page.')) ?></p>
</div>

<?php
$content = ob_get_clean();
$title = isset($title) ? $title : '403 - Accès interdit';
require_once dirname(__DIR__) . '/layouts/error.php';
?>