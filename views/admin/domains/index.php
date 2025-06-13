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
        <a href="/admin/domains/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter un domaine
        </a>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Libellé</th>
                    <th>Étudiants</th>
                    <th>Enseignants</th>
                    <th>Créé le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($domains as $domain): ?>
                <tr>
                    <td>
                        <span class="domain-code"><?= htmlspecialchars($domain['code']) ?></span>
                    </td>
                    <td><?= htmlspecialchars($domain['label']) ?></td>
                    <td><?= $domain['student_count'] ?></td>
                    <td><?= $domain['teacher_count'] ?></td>
                    <td><?= date('d/m/Y', strtotime($domain['created_at'])) ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="/admin/domains/edit/<?= $domain['id'] ?>" class="btn btn-sm btn-edit" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="/admin/domains/destroy/<?= $domain['id'] ?>" class="btn btn-sm btn-danger" title="Supprimer" 
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce domaine ?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
unset($_SESSION['error'], $_SESSION['success']);
$content = ob_get_clean();
require_once dirname(__DIR__, 2) . '/layouts/admin.php';
?>