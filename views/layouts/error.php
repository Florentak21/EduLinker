<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(isset($title) ? $title : 'Erreur') ?></title>
    <link rel="stylesheet" href="/css/error.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="error-container">
        <div class="error-content">
            <h1><?= htmlspecialchars(isset($title) ? $title : 'Erreur') ?></h1>
            <p><?= htmlspecialchars(isset($message) ? $message : (isset($_SESSION['error']) ? $_SESSION['error'] : 'Une erreur est survenue.')) ?></p>
            <a href="/" class="btn btn-primary">Retour à l'accueil</a>
        </div>
    </div>
    <?php unset($_SESSION['error'], $_SESSION['success']); // Nettoyer la session après affichage ?>
</body>
</html>