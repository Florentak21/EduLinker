<?php $content = ob_start() ?>

<div class="content-container">

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

    <!-- En-tête de la page -->
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-graduation-cap"></i> Détails du projet de soutenance</h2>
            <div class="project-status">
                <?php if ($student['theme_status'] === 'en-traitement'): ?>
                    <span class="status-badge pending">
                        <i class="fas fa-clock"></i> En traitement
                    </span>
                <?php elseif ($student['theme_status'] === 'valide'): ?>
                    <span class="status-badge completed">
                        <i class="fas fa-check-circle"></i> Validé
                    </span>
                <?php else: ?>
                    <span class="status-badge draft">
                        <i class="fas fa-edit"></i> Brouillon
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="dashboard-grid">
        <!-- Informations de l'étudiant -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-user-graduate"></i> Informations de l'étudiant</h3>
            </div>
            <div class="user-profile-section">
                <div class="user-avatar large">
                    <?= strtoupper(substr($student['student_firstname'], 0, 1) . substr($student['student_lastname'], 0, 1)) ?>
                </div>
                <div class="user-details">
                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-id-card"></i> Matricule:</span>
                        <span class="info-value"><?= htmlspecialchars($student['matricule']) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-user"></i> Nom complet:</span>
                        <span class="info-value"><?= htmlspecialchars($student['student_lastname']) ?> <?= htmlspecialchars($student['student_firstname']) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-envelope"></i> Email:</span>
                        <span class="info-value"><?= htmlspecialchars($student['student_email']) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-calendar-plus"></i> Date d'inscription:</span>
                        <span class="info-value"><?= date('d/m/Y à H:i', strtotime(htmlspecialchars($student['created_at']))) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations du binôme -->
        <?php if ($student['has_binome'] && !empty($student['matricule_binome'])): ?>
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-users"></i> Informations du binôme</h3>
                </div>
                <div class="user-profile-section">
                    <div class="user-avatar large">
                        <?= strtoupper(substr($student['binome_firstname'], 0, 1) . substr($student['binome_lastname'], 0, 1)) ?>
                    </div>
                    <div class="user-details">
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-id-card"></i> Matricule:</span>
                            <span class="info-value"><?= htmlspecialchars($student['matricule_binome']) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-user"></i> Nom complet:</span>
                            <span class="info-value"><?= htmlspecialchars($student['binome_lastname']) ?> <?= htmlspecialchars($student['binome_firstname']) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-envelope"></i> Email:</span>
                            <span class="info-value"><?= htmlspecialchars($student['binome_email']) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-calendar-plus"></i> Date d'inscription:</span>
                            <span class="info-value"><?= date('d/m/Y à H:i', strtotime(htmlspecialchars($student['binome_created_at']))) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif ?>
        
        <!-- Informations de l'encadreur -->
        <?php if ($student['teacher_id']): ?>
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-chalkboard-teacher"></i> Informations de l'encadreur</h3>
                </div>
                <div class="user-profile-section">
                    <div class="user-avatar large teacher">
                        <?= strtoupper(substr($student['teacher_firstname'], 0, 1) . substr($student['teacher_lastname'], 0, 1)) ?>
                    </div>
                    <div class="user-details">
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-user"></i> Nom complet:</span>
                            <span class="info-value"><?= htmlspecialchars($student['teacher_lastname']) ?> <?= htmlspecialchars($student['teacher_firstname']) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-envelope"></i> Email:</span>
                            <span class="info-value"><?= htmlspecialchars($student['teacher_email']) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-calendar-check"></i> Date d'affectation:</span>
                            <span class="info-value"><?= date('d/m/Y à H:i', strtotime(htmlspecialchars($student['assigned_at']))) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif ?>
    </div>

    <!-- Informations du thème -->
    <?php if ($student['theme'] && $student['cdc'] && in_array($student['theme_status'], ['en-traitement', 'valide'])): ?>
        <div class="card theme-card">
            <div class="card-header">
                <h3><i class="fas fa-lightbulb"></i> Informations du thème</h3>
            </div>
            <div class="theme-content">
                <div class="theme-item">
                    <h4><i class="fas fa-bookmark"></i> Thème du projet</h4>
                    <p class="theme-title"><?= htmlspecialchars($student['theme']) ?></p>
                </div>
                
                <div class="theme-item">
                    <h4><i class="fas fa-align-left"></i> Description</h4>
                    <p class="theme-description"><?= htmlspecialchars($student['description']) ?></p>
                </div>
                
                <div class="theme-item">
                    <h4><i class="fas fa-file-pdf"></i> Cahier de charge</h4>
                    <div class="document-section">
                        <div class="document-preview">
                            <div class="pdf-preview" id="pdfPreview">
                                <div class="pdf-placeholder">
                                    <i class="fas fa-file-pdf"></i>
                                    <p>Cliquez sur "Voir le PDF" pour prévisualiser le document</p>
                                </div>
                                <iframe id="pdfViewer" src="" style="display: none;" width="100%" height="500" frameborder="0"></iframe>
                            </div>
                        </div>
                        <div class="document-actions">
                            <button onclick="togglePdfViewer('/storage/<?= $student['cdc']?>')" class="btn btn-primary" id="viewPdfBtn">
                                <i class="fas fa-eye"></i> Voir le PDF
                            </button>
                            <a href="/storage/<?= $student['cdc']?>" download="" class="btn btn-secondary">
                                <i class="fas fa-download"></i> Télécharger
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>

    <!-- Actions et navigation -->
    <div class="card actions-card">
        <div class="actions-section">
            <div class="navigation-actions">
                <a href="/admin/students" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour à la liste
                </a>
            </div>

            <!-- Affichage des boutons d'action suivant le status du thème -->
            <?php if($student['theme'] && $student['theme_status'] === 'en-traitement'): ?>
                <div class="theme-actions">
                    <a href="/admin/students/validate-theme-form/<?= $student['id'] ?>" class="btn btn-primary" title="Valider le thème">
                        <i class="fas fa-check"></i> Valider le thème
                    </a>
                    <form action="/admin/students/cancel-theme" method="POST" class="delete-form">
                        <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
                        <button type="submit" title="Rejeter le thème" class="btn btn-danger" 
                            onclick="return confirm('Êtes-vous sûr de vouloir rejeter ce thème ?')">
                            <i class="fas fa-times"></i> Rejeter
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
let isPdfVisible = false;

