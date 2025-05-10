<?php $content = ob_start(); ?>
<h1>Liste des Domaines</h1>
<a href="/domains/create" class="btn btn-primary mb-3">Créer un nouveau domaine</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Code</th>
            <th>Libellé</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($domains as $domain): ?>
            <tr>
                <td><?php echo htmlspecialchars($domain['id']); ?></td>
                <td><?php echo htmlspecialchars($domain['code']); ?></td>
                <td><?php echo htmlspecialchars($domain['label']); ?></td>
                <td>
                    <a href="/domains/edit/<?php echo $domain['id']; ?>" class="btn btn-sm btn-warning">Modifier</a>
                    <a href="/domains/destroy/<?php echo $domain['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce domaine ?');">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php $content = ob_get_clean(); require_once dirname(__DIR__, 1) . '/layout.php'; ?>