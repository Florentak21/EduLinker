<?php $content = ob_start(); ?>
<div class="alert alert-danger">
    <h1>Erreur 404 - Page non trouvée</h1>
    <p>La page demandée n'existe pas.</p>
    <a href="/" class="btn btn-primary">Retour à l'accueil</a>
</div>
<?php $content = ob_get_clean(); require_once dirname(__DIR__) . '/layout.php'; ?>