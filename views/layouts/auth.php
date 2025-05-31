<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Connexion - EduLinker') ?></title>
    <link rel="stylesheet" href="/css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="auth-background">
        <div class="auth-container">
            <div class="auth-logo">
                <i class="fas fa-graduation-cap"></i>
                <h1>EduLinker</h1>
            </div>
            <?= $content ?>
        </div>
    </div>
</body>
</html>