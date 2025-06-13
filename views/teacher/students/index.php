<?php
use App\Models\Student;
$content = ob_start();
?>

<div class="card">
    <div class="card-header">
        <h2>Liste de mes étudiants encadrés</h2>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Étudiant</th>
                    <th>Matricule</th>
                    <th>Thème</th>
                    <th>Statut</th>
                    <th>Date d'affectation</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                <tr>
                    <td>
                        <div class="student-cell">
                            <div class="student-avatar">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="student-info">
                                <h3><?= htmlspecialchars($student['firstname'] . ' ' . $student['lastname']) ?></h3>
                                <p><?= htmlspecialchars($student['email']) ?></p>
                            </div>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($student['matricule']) ?></td>
                    <td><?= htmlspecialchars($student['theme']) ?></td>
                    <td>
                        <span class="status-badge <?= strtolower(str_replace('-', '', $student['theme_status'])) ?>">
                            <?= ucfirst($student['theme_status']) ?>
                        </span>
                    </td>
                    <td><?= date('d/m/Y', strtotime($student['assigned_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
unset($_SESSION['error'], $_SESSION['success']);
$content = ob_get_clean();
require_once dirname(__DIR__, 2) . '/layouts/teacher.php';
?>