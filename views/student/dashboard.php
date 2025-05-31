<?php $content = ob_start(); ?>

<div class="dashboard-grid">
    <!-- Statut du mémoire -->
    <div class="card status-card">
        <h2>Statut de mon mémoire</h2>
        <div class="status-indicator <?= strtolower(str_replace('-', '', $student['theme_status'] ?? 'non-soumis')) ?>">
            <span><?= ucfirst($student['theme_status'] ?? 'Non soumis') ?></span>
        </div>

        <?php if ($student['theme_status'] === 'non-soumis'): ?>
            <a href="/student/submit-cdc" class="btn btn-primary">
                <i class="fas fa-file-upload"></i> Soumettre mon CDC
            </a>
        <?php elseif ($student['theme_status'] === 'en-traitement' && $student['submitted_at']): ?>
            <div class="submission-info">
                <p><strong>Date de soumission :</strong> <?= htmlspecialchars($student['submitted_at']) ?></p>
                <?php if ($student['last_reminder_at']): ?>
                    <p><strong>Dernière relance :</strong> <?= htmlspecialchars($student['last_reminder_at']) ?></p>
                <?php endif; ?>

                <!-- Vérifier si une relance est possible -->
                <?php 
                    $submittedDate = new DateTime($student['submitted_at']);
                    $now = new DateTime();
                    $interval = $submittedDate->diff($now);
                    $canRemind = $interval->days >= 7;
                ?>
                <?php if ($canRemind): ?>
                    <form action="/student/remind" method="POST">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-bell"></i> Relancer
                        </button>
                    </form>
                <?php else: ?>
                    <div class="alert info">
                        <i class="fas fa-clock"></i>
                        Vous pourrez relancer après 7 jours (temps restant : <?= 7 - $interval->days ?> jour<?= (7 - $interval->days) > 1 ? 's' : '' ?>).
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Encadreur -->
    <div class="card teacher-card">
        <h2>Mon encadreur</h2>
        <?php if (isset($student['teacher_id']) && $student['theme_status'] === 'valide'): ?>
            <div class="teacher-info">
                <div class="avatar">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="details">
                    <h3><?= htmlspecialchars($student['teacher_name'] ?? 'N/A') ?></h3>
                    <p><?= htmlspecialchars($student['teacher_email'] ?? 'N/A') ?></p>
                </div>
            </div>
            <a href="mailto:<?= htmlspecialchars($student['teacher_email'] ?? '') ?>" class="btn btn-outline">
                <i class="fas fa-envelope"></i> Contacter
            </a>
        <?php else: ?>
            <div class="no-teacher">
                <i class="fas fa-user-times"></i>
                <p>Aucun encadreur attribué pour le moment</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Détails du mémoire -->
    <div class="card thesis-card">
        <h2>Détails de mon mémoire</h2>
        <div class="thesis-details">
            <?php if (!empty($student['theme'])): ?>
                <div class="detail-item">
                    <label>Thème :</label>
                    <p><?= htmlspecialchars($student['theme']) ?></p>
                </div>
                
                <?php if (isset($student['has_binome']) && $student['has_binome'] && isset($student['matricule_binome'])): ?>
                <div class="detail-item">
                    <label>Binôme :</label>
                    <p><?= htmlspecialchars($student['binome_name'] ?? $student['matricule_binome']) ?></p>
                </div>
                <?php endif; ?>
                
                <?php if (isset($student['cdc'])): ?>
                <div class="detail-item">
                    <label>CDC :</label>
                    <a href="/storage/<?= htmlspecialchars($student['cdc']) ?>" download class="file-link">
                        <i class="fas fa-file-pdf"></i> Télécharger
                    </a>
                </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="no-thesis">
                    <i class="fas fa-book"></i>
                    <p>Aucun thème soumis pour le moment</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); require_once dirname(__DIR__, 1) . '/layouts/student.php'; ?>