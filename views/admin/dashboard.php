<?php $content = ob_start(); ?>

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
        <h2>Statistiques globales</h2>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-value"><?= $data['users'] ?? 0 ?></div>
                <div class="stat-label">Utilisateurs</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $data['students'] ?? 0 ?></div>
                <div class="stat-label">Étudiants</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $data['teachers'] ?? 0 ?></div>
                <div class="stat-label">Enseignants</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $data['themes'] ?? 0 ?></div>
                <div class="stat-label">Thèmes</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $data['pending_themes'] ?? 0 ?></div>
                <div class="stat-label">Thèmes en attente de validation</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $data['validated_themes'] ?? 0 ?></div>
                <div class="stat-label">Thème validés</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $data['refused_themes'] ?? 0 ?></div>
                <div class="stat-label">Thèmes rejetés</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $data['domains']['domains_count'] ?? 0 ?></div>
                <div class="stat-label">Domaines</div>
            </div>
        </div>
    </div>

    <!-- Dernières affectations -->
    <div class="card assignments-card">
        <h2>Dernières affectations</h2>
        <div class="assignments-list">
            <?php foreach ($data['recent_assignments'] as $assignment): ?>
            <div class="assignment-item">
                <div class="assignment-details">
                    <div class="student-info">
                        <div class="avatar small">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <h3><?= htmlspecialchars($assignment['student_firstname'] . ' '. $assignment['student_lastname']) ?></h3>
                    </div>
                    <div class="teacher-info">
                        <div class="avatar small">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h3><?= htmlspecialchars($assignment['teacher_firstname'] . ' '. $assignment['teacher_lastname']) ?></h3>
                    </div>
                </div>
                <div class="assignment-meta">
                    <span><?= date('d/m/Y', strtotime($assignment['assigned_at'])) ?></span>
                    <span class="status <?= strtolower($assignment['theme_status']) ?>">
                        <?= ucfirst($assignment['theme_status']) ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <a href="/admin/students" class="btn btn-outline btn-block">
            Voir toutes les affectations
        </a>
    </div>

    <!-- Domaines actifs -->
    <div class="card domains-card">
        <h2>Domaines les plus actifs</h2>
        <div class="domains-list">
            <?php foreach ($data['domains']['domains_items'] as $domain): ?>
            <div class="domain-item">
                <div class="domain-info">
                    <h3><?= htmlspecialchars($domain['label']) ?></h3>
                    <p><?= htmlspecialchars($domain['code']) ?></p>
                </div>
                <div class="domain-stats">
                    <div class="stat">
                        <span class="value"><?= $domain['student_count'] ?></span>
                        <span class="label">Étudiants</span>
                    </div>
                    <div class="stat">
                        <span class="value"><?= $domain['teacher_count'] ?></span>
                        <span class="label">Enseignants</span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php
unset($_SESSION['error'], $_SESSION['success']);
$content = ob_get_clean();
require_once dirname(__DIR__) . '/layouts/admin.php';
?>