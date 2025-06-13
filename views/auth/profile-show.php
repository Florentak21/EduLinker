<?php ob_start(); ?>

<link rel="stylesheet" href="/assets/css/profile.css">

<div class="profile-card">
    <div class="profile-header">
        <h2>Mon profil</h2>
    </div>

    <div class="profile-grid">
        <div class="profile-info-block">
            <p><strong>Prénom</strong><?= htmlspecialchars($user['firstname'] ?? 'Non défini') ?></p>
            <p><strong>Nom</strong><?= htmlspecialchars($user['lastname'] ?? 'Non défini') ?></p>
            <p><strong>Email</strong><?= htmlspecialchars($user['email'] ?? 'Non défini') ?></p>
        </div>
        <div class="profile-info-block">
            <p><strong>Genre</strong><?= htmlspecialchars($user['gender'] === 'M' ? 'Masculin' : 'Féminin') ?></p>
            <p><strong>Rôle</strong><?= htmlspecialchars(ucfirst($user['role'] ?? 'Non défini')) ?></p>
            <?php if ($user['role'] === 'student' && isset($user['matricule'])): ?>
                <p><strong>Matricule</strong><?= htmlspecialchars($user['matricule']) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="profile-actions">
        <a href="/profile-update"><i class="fas fa-edit"></i> Modifier mes infos</a>
        <a href="/profile-password"><i class="fas fa-lock"></i> Changer mon mot de passe</a>
    </div>
</div>

<?php
switch ($_SESSION['user_role'] ?? '') {
    case 'admin':
        $layout = dirname(__DIR__, 1) . '/layouts/admin.php';
        break;
    case 'teacher':
        $layout = dirname(__DIR__, 1) . '/layouts/teacher.php';
        break;
    case 'student':
        $layout = dirname(__DIR__, 1) . '/layouts/student.php';
        break;
    default:
        $layout = dirname(__DIR__, 1) . '/layouts/auth.php';
}

$content = ob_get_clean();
require_once $layout;