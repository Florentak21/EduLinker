<?php ob_start(); ?>

<div class="card">
    <h2>Modifier mon profil</h2>

    <!-- Affichage des erreurs de formulaire -->
    <?php if (isset($errors['general'])): ?>
        <div class="alert error">
            <i class="fas fa-exclamation-circle"></i>
            <?= htmlspecialchars($errors['general']) ?>
        </div>
    <?php endif; ?>

    <form action="/profile/update/infos" method="POST" class="profile-form">
        <div class="form-group">
            <label for="lastname">Nom *</label>
            <input type="text" id="lastname" name="lastname" class="form-control <?= isset($errors['lastname']) ? 'is-invalid' : '' ?>" 
                   value="<?= htmlspecialchars($data['lastname'] ?? $user['lastname'] ?? '') ?>" required>
            <?php if (isset($errors['lastname'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['lastname']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="firstname">Prénom *</label>
            <input type="text" id="firstname" name="firstname" class="form-control <?= isset($errors['firstname']) ? 'is-invalid' : '' ?>" 
                   value="<?= htmlspecialchars($data['firstname'] ?? $user['firstname'] ?? '') ?>" required>
            <?php if (isset($errors['firstname'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['firstname']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                   value="<?= htmlspecialchars($data['email'] ?? $user['email'] ?? '') ?>" required>
            <?php if (isset($errors['email'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['email']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="gender">Genre *</label>
            <select id="gender" name="gender" class="form-control <?= isset($errors['gender']) ? 'is-invalid' : '' ?>" required>
                <option value="M" <?= ($data['gender'] ?? $user['gender'] ?? '') === 'M' ? 'selected' : '' ?>><?= htmlspecialchars('Masculin') ?></option>
                <option value="F" <?= ($data['gender'] ?? $user['gender'] ?? '') === 'F' ? 'selected' : '' ?>><?= htmlspecialchars('Féminin') ?></option>
            </select>
            <?php if (isset($errors['gender'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['gender']) ?></div>
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