<?php $content = ob_start(); ?>
<h1>Gestion des thèmes</h1>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if (empty($students)): ?>
    <p>Aucun thème à gérer.</p>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Étudiant</th>
                <th>Thème</th>
                <th>Description</th>
                <th>Fichier CDC</th>
                <th>Binôme</th>
                <th>Date de soumission</th>
                <th>Statut</th>
                <th>Encadreur</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?php echo htmlspecialchars($student['id']); ?></td>
                    <td><?php echo htmlspecialchars($student['firstname'] . ' ' . $student['lastname']); ?></td>
                    <td><?php echo htmlspecialchars($student['theme']); ?></td>
                    <td><?php echo htmlspecialchars($student['description']); ?></td>
                    <td>
                        <?php if ($student['cdc']): ?>
                            <a href="/uploads/<?php echo htmlspecialchars($student['cdc']); ?>" target="_blank">Télécharger</a>
                        <?php else: ?>
                            Aucun fichier
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($student['has_binome'] && $student['matricule_binome']): ?>
                            <?php echo htmlspecialchars($student['matricule_binome']); ?>
                        <?php else: ?>
                            Aucun binôme
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($student['submitted_at']); ?></td>
                    <td><?php echo htmlspecialchars($student['theme_status']); ?></td>
                    <td>
                        <?php if ($student['teacher_id']): ?>
                            <?php 
                                $teacher = \App\Models\Teacher::find($student['teacher_id']);
                                echo htmlspecialchars($teacher['firstname'] . ' ' . $teacher['lastname']);
                            ?>
                        <?php else: ?>
                            Aucun encadreur
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($student['theme_status'] === 'en-traitement'): ?>
                            <a href="/admin/validate-theme/<?php echo $student['id']; ?>" class="btn btn-success btn-sm">Valider</a>
                            <a href="/admin/cancel-theme/<?php echo $student['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir annuler ce thème ?');">Annuler</a>
                        <?php elseif ($student['theme_status'] === 'validé'): ?>
                            <a href="/admin/assign-supervisor/<?php echo $student['id']; ?>" class="btn btn-primary btn-sm">Affecter/Modifier encadreur</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<a href="/students" class="btn btn-secondary">Retour</a>

<?php $content = ob_get_clean(); require_once dirname(__DIR__, 1) . '/layout.php'; ?>