<?php
/* Si l'utilisateur n'est pas connecté et qu'il n'est pas sur la page de connexion, rediriger vers /login
if (!isset($_SESSION['user_id']) && $_SERVER['REQUEST_URI'] !== '/login' && $_SERVER['REQUEST_URI'] !== '/authenticate') {
    header('Location: /login');
    exit;
} */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Étudiants</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding-top: 20px; }
        .container { max-width: 1200px; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
            <div class="container-fluid">
                <a class="navbar-brand" href="/login">Gestion des Étudiants</a>
                <div class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a class="nav-link" href="/users">Utilisateurs</a>
                        <a class="nav-link" href="/students">Étudiants</a>
                        <a class="nav-link" href="/teachers">Enseignants</a>
                        <a class="nav-link" href="/domains">Domaines</a>
                        <a class="nav-link" href="/logout">Déconnexion</a>
                    <?php else: ?>
                        <a class="nav-link" href="/login">Connexion</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>

        <?php if (isset($error) && !empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php echo $content; // Contenu spécifique de chaque page ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>