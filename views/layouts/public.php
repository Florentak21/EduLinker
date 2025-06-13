<?php
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'custom-links.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'EduLinker - Gestion des mémoires') ?></title>
    <link rel="stylesheet" href="/css/public.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="public-header">
        <div class="container">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
                <h1>EduLinker</h1>
            </div>
            <nav>
                <a href="<?= $results['url'] ?>" class="btn btn-outline"><?= $results['link'] ?></a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/logout" class="logout" onclick="return confirm('Voulez-vous vraiment vous déconnecter ?');">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main>
        <?= $content ?>
    </main>

    <footer class="public-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>EduLinker</h3>
                    <p>Plateforme de gestion des mémoires de fin d'études</p>
                </div>
                <div class="footer-section">
                    <h3>Contact</h3>
                    <p><i class="fas fa-envelope"></i> contact@edulinker.edu</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> EduLinker. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</body>
</html>