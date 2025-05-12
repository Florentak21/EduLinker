<?php
use App\Models\Teacher;

$content = ob_start();
?>

<div class="card">
    <div class="card-header">
        <div class="card-actions">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Rechercher un enseignant...">
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
                    <th>Enseignant</th>
                    <th>Domaine</th>
                    <th>Étudiants</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($teachers as $teacher): ?>
                <tr>
                    <td>
                        <div class="user-cell">
                            <div class="user-avatar">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <div class="user-info">
                                <h3><?= htmlspecialchars($teacher['firstname'] . ' ' . strtoupper($teacher['lastname'])) ?></h3>
                                <p><?= htmlspecialchars($teacher['email']) ?></p>
                            </div>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($teacher['label']) ?></td>
                    <td><?= Teacher::countAssignedStudents($teacher['id']) ?? 0 ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="/admin/teachers/edit/<?= $teacher['id'] ?>" class="btn btn-sm btn-outline" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="/admin/teachers/destroy/<?= $teacher['id'] ?>" class="btn btn-sm btn-danger" title="Supprimer" 
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet enseignant ?')">
                                <i class="fas fa-trash"></i>
                            </a>
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
$content = ob_get_clean();
require_once dirname(__DIR__, 2) . '/layouts/admin.php';
?>