<?php $content = ob_start(); ?>
<h1>Connexion</h1>
<form method="POST" action="/authenticate">
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>">
        <?php if (isset($errors['email'])): ?>
            <small class="error"><?php echo htmlspecialchars($errors['email']); ?></small>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Mot de passe</label>
        <input type="password" class="form-control" id="password" name="password">
        <?php if (isset($errors['password'])): ?>
            <small class="error"><?php echo htmlspecialchars($errors['password']); ?></small>
        <?php endif; ?>
    </div>
    <button type="submit" class="btn btn-primary">Se connecter</button>
</form>
<?php $content = ob_get_clean(); require_once dirname(__DIR__, 1) . '/layout.php'; ?>