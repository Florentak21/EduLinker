<?php $content = ob_start(); ?>

<div class="card">
    <div class="card-header">
        <h2>Valider le thème de <?= htmlspecialchars($student['student_firstname'] . ' ' . $student['student_lastname']) ?></h2>
    </div>
    <div class="card-body">
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

        <form action="/admin/students/validate-theme" method="POST">
            <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
            <div class="form-group">
                <label for="theme">Thème du mémoire *</label>
                <input type="text" id="theme" name="theme" class="form-control <?= isset($errors['theme']) ? 'is-invalid' : '' ?>" 
                       value="<?= htmlspecialchars($data['theme'] ?? $student['theme'] ?? '') ?>" required>
                <?php if (isset($errors['theme'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['theme']) ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="description">Description du thème *</label>
                <textarea name="description" id="description" class="form-control <?= isset($errors['description']) ? 'is-invalid' : '' ?>" required>
                    <?= htmlspecialchars($data['description'] ?? $student['theme'] ?? '') ?>
                </textarea>
                <?php if (isset($errors['description'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['description']) ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="teacher_id">Encadreur *</label>
                <select name="teacher_id" id="teacher_id" class="form-control <?= isset($errors['teacher_id']) ? 'is-invalid' : '' ?>" required>
                    <option value="">Sélectionner un encadreur</option>
                    <?php foreach ($teachers as $teacher): ?>
                        <option value="<?= $teacher['id'] ?>" <?= isset($data['teacher_id']) && $data['teacher_id'] == $teacher['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($teacher['firstname'] . ' ' . $teacher['lastname']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['teacher_id'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['teacher_id']) ?></div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-check"></i> Valider
            </button>
            <a href="/admin/students/show-theme/<?= $student['id'] ?>" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once dirname(__DIR__, 2) . '/layouts/admin.php';
?>