<?php $content = ob_start(); ?>

<div class="card">
    <h2>Soumission du cahier des charges</h2>
    
    <?php if (isset($student['theme_status']) && $student['theme_status'] !== 'non-soumis'): ?>
        <div class="alert info">
            <i class="fas fa-info-circle"></i>
            Vous avez déjà soumis votre CDC. Statut actuel : <?= ucfirst($student['theme_status']) ?>
        </div>
        
        <div class="submission-details">
            <h3>Détails de votre soumission</h3>
            <?php if (isset($student['theme'])): ?>
                <div class="detail-item">
                    <label>Thème :</label>
                    <p><?= htmlspecialchars($student['theme']) ?></p>
                </div>
            <?php endif; ?>
            <?php if (isset($student['cdc'])): ?>
                <div class="detail-item">
                    <label>Fichier CDC :</label>
                    <a href="/storage/<?= htmlspecialchars($student['cdc']) ?>" download class="file-link">
                        <i class="fas fa-file-pdf"></i> <?= htmlspecialchars($student['cdc']) ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <form action="/student/submit-cdc" method="POST" enctype="multipart/form-data" class="cdc-form">
            <div class="form-group">
                <label for="theme">Thème du mémoire *</label>
                <input type="text" id="theme" name="theme" class="form-control <?= isset($errors['theme']) ? 'is-invalid' : '' ?>" 
                       value="<?= htmlspecialchars($data['theme'] ?? '') ?>" required>
                <?php if (isset($errors['theme'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['theme']) ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label>Travaillez-vous en binôme ? *</label>
                <div class="radio-group">
                    <label class="radio-option">
                        <input type="radio" name="has_binome" value="1" <?= ($data['has_binome'] ?? '0') === '1' ? 'checked' : '' ?>>
                        <span>Oui</span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="has_binome" value="0" <?= ($data['has_binome'] ?? '0') === '0' ? 'checked' : '' ?>>
                        <span>Non</span>
                    </label>
                </div>
                <?php if (isset($errors['has_binome'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['has_binome']) ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group binome-field" style="<?= (isset($data['has_binome']) && $data['has_binome'] === '1') ? '' : 'display: none;' ?>">
                <label for="matricule_binome">Matricule du binôme</label>
                <input type="text" id="matricule_binome" name="matricule_binome" class="form-control <?= isset($errors['matricule_binome']) ? 'is-invalid' : '' ?>" 
                       value="<?= htmlspecialchars($data['matricule_binome'] ?? '') ?>">
                <?php if (isset($errors['matricule_binome'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['matricule_binome']) ?></div>
                <?php endif; ?>
                <small class="hint">Format: DOMAINE-ANNEE-XXXX (ex: INFO-2023-1234)</small>
            </div>
            
            <div class="form-group">
                <label for="cdc_file">Fichier CDC *</label>
                <div class="file-upload">
                    <input type="file" id="cdc_file" name="cdc_file" class="<?= isset($errors['cdc_file']) ? 'is-invalid' : '' ?>" required>
                    <label for="cdc_file" class="file-label">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>Choisir un fichier</span>
                    </label>
                    <div class="file-info">Aucun fichier sélectionné</div>
                </div>
                <?php if (isset($errors['cdc_file'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['cdc_file']) ?></div>
                <?php endif; ?>
                <small class="hint">Formats acceptés: PDF (Max 5MB)</small>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> Soumettre
            </button>
        </form>
        
        <script>
            // Afficher/masquer le champ binôme selon le choix
            document.querySelectorAll('input[name="has_binome"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    document.querySelector('.binome-field').style.display = 
                        this.value === '1' ? 'block' : 'none';
                });
            });
            
            // Afficher le nom du fichier sélectionné
            document.getElementById('cdc_file').addEventListener('change', function(e) {
                const fileName = e.target.files[0]?.name || 'Aucun fichier sélectionné';
                document.querySelector('.file-info').textContent = fileName;
            });
        </script>
    <?php endif; ?>
</div>

<?php $content = ob_get_clean(); require_once dirname(__DIR__, 1) . '/layouts/student.php'; ?>