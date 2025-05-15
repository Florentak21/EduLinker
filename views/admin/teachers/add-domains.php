<?php
$content = ob_start();
?>

<div class="card">
    <div class="card-header">
        <h2>Ajouter un domaine à <?= htmlspecialchars($teacher['firstname'] . ' ' . $teacher['lastname']) ?></h2>
    </div>

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

    <div class="card-body">
        <form action="/admin/teachers/<?= $teacher['user_id'] ?>/domains/store" method="POST" class="form">
            <div class="form-group">
                <label for="domain_id">Domaine *</label>
                <select id="domain_id" name="domain_id" class="form-control <?= isset($errors['domain_id']) ? 'is-invalid' : '' ?>" required>
                    <option value="">Sélectionner un domaine...</option>
                    <?php foreach ($domains as $domain): ?>
                        <option value="<?= $domain['id'] ?>" <?= isset($data['domain_id']) && (int)$data['domain_id'] === (int)$domain['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($domain['label']) ?> (<?= htmlspecialchars($domain['code']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['domain_id'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['domain_id']) ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-actions">
                <a href="/admin/teachers/<?= $teacher['user_id'] ?>/domains" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Ajouter
                </button>
            </div>
        </form>
    </div>
</div>

<?php
unset($_SESSION['error'], $_SESSION['success']);
$content = ob_get_clean();
require_once dirname(__DIR__, 2) . '/layouts/admin.php';
?>