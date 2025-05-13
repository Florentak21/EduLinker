<?php ob_start(); ?>

<div class="card">
    <h2>Créer un compte</h2>
    
    <!-- Affichage des messages depuis la redirection -->
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
    
    <form action="/register/store" method="POST" class="form">
        <div class="form-row">
            <div class="form-group">
                <label for="firstname">Prénom *</label>
                <input type="text" id="firstname" name="firstname" class="form-control <?= isset($errors['firstname']) ? 'is-invalid' : '' ?>" 
                       value="<?= htmlspecialchars($data['firstname'] ?? '') ?>" required>
                <?php if (isset($errors['firstname'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['firstname']) ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="lastname">Nom *</label>
                <input type="text" id="lastname" name="lastname" class="form-control <?= isset($errors['lastname']) ? 'is-invalid' : '' ?>" 
                       value="<?= htmlspecialchars($data['lastname'] ?? '') ?>" required>
                <?php if (isset($errors['lastname'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['lastname']) ?></div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                       value="<?= htmlspecialchars($data['email'] ?? '') ?>" required>
                <?php if (isset($errors['email'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['email']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="gender">Genre *</label>
                <select id="gender" name="gender" class="form-control <?= isset($errors['gender']) ? 'is-invalid' : '' ?>" required>
                    <option value="">Sélectionner...</option>
                    <option value="M" <?= ($data['gender'] ?? '') === 'M' ? 'selected' : '' ?>>Masculin</option>
                    <option value="F" <?= ($data['gender'] ?? '') === 'F' ? 'selected' : '' ?>>Féminin</option>
                </select>
                <?php if (isset($errors['gender'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['gender']) ?></div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="role">Rôle *</label>
                <select id="role" name="role" class="form-control <?= isset($errors['role']) ? 'is-invalid' : '' ?>" required>
                    <option value="">Sélectionner...</option>
                    <option value="student" <?= ($data['role'] ?? '') === 'student' ? 'selected' : '' ?>>Étudiant</option>
                    <option value="teacher" <?= ($data['role'] ?? '') === 'teacher' ? 'selected' : '' ?>>Enseignant</option>
                </select>
                <?php if (isset($errors['role'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['role']) ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="domain_id">Domaine *</label>
                <select id="domain_id" name="domain_id" class="form-control <?= isset($errors['domain_id']) ? 'is-invalid' : '' ?>" required>
                    <option value="">Sélectionner un domaine...</option>
                    <?php foreach ($domains as $domain): ?>
                    <option value="<?= $domain['id'] ?>" <?= ($data['domain_id'] ?? '') == $domain['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($domain['label']) ?> (<?= htmlspecialchars($domain['code']) ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['domain_id'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['domain_id']) ?></div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="password">Mot de passe *</label>
                <div class="password-input-container">
                    <input type="password" id="password" name="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" required>
                    <i class="fas fa-eye password-toggle-icon"></i>
                </div>
                <?php if (isset($errors['password'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['password']) ?></div>
                <?php endif; ?>
                <small class="hint">Minimum 8 caractères</small>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmation *</label>
                <div class="password-input-container">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control <?= isset($errors['password_confirmation']) ? 'is-invalid' : '' ?>" required>
                    <i class="fas fa-eye password-toggle-icon"></i>
                </div>
                <?php if (isset($errors['password_confirmation'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['password_confirmation']) ?></div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="form-actions">
            <a href="/" class="btn btn-outline">Annuler</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Enregistrer
            </button>
        </div>
    </form>
</div>

<?php
unset($_SESSION['error']);

$content = ob_get_clean();
require_once dirname(__DIR__, 1) . '/layouts/auth.php';
?>