function togglePdfViewer(pdfUrl) {
    const pdfViewer = document.getElementById('pdfViewer');
    const pdfPlaceholder = document.querySelector('.pdf-placeholder');
    const viewPdfBtn = document.getElementById('viewPdfBtn');
    const pdfPreview = document.getElementById('pdfPreview');
    
    if (!isPdfVisible) {
        pdfPlaceholder.innerHTML = `
            <div class="loading-pdf">
                <i class="fas fa-spinner"></i>
                <p>Chargement du PDF en cours...</p>
            </div>
        `;
        
        pdfViewer.src = pdfUrl;
        
        pdfViewer.onload = function() {
            pdfPlaceholder.style.display = 'none';
            pdfViewer.style.display = 'block';
            pdfViewer.classList.add('show');
            
            viewPdfBtn.innerHTML = '<i class="fas fa-eye-slash"></i> Masquer le PDF';
            viewPdfBtn.classList.remove('btn-primary');
            viewPdfBtn.classList.add('btn-secondary');
            
            isPdfVisible = true;
        };
        
        pdfViewer.onerror = function() {
            pdfPlaceholder.innerHTML = `
                <div style="color: var(--danger-color); text-align: center; padding: 2rem;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                    <p>Impossible de charger le PDF. Le fichier peut être corrompu ou indisponible.</p>
                    <p style="font-size: 0.875rem; margin-top: 1rem;">Vous pouvez essayer de le télécharger directement.</p>
                </div>
            `;
        };
        
    }
}

function adjustPdfViewerHeight() {
    const pdfViewer = document.getElementById('pdfViewer');
    if (pdfViewer && isPdfVisible) {
        const viewportHeight = window.innerHeight;
        const optimalHeight = Math.min(600, viewportHeight * 0.6);
        pdfViewer.style.height = optimalHeight + 'px';
    }
}

window.addEventListener('resize', adjustPdfViewerHeight);
document.addEventListener('DOMContentLoaded', adjustPdfViewerHeight);
</script>

<?php
unset($_SESSION['error'], $_SESSION['success']);
$content = ob_get_clean();
require_once dirname(__DIR__, 2) . '/layouts/admin.php';
?>