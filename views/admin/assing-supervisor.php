<?php

use App\Models\Teacher;

 $content = ob_start(); ?>
<h1>Affecter un encadreur à l'étudiant #<?php echo htmlspecialchars($student['id']); ?></h1>

<h3>Détails de l'étudiant</h3>
<ul>
    <li><strong>Étudiant :</strong> <?php echo htmlspecialchars($student['firstname'] . ' ' . $student['lastname']); ?></li>
    <li><strong>Thème :</strong> <?php echo htmlspecialchars($student['theme']); ?></li>
    <li><strong>Encadreur actuel :</strong> 
        <?php if ($student['teacher_id']): ?>
            <?php 
                $currentTeacher = Teacher::find($student['teacher_id']);
                echo htmlspecialchars($currentTeacher['firstname'] . ' ' . $currentTeacher['lastname']);
            ?>
        <?php else: ?>
            Aucun encadreur
        <?php endif; ?>
    </li>
</ul>

<h3>Choisir un nouvel encadreur</h3>
<form method="POST" action="/admin/assign-supervisor/<?php echo htmlspecialchars($student['id']); ?>">
    <div class="mb-3">
        <label for="teacher_id" class="form-label">Encadreur</label>
        <select class="form-control" id="teacher_id" name="teacher_id" required>
            <option value="">Sélectionner un encadreur</option>
            <?php foreach ($teachers as $teacher): ?>
                <option value="<?php echo htmlspecialchars($teacher['id']); ?>" <?php echo ($student['teacher_id'] == $teacher['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($teacher['firstname'] . ' ' . $teacher['lastname']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Affecter</button>
    <a href="/admin/pending-themes" class="btn btn-secondary">Annuler</a>
</form>

<?php $content = ob_get_clean(); require_once dirname(__DIR__, 1) . '/layout.php'; ?>