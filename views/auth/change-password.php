<?php ob_start(); ?>

<div class="card">
    <h2>Changer mon mot de passe</h2>

    <!-- Affichage des messages depuis la redirection -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert success">
            <i class="fas fa-check-circle"></i>
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert error">
            <i class="fas fa-exclamation-circle"></i>
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
    <?php endif; ?>

    <!-- Affichage des erreurs de formulaire -->
    <?php if (isset($errors['general'])): ?>
        <div class="alert error">
            <i class="fas fa-exclamation-circle"></i>
            <?= htmlspecialchars($errors['general']) ?>
        </div>
    <?php endif; ?>

    <form action="/profile/update/password" method="POST" class="profile-form">
        <div class="form-group">
            <label for="current_password">Mot de passe actuel *</label>
            <input type="password" id="current_password" name="current_password" 
                   class="form-control <?= isset($errors['current_password']) ? 'is-invalid' : '' ?>" 
                   value="" required>
            <?php if (isset($errors['current_password'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['current_password']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="new_password">Nouveau mot de passe *</label>
            <input type="password" id="new_password" name="new_password" 
                   class="form-control <?= isset($errors['new_password']) ? 'is-invalid' : '' ?>" 
                   value="" required>
            <?php if (isset($errors['new_password'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['new_password']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirmer le nouveau mot de passe *</label>
            <input type="password" id="password_confirmation" name="password_confirmation" 
                   class="form-control <?= isset($errors['password_confirmation']) ? 'is-invalid' : '' ?>" 
                   value="" required>
            <?php if (isset($errors['password_confirmation'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['password_confirmation']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-actions">
            <a href="/profile-show" class="btn btn-outline">Annuler</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Mettre à jour
            </button>
        </div>
    </form>
</div>

<?php
unset($_SESSION['success'], $_SESSION['error']);

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