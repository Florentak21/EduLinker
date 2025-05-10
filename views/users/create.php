<?php $content = ob_start(); ?>
<h1>Créer un Utilisateur</h1>
<form method="POST" action="/register/store">
    <div class="mb-3">
        <label for="firstname" class="form-label">Prénom</label>
        <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo htmlspecialchars($data['firstname'] ?? ''); ?>">
        <?php if (isset($errors['firstname'])): ?>
            <small class="error"><?php echo htmlspecialchars($errors['firstname']); ?></small>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="lastname" class="form-label">Nom</label>
        <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo htmlspecialchars($data['lastname'] ?? ''); ?>">
        <?php if (isset($errors['lastname'])): ?>
            <small class="error"><?php echo htmlspecialchars($errors['lastname']); ?></small>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="gender" class="form-label">Genre</label>
        <select class="form-control" id="gender" name="gender">
            <option value="M" <?php echo (isset($data['gender']) && $data['gender'] === 'M') ? 'selected' : ''; ?>>Masculin</option>
            <option value="F" <?php echo (isset($data['gender']) && $data['gender'] === 'F') ? 'selected' : ''; ?>>Féminin</option>
        </select>
        <?php if (isset($errors['gender'])): ?>
            <small class="error"><?php echo htmlspecialchars($errors['gender']); ?></small>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>">
        <?php if (isset($errors['email'])): ?>
            <small class="error"><?php echo htmlspecialchars($errors['email']); ?></small>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="role" class="form-label">Rôle</label>
        <select class="form-control" id="role" name="role">
            <option value="teacher" <?php echo (isset($data['role']) && $data['role'] === 'teacher') ? 'selected' : ''; ?>>Enseignant</option>
            <option value="admin" <?php echo (isset($data['role']) && $data['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
        </select>
        <?php if (isset($errors['role'])): ?>
            <small class="error"><?php echo htmlspecialchars($errors['role']); ?></small>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Mot de passe</label>
        <input type="password" class="form-control" id="password" name="password">
        <?php if (isset($errors['password'])): ?>
            <small class="error"><?php echo htmlspecialchars($errors['password']); ?></small>
        <?php endif; ?>
    </div>
    <button type="submit" class="btn btn-primary">Créer</button>
    <a href="/users" class="btn btn-secondary">Annuler</a>
</form>
<?php $content = ob_get_clean(); require_once dirname(__DIR__, 1) . '/layout.php'; ?>
