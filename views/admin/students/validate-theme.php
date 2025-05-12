<?php $content = ob_start(); ?>

<div class="card">
    <div class="card-header">
        <h2>Valider le thème de <?= htmlspecialchars($student['student_firstname'] . ' ' . $student['student_lastname']) ?></h2>
    </div>
    <div class="card-body">
        <form action="/admin/validate-theme/<?= $student['id'] ?>" method="POST">
            <div class="form-group">
                <label>Thème :</label>
                <p><?= htmlspecialchars($student['theme']) ?></p>
            </div>
            <div class="form-group">
                <label for="teacher_id">Encadreur *</label>
                <select name="teacher_id" id="teacher_id" class="form-control" required>
                    <option value="">Sélectionner un encadreur</option>
                    <?php foreach ($teachers as $teacher): ?>
                        <option value="<?= $teacher['id'] ?>">
                            <?= htmlspecialchars($teacher['firstname'] . ' ' . $teacher['lastname']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-check"></i> Valider
            </button>
            <a href="/admin/students" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once dirname(__DIR__, 2) . '/layouts/admin.php';
?>