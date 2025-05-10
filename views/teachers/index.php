<?php $content = ob_start(); ?>
<h1>Liste des Enseignants</h1>
<a href="/teachers/create" class="btn btn-primary mb-3">Créer un nouvel enseignant</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Domaine</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($teachers as $teacher): ?>
            <tr>
                <td><?php echo htmlspecialchars($teacher['id']); ?></td>
                <td><?php echo htmlspecialchars($teacher['firstname'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($teacher['lastname'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($teacher['email'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($teacher['phone']); ?></td>
                <td>
                    <?php
                    $domain = \App\Models\Domain::find($teacher['domain_id']);
                    echo htmlspecialchars($domain['label'] ?? 'N/A');
                    ?>
                </td>
                <td>
                    <a href="/teachers/edit/<?php echo $teacher['id']; ?>" class="btn btn-sm btn-warning">Modifier</a>
                    <a href="/teachers/destroy/<?php echo $teacher['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet enseignant ?');">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php $content = ob_get_clean(); require_once dirname(__DIR__, 1) . '/layout.php'; ?>
