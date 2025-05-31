<?php
    $content = ob_start();
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'custom-links.php';

?>

<section class="hero">
    <div class="container">
        <h1>Gestion des mémoires de fin d'études</h1>
        <p>La plateforme idéale pour simplifier l'encadrement et le suivi des travaux de recherche</p>
        <a href="<?= $results['url'] ?>" class="btn btn-outline"><?= $results['link'] ?></a>
    </div>
</section>

<section class="features">
    <div class="container">
        <h2>Pourquoi choisir EduLinker ?</h2>
        <div class="feature-grid">
            <div class="feature-card">
                <i class="fas fa-user-graduate"></i>
                <h3>Étudiants</h3>
                <ul>
                    <li>Soumission facile des thèmes</li>
                    <li>Suivi en temps réel</li>
                    <li>Gestion des binômes</li>
                    <li>Upload des documents</li>
                </ul>
            </div>
            <div class="feature-card">
                <i class="fas fa-chalkboard-teacher"></i>
                <h3>Enseignants</h3>
                <ul>
                    <li>Visualisation des étudiants encadrés</li>
                    <li>Évaluation des propositions</li>
                    <li>Outils de suivi</li>
                    <li>Communication simplifiée</li>
                </ul>
            </div>
            <div class="feature-card">
                <i class="fas fa-user-shield"></i>
                <h3>Administrateurs</h3>
                <ul>
                    <li>Gestion centralisée</li>
                    <li>Affectation des encadreurs</li>
                    <li>Tableaux de bord analytiques</li>
                    <li>Configuration flexible</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<?php $content = ob_get_clean(); require_once dirname(__DIR__, 1) . '/layouts/public.php'; ?>