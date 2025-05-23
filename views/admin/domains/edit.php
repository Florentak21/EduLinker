<?php $content = ob_start(); ?>

<div class="card">

    <!-- Affichage des messages depuis la redirection -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert error">
            <i class="fas fa-exclamation-circle"></i>
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert success">
            <i class="fas fa-check-circle"></i>
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
    <?php endif; ?>
    
    <form action="/admin/domains/update" method="POST" class="form">
        <input type="hidden" name="id" value="<?= $domain['id'] ?>">
        
        <div class="form-group">
            <label for="code">Code du domaine *</label>
            <input type="text" id="code" name="code" class="form-control <?= isset($errors['code']) ? 'is-invalid' : '' ?> <?= isset($errors['domain']) ? 'is-invalid' : '' ?>" 
                   value="<?= htmlspecialchars($data['code'] ?? $domain['code']) ?>" required>
            <?php if (isset($errors['code'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['code']) ?></div>
            <?php endif; ?>
            <?php if (isset($errors['domain'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['domain']) ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="label">Libellé du domaine *</label>
            <input type="text" id="label" name="label" class="form-control <?= isset($errors['label']) ? 'is-invalid' : '' ?> <?= isset($errors['domain']) ? 'is-invalid' : '' ?>" 
                   value="<?= htmlspecialchars($data['label'] ?? $domain['label']) ?>" required>
            <?php if (isset($errors['label'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['label']) ?></div>
            <?php endif; ?>
            <?php if (isset($errors['domain'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['domain']) ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-actions">
            <a href="/admin/domains" class="btn btn-outline">Annuler</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Enregistrer
            </button>
        </div>
    </form>
</div>

<?php
unset($_SESSION['error'], $_SESSION['success']);
$content = ob_get_clean();
require_once dirname(__DIR__, 2) . '/layouts/admin.php';
?>