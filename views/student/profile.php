<?php 
$student = $this->data['student'] ?? [];
$this->layout('layouts/student', [
    'title' => 'Mon profil', 
    'active' => 'profile',
    'student' => $student
]) 
?>

<div class="card">
    <h2>Mon profil</h2>
    
    <div class="profile-grid">
        <div class="profile-section">
            <h3>Informations personnelles</h3>
            <div class="profile-info">
                <div class="info-item">
                    <label>Matricule:</label>
                    <p><?= htmlspecialchars($student['matricule']) ?></p>
                </div>
                <div class="info-item">
                    <label>Nom complet:</label>
                    <p><?= htmlspecialchars($student['firstname'] . ' ' . $student['lastname']) ?></p>
                </div>
                <div class="info-item">
                    <label>Genre:</label>
                    <p><?= htmlspecialchars($student['gender'] === 'M' ? 'Masculin' : 'Féminin') ?></p>
                </div>
                <div class="info-item">
                    <label>Email:</label>
                    <p><?= htmlspecialchars($student['email']) ?></p>
                </div>
                <div class="info-item">
                    <label>Téléphone:</label>
                    <p><?= htmlspecialchars($student['phone']) ?></p>
                </div>
            </div>
        </div>
        
        <div class="profile-section">
            <h3>Informations académiques</h3>
            <div class="profile-info">
                <div class="info-item">
                    <label>Domaine:</label>
                    <p><?= htmlspecialchars($student['domain_label'] ?? 'Non défini') ?></p>
                </div>
                <div class="info-item">
                    <label>Encadreur:</label>
                    <p>
                        <?php if ($student['teacher_name']): ?>
                            <?= htmlspecialchars($student['teacher_name']) ?>
                            <a href="/messages/<?= $student['teacher_id'] ?>" class="btn btn-sm btn-outline">
                                <i class="fas fa-envelope"></i> Contacter
                            </a>
                        <?php else: ?>
                            Non attribué
                        <?php endif; ?>
                    </p>
                </div>
                <div class="info-item">
                    <label>Statut du mémoire:</label>
                    <p>
                        <span class="status-badge <?= strtolower(str_replace('-', '', $student['theme_status'])) ?>">
                            <?= ucfirst($student['theme_status']) ?>
                        </span>
                    </p>
                </div>
                <?php if ($student['has_binome'] && $student['matricule_binome']): ?>
                <div class="info-item">
                    <label>Binôme:</label>
                    <p><?= htmlspecialchars($student['matricule_binome']) ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="form-actions">
        <a href="/student/profile/edit" class="btn btn-primary">
            <i class="fas fa-edit"></i> Modifier mon profil
        </a>
        <a href="/student/change-password" class="btn btn-outline">
            <i class="fas fa-key"></i> Changer mon mot de passe
        </a>
    </div>
</div>