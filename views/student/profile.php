<?php $content = ob_start(); ?>

<div class="profile-card card">
    <h2 class="profile-title">Mon profil</h2>
    
    <div class="profile-grid">
        <div class="profile-section-container profile-section">
            <h3 class="profile-section-title">Informations personnelles</h3>
            <div class="profile-info">
                <div class="profile-info-item info-item">
                    <label class="profile-info-label">Matricule :</label>
                    <p class="profile-info-value"><?= htmlspecialchars($student['matricule']) ?></p>
                </div>
                <div class="profile-info-item info-item">
                    <label class="profile-info-label">Nom complet :</label>
                    <p class="profile-info-value"><?= htmlspecialchars($student['firstname'] . ' ' . $student['lastname']) ?></p>
                </div>
                <div class="profile-info-item info-item">
                    <label class="profile-info-label">Genre :</label>
                    <p class="profile-info-value"><?= htmlspecialchars($student['gender'] === 'M' ? 'Masculin' : 'Féminin') ?></p>
                </div>
                <div class="profile-info-item info-item">
                    <label class="profile-info-label">Email :</label>
                    <p class="profile-info-value"><?= htmlspecialchars($student['email']) ?></p>
                </div>
                <div class="profile-info-item info-item">
                    <label class="profile-info-label">Téléphone :</label>
                    <p class="profile-info-value"><?= htmlspecialchars($student['phone']) ?></p>
                </div>
            </div>
        </div>
        
        <div class="profile-section-container profile-section">
            <h3 class="profile-section-title">Informations académiques</h3>
            <div class="profile-info">
                <div class="profile-info-item info-item">
                    <label class="profile-info-label">Domaine :</label>
                    <p class="profile-info-value"><?= htmlspecialchars($student['domain_label'] ?? 'Non défini') ?></p>
                </div>
                <div class="profile-info-item info-item">
                    <label class="profile-info-label">Encadreur :</label>
                    <p class="profile-info-value">
                        <?php if ($student['teacher_name']): ?>
                            <?= htmlspecialchars($student['teacher_name']) ?>
                            <a href="/messages/<?= $student['teacher_id'] ?>" class="profile-btn btn-sm profile-btn-sm">
                                <i class="fas fa-envelope"></i> Contacter
                            </a>
                        <?php else: ?>
                            Non attribué
                        <?php endif; ?>
                    </p>
                </div>
                <div class="profile-info-item info-item">
                    <label class="profile-info-label">Statut du mémoire :</label>
                    <p class="profile-info-value">
                        <span class="profile-status-badge <?= strtolower(str_replace('-', '', $student['theme_status'])) ?>">
                            <?= ucfirst($student['theme_status']) ?>
                        </span>
                    </p>
                </div>
                <?php if ($student['has_binome'] && $student['matricule_binome']): ?>
                <div class="profile-info-item info-item">
                    <label class="profile-info-label">Binôme :</label>
                    <p class="profile-info-value"><?= htmlspecialchars($student['matricule_binome']) ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="profile-actions form-actions">
        <a href="/student/profile/edit" class="profile-btn btn-primary">
            <i class="fas fa-edit"></i> Modifier mon profil
        </a>
        <a href="/student/change-password" class="profile-btn profile-btn-outline">
            <i class="fas fa-key"></i> Changer mon mot de passe
        </a>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require_once dirname(__DIR__, 1) . '/layouts/student.php'; ?>