<?php $content = ob_start(); ?>
<h1>Liste des Étudiants</h1>
<a href="/students/create" class="btn btn-primary mb-3">Créer un nouvel étudiant</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Matricule</th>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Thème</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($students as $student): ?>
            <tr>
                <td><?php echo htmlspecialchars($student['id']); ?></td>
                <td><?php echo htmlspecialchars($student['matricule']); ?></td>
                <td><?php echo htmlspecialchars($student['firstname'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($student['lastname'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($student['email'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($student['phone']); ?></td>
                <td><?php echo htmlspecialchars($student['theme'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($student['affectation_status']); ?></td>
                <td>
                    <a href="/students/edit/<?php echo $student['id']; ?>" class="btn btn-sm btn-warning">Modifier</a>
                    <a href="/students/submit-cdc/<?php echo $student['id']; ?>" class="btn btn-sm btn-info">Soumettre CDC</a>
                    <a href="/students/destroy/<?php echo $student['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?');">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php $content = ob_get_clean(); require_once dirname(__DIR__, 1) . '/layout.php'; ?>
