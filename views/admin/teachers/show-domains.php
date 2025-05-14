<?php
$content = ob_start();
?>

<div class="">
    <h3>Les domaines associés à <?= htmlspecialchars($teacher['firstname'] . ' ' . $teacher['lastname']) ?> </h3>

    <a href="/admin/teachers/<?= $teacher['id'] ?>/domains/add" class="btn btn-primary">
        <i class="fas fa-plus"></i> Ajouter
    </a>

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
                <?php foreach ($domains as $domain): ?>
                <tr>
                    <td><?= htmlspecialchars($domain['code']) ?></td>
                    <td><?= htmlspecialchars($domain['label']) ?></td>
                    <td>
                        <div class="action-buttons">
                            <form action="/admin/teachers/{id}/domains/remove/{id}" method="POST">
                                <button class="btn btn-sm btn-danger" title="Supprimer"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce domaine pour cet enseignant ?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<?php
$content = ob_get_clean();
require_once dirname(__DIR__, 2) . '/layouts/admin.php';
?>