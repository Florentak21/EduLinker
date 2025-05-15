<?php
ob_start();
?>

<div class="error-details">
    <i class="fas fa-search fa-3x"></i>
    <p><?= htmlspecialchars(isset($message) ? $message : (isset($_SESSION['error']) ? $_SESSION['error'] : 'La ressource que vous cherchez est introuvable.')) ?></p>
</div>

<?php
$content = ob_get_clean();
$title = isset($title) ? $title : '404 - Page non trouvée';
require_once dirname(__DIR__) . '/layouts/error.php';
?>