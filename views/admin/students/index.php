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
            <a href="/admin/users/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Étudiant</th>
                    <th>Matricule</th>
                    <th>Domaine</th>
                    <th>Encadreur</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                <tr>
                    <td>
                        <div class="user-cell">
                            <div class="user-avatar">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="user-info">
                                <h3><?= htmlspecialchars($student['student_firstname'] . ' ' . $student['student_lastname']) ?></h3>
                                <p><?= htmlspecialchars($student['student_email']) ?></p>
                            </div>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($student['matricule']) ?></td>
                    <td><?= htmlspecialchars($student['domain_label']) ?></td>
                    <td>
                        <?php if ($student['teacher_id']): ?>
                            <?= htmlspecialchars($student['teacher_firstname'] . ' ' . $student['teacher_lastname']) ?>
                        <?php else: ?>
                            <span class="text-muted">Non attribué</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="status-badge <?= strtolower(str_replace('-', '', $student['theme_status'])) ?>">
                            <?= ucfirst($student['theme_status']) ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="/admin/students/show-theme/<?= $student['id'] ?>" class="btn btn-sm btn-outline" title="Thème">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="/admin/students/edit/<?= $student['id'] ?>" class="btn btn-sm btn-outline" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="/admin/students/destroy/<?= $student['id'] ?>" title="Supprimer" class="btn btn-sm btn-danger" 
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?')">
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