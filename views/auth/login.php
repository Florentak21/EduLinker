<?php ob_start(); ?>

<div class="auth-card">
    <div class="auth-header">
        <h2>Connexion à votre compte</h2>
        <p>Entrez vos identifiants pour accéder à la plateforme</p>
    </div>
    
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
    
    <form action="/authenticate" method="POST" class="auth-form">
        <div class="form-group">
            <label for="email">Adresse email</label>
            <input type="email" id="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                   value="<?= htmlspecialchars($data['email'] ?? '') ?>" required>
            <?php if (isset($errors['email'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['email']) ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" required>
            <?php if (isset($errors['password'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['password']) ?></div>
            <?php endif; ?>
        </div>
        
        <button type="submit" class="btn btn-primary btn-block">
            <i class="fas fa-sign-in-alt"></i> Se connecter
        </button>
    </form>
    
    <div class="auth-footer">
        <p>Vous n'avez pas de compte ? <a href="/register">créer un compte</a></p>
    </div>
</div>

<?php
unset($_SESSION['error']);

$content = ob_get_clean();
require_once dirname(__DIR__, 1) . '/layouts/auth.php';
?>