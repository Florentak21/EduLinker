<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enseignant - <?= htmlspecialchars($title ?? 'EduLinker') ?></title>
    <link rel="stylesheet" href="/css/teacher.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="teacher-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="avatar">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <h3><?= htmlspecialchars($teacher['firstname'] ?? 'Enseignant') ?></h3>
                <p><?= htmlspecialchars($teacher['domain'] ?? '') ?></p>
            </div>
            <nav class="sidebar-nav">
                <a href="/teacher/dashboard" class="<?= $active === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="/teacher/students" class="<?= $active === 'students' ? 'active' : '' ?>">
                    <i class="fas fa-user-graduate"></i> Mes étudiants
                </a>
                <a href="/logout" class="logout">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="main-header">
                <h1><?= htmlspecialchars($title ?? 'Tableau de bord') ?></h1>
                <div class="header-actions">
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="badge">2</span>
                    </div>
                </div>
            </header>

            <div class="content-container">
                <?php if (isset($success)): ?>
                    <div class="alert success">
                        <i class="fas fa-check-circle"></i>
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <?= $content ?>
            </div>
        </main>
    </div>
</body>
</html>