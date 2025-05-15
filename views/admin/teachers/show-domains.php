<?php

use App\Models\Teacher;

$content = ob_start();
?>

<div class="card">
    <div class="card-header">
        <h2>Domaines associés à <?= htmlspecialchars($teacher['firstname'] . ' ' . $teacher['lastname']) ?></h2>
        <div class="card-actions">
            <a href="/admin/teachers/<?= $teacher['user_id'] ?>/domains/add" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter un domaine
            </a>
        </div>
    </div>

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

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Label</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($domains)): ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted">Aucun domaine associé.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($domains as $domain): ?>
                    <tr>
                        <td><?= htmlspecialchars($domain['code']) ?></td>
                        <td><?= htmlspecialchars($domain['label']) ?></td>
                        <td>
                            <div class="action-buttons">
                                <?php
                                // Vérifier si des étudiants sont assignés à ce domaine
                                $studentCount = Teacher::countStudentsByDomain($teacher['user_id'], $domain['id']);
                                $isLastDomain = count($domains) <= 1;
                                $canDelete = !$isLastDomain && $studentCount === 0;
                                ?>
                                <?php if ($canDelete): ?>
                                    <form action="/admin/teachers/<?= $teacher['user_id'] ?>/domains/remove/<?= $domain['id'] ?>" method="POST">
                                        <button class="btn btn-sm btn-danger" title="Supprimer"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce domaine pour cet enseignant ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        <a href="/admin/teachers" class="btn btn-secondary">Retour à la liste</a>
    </div>
</div>

<?php
unset($_SESSION['error'], $_SESSION['success']);
$content = ob_get_clean();
require_once dirname(__DIR__, 2) . '/layouts/admin.php';
?>