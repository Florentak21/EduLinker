<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?= htmlspecialchars($title ?? 'EduLinker') ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h1>EduLinker</h1>
                <p>Administration</p>
            </div>
            <nav class="sidebar-nav">
                <a href="/admin/dashboard" class="<?= $active === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="/admin/domains" class="<?= $active === 'domains' ? 'active' : '' ?>">
                    <i class="fas fa-book"></i> Domaines
                </a>
                <a href="/admin/teachers" class="<?= $active === 'teachers' ? 'active' : '' ?>">
                    <i class="fas fa-chalkboard-teacher"></i> Enseignants
                </a>
                <a href="/admin/students" class="<?= $active === 'students' ? 'active' : '' ?>">
                    <i class="fas fa-user-graduate"></i> Étudiants
                </a>
                <a href="/admin/users" class="<?= $active === 'users' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i> Utilisateurs
                </a>
                <a href="/profile-show" class="<?= $active === 'profile' ? 'active' : '' ?>">
                    <i class="fas fa-user-circle"></i> Profil
                </a>
                <a href="/logout" class="logout" onclick="return confirm('Voulez-vous vraiment vous déconnecter ?');">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="main-header">
                <h1><?= htmlspecialchars($title ?? 'Tableau de bord') ?></h1>
                <div class="user-profile">
                    <span>Admin</span>
                    <div class="avatar">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </div>
            </header>

                <!-- Affichage des messages depuis la redirection -->
                <?php if (isset($success)): ?>
                    <div class="alert success">
                        <i class="fas fa-check-circle"></i>
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($errors['general'])): ?>
                    <div class="alert error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?= htmlspecialchars($errors['general']) ?>
                    </div>
                <?php endif; ?>

                <?= $content ?>
            </div>
        </main>
    </div>
</body>
</html>
<?php
unset($_SESSION['error'], $_SESSION['success']);
?>