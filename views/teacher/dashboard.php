<?php 
$teacher = $this->data['teacher'] ?? [];
$this->layout('layouts/teacher', [
    'title' => 'Tableau de bord', 
    'active' => 'dashboard',
    'teacher' => $teacher
]) 
?>

<div class="dashboard-grid">
    <!-- Statistiques -->
    <div class="card stats-card">
        <h2>Mes statistiques</h2>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-value"><?= $stats['total_students'] ?? 0 ?></div>
                <div class="stat-label">Étudiants encadrés</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $stats['pending_cdc'] ?? 0 ?></div>
                <div class="stat-label">CDC à valider</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $stats['completed'] ?? 0 ?></div>
                <div class="stat-label">Mémoires terminés</div>
            </div>
        </div>
    </div>

    <!-- Derniers étudiants -->
    <div class="card recent-students">
        <h2>Derniers étudiants ajoutés</h2>
        <div class="students-list">
            <?php foreach ($recentStudents as $student): ?>
            <div class="student-item">
                <div class="student-avatar">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="student-info">
                    <h3><?= htmlspecialchars($student['firstname'] . ' ' . $student['lastname']) ?></h3>
                    <p>Matricule: <?= htmlspecialchars($student['matricule']) ?></p>
                    <p>Thème: <?= htmlspecialchars($student['theme'] ?? 'Non défini') ?></p>
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

    <!-- Calendrier -->
    <div class="card calendar-card">
        <h2>Prochains rendez-vous</h2>
        <div class="calendar">
            <?php foreach ($upcomingMeetings as $meeting): ?>
            <div class="meeting-item">
                <div class="meeting-date">
                    <div class="meeting-day"><?= date('d', strtotime($meeting['date'])) ?></div>
                    <div class="meeting-month"><?= date('M', strtotime($meeting['date'])) ?></div>
                </div>
                <div class="meeting-details">
                    <h3><?= htmlspecialchars($meeting['title']) ?></h3>
                    <p>
                        <i class="fas fa-clock"></i> 
                        <?= date('H:i', strtotime($meeting['date'])) ?> - 
                        <?= htmlspecialchars($meeting['student_name']) ?>
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <button class="btn btn-primary btn-block">
            <i class="fas fa-plus"></i> Ajouter un rendez-vous
        </button>
    </div>

    <!-- Documents récents -->
    <div class="card documents-card">
        <h2>Derniers documents reçus</h2>
        <div class="documents-list">
            <?php foreach ($recentDocuments as $doc): ?>
            <div class="document-item">
                <div class="document-icon">
                    <i class="fas fa-file-<?= $doc['type'] === 'pdf' ? 'pdf' : 'word' ?>"></i>
                </div>
                <div class="document-info">
                    <h3><?= htmlspecialchars($doc['title']) ?></h3>
                    <p>
                        <?= htmlspecialchars($doc['student_name']) ?> - 
                        <?= date('d/m/Y', strtotime($doc['uploaded_at'])) ?>
                    </p>
                </div>
                <a href="/download/<?= $doc['id'] ?>" class="document-download">
                    <i class="fas fa-download"></i>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>