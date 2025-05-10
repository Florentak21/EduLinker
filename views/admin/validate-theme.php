<?php $content = ob_start(); ?>
<h1>Valider le thème de l'étudiant #<?php echo htmlspecialchars($student['id']); ?></h1>

<h3>Détails du thème</h3>
<ul>
    <li><strong>Étudiant :</strong> <?php echo htmlspecialchars($student['firstname'] . ' ' . $student['lastname']); ?></li>
    <li><strong>Thème :</strong> <?php echo htmlspecialchars($student['theme']); ?></li>
    <li><strong>Description :</strong> <?php echo htmlspecialchars($student['description']); ?></li>
    <li><strong>Fichier CDC :</strong> 
        <?php if ($student['cdc']): ?>
            <a href="/uploads/<?php echo htmlspecialchars($student['cdc']); ?>" target="_blank">Télécharger</a>
        <?php else: ?>
            Aucun fichier
        <?php endif; ?>
    </li>
    <li><strong>Binôme :</strong> 
        <?php if ($student['has_binome'] && $student['matricule_binome']): ?>
            <?php echo htmlspecialchars($student['matricule_binome']); ?>
        <?php else: ?>
            Aucun binôme
        <?php endif; ?>
    </li>
    <li><strong>Date de soumission :</strong> <?php echo htmlspecialchars($student['submitted_at']); ?></li>
</ul>

<h3>Choisir un encadreur</h3>
<form method="POST" action="/admin/validate-theme/<?php echo htmlspecialchars($student['id']); ?>">
    <div class="mb-3">
        <label for="teacher_id" class="form-label">Encadreur</label>
        <select class="form-control" id="teacher_id" name="teacher_id" required>
            <option value="">Sélectionner un encadreur</option>
            <?php foreach ($teachers as $teacher): ?>
                <option value="<?php echo htmlspecialchars($teacher['id']); ?>">
                    <?php echo htmlspecialchars($teacher['firstname'] . ' ' . $teacher['lastname']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Valider et affecter</button>
    <a href="/admin/pending-themes" class="btn btn-secondary">Annuler</a>
</form>

<?php $content = ob_get_clean(); require_once dirname(__DIR__, 1) . '/layout.php'; ?>