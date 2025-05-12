<?php $content = ob_start(); ?>

<div class="card">
    <form action="/admin/users/update" method="POST" class="form">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">
        
        <div class="form-row">
            <div class="form-group">
                <label for="firstname">Prénom *</label>
                <input type="text" id="firstname" name="firstname" class="form-control <?= isset($errors['firstname']) ? 'is-invalid' : '' ?>" 
                       value="<?= htmlspecialchars($data['firstname'] ?? $user['firstname']) ?>" required>
                <?php if (isset($errors['firstname'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['firstname']) ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="lastname">Nom *</label>
                <input type="text" id="lastname" name="lastname" class="form-control <?= isset($errors['lastname']) ? 'is-invalid' : '' ?>" 
                       value="<?= htmlspecialchars($data['lastname'] ?? $user['lastname']) ?>" required>
                <?php if (isset($errors['lastname'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['lastname']) ?></div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                       value="<?= htmlspecialchars($data['email'] ?? $user['email']) ?>" required>
                <?php if (isset($errors['email'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['email']) ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="gender">Genre *</label>
                <select id="gender" name="gender" class="form-control <?= isset($errors['gender']) ? 'is-invalid' : '' ?>" required>
                    <option value="">Sélectionner...</option>
                    <option value="M" <?= ($data['gender'] ?? $user['gender']) === 'M' ? 'selected' : '' ?>>Masculin</option>
                    <option value="F" <?= ($data['gender'] ?? $user['gender']) === 'F' ? 'selected' : '' ?>>Féminin</option>
                </select>
                <?php if (isset($errors['gender'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['gender']) ?></div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="form-group">
            <label for="role">Rôle *</label>
            <select id="role" name="role" class="form-control <?= isset($errors['role']) ? 'is-invalid' : '' ?>" required>
                <option value="">Sélectionner un rôle...</option>
                <option value="admin" <?= ($data['role'] ?? $user['role']) === 'admin' ? 'selected' : '' ?>>Administrateur</option>
                <option value="student" <?= ($data['role'] ?? $user['role']) === 'student' ? 'selected' : '' ?>>Étudiant</option>
                <option value="teacher" <?= ($data['role'] ?? $user['role']) === 'teacher' ? 'selected' : '' ?>>Enseignant</option>
            </select>
            <?php if (isset($errors['role'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['role']) ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-actions">
            <a href="/admin/users" class="btn btn-outline">Annuler</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Enregistrer
            </button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once dirname(__DIR__, 2) . '/layouts/admin.php';
?>