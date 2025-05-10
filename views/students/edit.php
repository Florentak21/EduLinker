<?php $content = ob_start(); ?>
<h1>Modifier un Étudiant</h1>
<form method="POST" action="/students/update">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($student['id']); ?>">
    <div class="mb-3">
        <label for="matricule" class="form-label">Matricule</label>
        <input type="text" class="form-control" id="matricule" value="<?php echo htmlspecialchars($student['matricule']); ?>" readonly>
    </div>
    <div class="mb-3">
        <label for="firstname" class="form-label">Prénom</label>
        <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo htmlspecialchars($data['firstname'] ?? $student['firstname']); ?>">
        <?php if (isset($errors['firstname'])): ?>
            <small class="error"><?php echo htmlspecialchars($errors['firstname']); ?></small>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="lastname" class="form-label">Nom</label>
        <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo htmlspecialchars($data['lastname'] ?? $student['lastname']); ?>">
        <?php if (isset($errors['lastname'])): ?>
            <small class="error"><?php echo htmlspecialchars($errors['lastname']); ?></small>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="gender" class="form-label">Genre</label>
        <select class="form-control" id="gender" name="gender">
            <option value="M" <?php echo (isset($data['gender']) ? $data['gender'] : $student['gender']) === 'M' ? 'selected' : ''; ?>>Masculin</option>
            <option value="F" <?php echo (isset($data['gender']) ? $data['gender'] : $student['gender']) === 'F' ? 'selected' : ''; ?>>Féminin</option>
        </select>
        <?php if (isset($errors['gender'])): ?>
            <small class="error"><?php echo htmlspecialchars($errors['gender']); ?></small>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($data['email'] ?? $student['email']); ?>">
        <?php if (isset($errors['email'])): ?>
            <small class="error"><?php echo htmlspecialchars($errors['email']); ?></small>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="domain_id" class="form-label">Domaine</label>
        <select class="form-control" id="domain_id" name="domain_id">
            <option value="">Sélectionner un domaine</option>
            <?php foreach ($domains as $domain): ?>
                <option value="<?php echo $domain['id']; ?>" <?php echo (isset($data['domain_id']) ? $data['domain_id'] : $student['domain_id']) == $domain['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($domain['label']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors['domain_id'])): ?>
            <small class="error"><?php echo htmlspecialchars($errors['domain_id']); ?></small>
        <?php endif; ?>
    </div>
    <button type="submit" class="btn btn-primary">Modifier</button>
    <a href="/students" class="btn btn-secondary">Annuler</a>
</form>
<?php $content = ob_get_clean(); require_once dirname(__DIR__, 1) . '/layout.php'; ?>