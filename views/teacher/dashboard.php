<?php
use App\Models\Student;
$content = ob_start();
?>

<div class="dashboard-grid">

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

    <!-- Statistiques -->
    <div class="card stats-card">
        <h2>Mes statistiques</h2>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-value"><?= $total_students ?? 0 ?></div>
                <div class="stat-label">Étudiants encadrés</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $total_students ?? 0 ?></div>
                <div class="stat-label">Mémoires à superviser</div>
            </div>
        </div>
    </div>

    <!-- Derniers étudiants -->
    <div class="card recent-students">
        <h2>Derniers étudiants ajoutés</h2>
        <div class="students-list">
            <?php foreach ($students as $student): ?>
            <div class="student-item">
                <div class="student-avatar">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="student-info">
                    <h3><?= htmlspecialchars($student['firstname'] . ' ' . $student['lastname']) ?></h3>
                    <p>Matricule: <?= htmlspecialchars($student['matricule']) ?></p>
                    <p>Domaine: <?= htmlspecialchars($student['label']) ?></p>
                    <p>Thème: <?= htmlspecialchars($student['theme']) ?></p>
                    <?php if($student['has_binome'] && $student['matricule_binome']):
                        $binome = Student::findByMatricule($student['matricule_binome']);
                        dump($binome);
                        $binome_complete = Student::findByUserId($binome['user_id']);
                    ?>
                        <h3>Binome<?= htmlspecialchars($binome_complete['firstname'] . ' ' . $binome_complete['lastname']) ?></h3>
                        <p>Matricule: <?= htmlspecialchars($binome_complete['matricule']) ?></p>
                        <p>Domaine: <?= htmlspecialchars($binome_complete['domain_label']) ?></p>
                    <?php endif ?>
                </div>
                <div class="student-status <?= strtolower(str_replace('-', '', $student['theme_status'])) ?>">
                    <?= ucfirst($student['theme_status']) ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <a href="/teacher/students" class="btn btn-outline btn-block">
            Voir tous les étudiants
        </a>
    </div>

    <!-- Documents récents -->
    <div class="card documents-card">
        <h2>Derniers documents reçus</h2>
        <div class="documents-list">
            <?php foreach ($students as $student): ?>
            <div class="document-item">
                <div class="document-icon">
                    <i class="fas fa-file-<?= $doc['type'] === 'pdf' ? 'pdf' : 'word' ?>"></i>
                </div>
                <div class="document-info">
                    <h3><?= htmlspecialchars($student['theme']) ?></h3>
                    <p>
                        <?= htmlspecialchars($student['firstname'] . ' ' . $student['lastname']) ?> - 
                        <?= date('d/m/Y', strtotime($student['submitted_at'])) ?>
                    </p>
                </div>
                <a href="/storage/<?= htmlspecialchars($student['cdc']) ?>" download class="file-link">
                    <i class="fas fa-download"></i>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php
unset($_SESSION['error'], $_SESSION['success']);
$content = ob_get_clean();
require_once dirname(__DIR__) . '/layouts/teacher.php';
?>