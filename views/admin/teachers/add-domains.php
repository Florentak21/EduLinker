<?php
$content = ob_start();
dump($teacher, $domains)
?>
<h3>Ajouter un domaine à  <?= htmlspecialchars($teacher['firstname'] . ' ' . $teacher['lastname']) ?> </h3>

<div class="card">
    <?php if (isset($errors['general'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($errors['general']) ?></div>
    <?php endif; ?>
    
    <form action="/admin/teachers/<?= $teacher['id'] ?>/domains/store" method="POST" class="form">
        <div class="form-group">
            <label for="domain_id">Domaine *</label>
            <select id="domain_id" name="domain_id" class="form-control <?= isset($errors['domain_id']) ? 'is-invalid' : '' ?>" required>
                <option value="">Sélectionner un domaine...</option>
                <?php foreach ($domains as $domain): ?>
                <option value="<?= $domain['id'] ?>" <?= ((int)($data['domain_id'] ?? 0)) === (int)$domain['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($domain['label']) ?> (<?= htmlspecialchars($domain['code']) ?>)
                </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['domain_id'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['domain_id']) ?></div>
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