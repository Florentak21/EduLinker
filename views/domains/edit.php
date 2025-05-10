<?php $content = ob_start(); ?>
<h1>Modifier un Domaine</h1>
<form method="POST" action="/domains/update">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($domain['id']); ?>">
    <div class="mb-3">
        <label for="code" class="form-label">Code</label>
        <input type="text" class="form-control" id="code" name="code" value="<?php echo htmlspecialchars($data['code'] ?? $domain['code']); ?>">
        <?php if (isset($errors['code'])): ?>
            <small class="error"><?php echo htmlspecialchars($errors['code']); ?></small>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="label" class="form-label">Libellé</label>
        <input type="text" class="form-control" id="label" name="label" value="<?php echo htmlspecialchars($data['label'] ?? $domain['label']); ?>">
        <?php if (isset($errors['label'])): ?>
            <small class="error"><?php echo htmlspecialchars($errors['label']); ?></small>
        <?php endif; ?>
    </div>
    <button type="submit" class="btn btn-primary">Modifier</button>
    <a href="/domains" class="btn btn-secondary">Annuler</a>
</form>
<?php $content = ob_get_clean(); require_once dirname(__DIR__, 1) . '/layout.php'; ?>
