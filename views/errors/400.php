<?php $content = ob_start(); ?>
<div class="alert alert-danger">
    <h1>Erreur 400 - Requête incorrecte</h1>
    <p><?php echo htmlspecialchars($error ?? 'Requête invalide.'); ?></p>
    <a href="/" class="btn btn-primary">Retour à l'accueil</a>
</div>
<?php $content = ob_get_clean(); require_once dirname(__DIR__) . '/layout.php'; ?>