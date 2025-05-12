<?php ob_start(); ?>

<div class="card">
    <h2>Mon profil</h2>

    <!-- Affichage des informations de l'utilisateur -->
    <div class="profile-info">
        <p><strong>Prénom :</strong> <?= htmlspecialchars($user['firstname'] ?? 'Non défini') ?></p>
        <p><strong>Nom :</strong> <?= htmlspecialchars($user['lastname'] ?? 'Non défini') ?></p>
        <p><strong>Email :</strong> <?= htmlspecialchars($user['email'] ?? 'Non défini') ?></p>
        <p><strong>Genre :</strong> <?= htmlspecialchars($user['gender'] === 'M' ? 'Masculin' : 'Féminin') ?></p>
        <p><strong>Rôle :</strong> <?= htmlspecialchars(ucfirst($user['role'] ?? 'Non défini')) ?></p>
        <?php if ($user['role'] === 'student' && isset($user['matricule'])): ?>
            <p><strong>Matricule :</strong> <?= htmlspecialchars($user['matricule']) ?></p>
        <?php endif; ?>
    </div>

    <!-- Liens pour modifier les infos ou changer de mot de passe -->
    <div class="profile-actions">
        <a href="/profile-update" class="btn btn-primary">
            <i class="fas fa-edit"></i> Modifier mes infos
        </a>
        <a href="/profile-password" class="btn btn-secondary">
            <i class="fas fa-lock"></i> Changer mon mot de passe
        </a>
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
?>