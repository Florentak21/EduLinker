<?php $content = ob_start(); dump($student) ?>

<div class="">
    <h3>Détails du projet de soutenance</h3>

    <div class="">

        <!-- Informations de l'étudiant -->
        <h5>Informations de l'étudiant</h5>
        <div class="">
            <p>Matricule: <?= htmlspecialchars($student['matricule']) ?> </p>
            <p>Nom: <?= htmlspecialchars($student['student_lastname']) ?> </p>
            <p>Prénom: <?= htmlspecialchars($student['student_firstname']) ?> </p>
            <p>Email: <?= htmlspecialchars($student['student_email']) ?> </p>
            <p>Date d'inscription: <?=  date('d/m/Y à H:m:s', strtotime(htmlspecialchars($student['created_at']))) ?></p>
        </div>

        <!-- Informations du binôme -->
        <?php if ($student['has_binome'] && !empty($student['matricule_binome'])): ?>
            <h5>Informations du binôme</h5>
            <div class="">
                <p>Matricule: <?= htmlspecialchars($student['matricule_binome']) ?> </p>
                <p>Nom: <?= htmlspecialchars($student['binome_lastname']) ?> </p>
                <p>Prénom: <?= htmlspecialchars($student['binome_firstname']) ?> </p>
                <p>Email: <?= htmlspecialchars($student['binome_email']) ?> </p>
                <p>Date d'inscription: <?=  date('d/m/Y à H:m:s', strtotime(htmlspecialchars($student['binome_created_at']))) ?></p>
            </div>
        <?php endif ?>
        
        <!-- Informations de l'encadreur -->
        <?php if ($student['teacher_id']): ?>
            <h5>Informations de l'encadreur</h5>
            <div class="">
                <p>Nom: <?= htmlspecialchars($student['teacher_lastname']) ?> </p>
                <p>Prénom: <?= htmlspecialchars($student['teacher_firstname']) ?> </p>
                <p>Email: <?= htmlspecialchars($student['teacher_email']) ?> </p>
                <p>Date d'affectation: <?=  date('d/m/Y à H:m:s', strtotime(htmlspecialchars($student['assigned_at']))) ?></p>
            </div>
        <?php endif ?>
    </div>

    <!-- Informations du thème même -->
     <?php if ($student['theme'] && $student['cdc'] && in_array($student['theme_status'], ['en-traitement', 'valide']) ): ?>
        <h5>Informations du thème</h5>
        <div class="">
            <p>
                Thème: <?= $student['theme'] ?>
            </p>
            <p>
                Description: <?= $student['description'] ?>
            </p>
            <p>
                Cahier de charge: <br/>
                <a href="/storage/<?= $student['cdc']?>.pdf" download="">Voir le cahier de charge</a>
                <?php //dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . $student['cdc'] . '.pdf';?>
            </p>
        </div>
    <?php endif ?>

    <a href="/admin/students">Retour</a>

    <!-- Affichage des boutons d'action suivant le status du thème -->
     <?php if($student['theme'] && $student['theme_status'] === 'en-traitement'): ?>
        <div class="">
            <a href="/admin/students/validate-theme-form/<?= $student['id'] ?>" class="btn btn-success" title="Valider">
                <i class="fas fa-check"></i> Valider
            </a>
            <form action="/admin/students/cancel-theme" method="POST">
                <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
                <button type="submit" title="Rejeter" class="btn btn-sm btn-danger" 
                    onclick="return confirm('Êtes-vous sûr de vouloir rejeter ce thème ?')">
                    Rejeter
                </button>
            </form>
        </div>
    <?php endif; ?>
</div>




<?php
$content = ob_get_clean();
require_once dirname(__DIR__, 2) . '/layouts/admin.php';
?>