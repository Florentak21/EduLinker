<?php $content = ob_start(); ?>

<div class="card">

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

    <div class="card-header">
        <div class="card-actions">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Rechercher un utilisateur...">
            </div>
            <a href="/admin/users/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Rôle</th>
                    <th>Email</th>
                    <th>Créé le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td>
                        <div class="user-cell">
                            <div class="user-avatar">
                                <?php if ($user['role'] === 'admin'): ?>
                                    <i class="fas fa-user-shield"></i>
                                <?php elseif ($user['role'] === 'teacher'): ?>
                                    <i class="fas fa-chalkboard-teacher"></i>
                                <?php else: ?>
                                    <i class="fas fa-user-graduate"></i>
                                <?php endif; ?>
                            </div>
                            <div class="user-info">
                                <h3><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></h3>
                                <p><?= htmlspecialchars($user['gender'] === 'M' ? 'Homme' : 'Femme') ?></p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="role-badge <?= $user['role'] ?>">
                            <?= ucfirst($user['role']) ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                    <td>
                        <div class="action-buttons">
                            <?php if ($_SESSION['user_id'] === $user['id']): ?>
                                <span>moi meme</span>
                            <?php else: ?>
                                <a href="/admin/users/edit/<?= $user['id'] ?>" class="btn btn-sm btn-outline" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/admin/users/destroy/<?= $user['id'] ?>" class="btn btn-sm btn-danger" title="Supprimer" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        <div class="pagination">
            <a href="#" class="page-item disabled"><i class="fas fa-chevron-left"></i></a>
            <a href="#" class="page-item active">1</a>
            <a href="#" class="page-item">2</a>
            <a href="#" class="page-item">3</a>
            <a href="#" class="page-item"><i class="fas fa-chevron-right"></i></a>
        </div>
    </div>
</div>

<?php
unset($_SESSION['error'], $_SESSION['success']);
$content = ob_get_clean();
require_once dirname(__DIR__, 2) . '/layouts/admin.php';
?>