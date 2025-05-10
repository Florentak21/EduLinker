<?php $content = ob_start(); ?>
<h1>Soumettre le CDC pour l'Étudiant #<?php echo htmlspecialchars($student['id']); ?></h1>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form method="POST" action="/students/submit-cdc/<?php echo htmlspecialchars($student['id']); ?>" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="theme" class="form-label">Thème</label>
        <input type="text" class="form-control" id="theme" name="theme" value="<?php echo htmlspecialchars($data['theme'] ?? $student['theme'] ?? ''); ?>">
        <?php if (isset($errors['theme'])): ?>
            <small class="error"><?php echo htmlspecialchars($errors['theme']); ?></small>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="cdc_file" class="form-label">Fichier CDC (PDF)</label>
        <input type="file" class="form-control" id="cdc_file" name="cdc_file" accept=".pdf">
        <?php if (isset($errors['cdc_file'])): ?>
            <small class="error"><?php echo htmlspecialchars($errors['cdc_file']); ?></small>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label>Avez-vous un binôme ?</label>
        <div class="form-check">
            <input type="radio" class="form-check-input" name="has_binome" value="1" <?php echo (isset($data['has_binome']) && $data['has_binome'] == '1') ? 'checked' : ''; ?>>
            <label class="form-check-label">Oui</label>
        </div>
        <div class="form-check">
            <input type="radio" class="form-check-input" name="has_binome" value="0" <?php echo (isset($data['has_binome']) && $data['has_binome'] == '0') ? 'checked' : ''; ?>>
            <label class="form-check-label">Non</label>
        </div>
        <?php if (isset($errors['has_binome'])): ?>
            <small class="error"><?php echo htmlspecialchars($errors['has_binome']); ?></small>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="matricule_binome" class="form-label">Matricule du binôme (si applicable)</label>
        <input type="text" class="form-control" id="matricule_binome" name="matricule_binome" value="<?php echo htmlspecialchars($data['matricule_binome'] ?? ''); ?>">
        <?php if (isset($errors['matricule_binome'])): ?>
            <small class="error"><?php echo htmlspecialchars($errors['matricule_binome']); ?></small>
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary">Soumettre</button>
    <a href="/students" class="btn btn-secondary">Annuler</a>
</form>

<?php $content = ob_get_clean(); require_once dirname(__DIR__, 1) . '/layout.php'; ?>