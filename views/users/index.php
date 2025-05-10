<?php $content = ob_start(); ?>
<h1>Liste des Utilisateurs</h1>
<a href="/users/create" class="btn btn-primary mb-3">Créer un nouvel utilisateur</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Genre</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['id']); ?></td>
                <td><?php echo htmlspecialchars($user['firstname']); ?></td>
                <td><?php echo htmlspecialchars($user['lastname']); ?></td>
                <td><?php echo htmlspecialchars($user['gender']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['role']); ?></td>
                <td>
                    <a href="/users/edit/<?php echo $user['id']; ?>" class="btn btn-sm btn-warning">Modifier</a>
                    <a href="/users/destroy/<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php $content = ob_get_clean(); require_once dirname(__DIR__, 1) . '/layout.php'; ?>
