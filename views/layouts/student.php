<?php
    use App\Models\Student;
    $student = Student::findByUserId($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Étudiant - <?= htmlspecialchars($title ?? 'EduLinker') ?></title>
    <link rel="stylesheet" href="/css/student.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="student-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="avatar">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h3><?= htmlspecialchars(strtolower('@' . $student['student_firstname']) ?? 'Étudiant') ?></h3>
                <p>Matricule: <?= htmlspecialchars($student['matricule'] ?? '') ?></p>
            </div>
            <nav class="sidebar-nav">
                <a href="/student/dashboard" class="<?= $active === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="/profile-show" class="<?= $active === 'profile' ? 'active' : '' ?>">
                    <i class="fas fa-user"></i> Mon profil
                </a>
                <a href="/student/submit-cdc" class="<?= $active === 'cdc' ? 'active' : '' ?>">
                    <i class="fas fa-file-upload"></i> Soumettre mon CDC
                </a>
                <a href="/logout" class="logout">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="main-header">
                <h1><?= htmlspecialchars($title ?? 'Tableau de bord') ?></h1>
                <div class="notifications">
                    <i class="fas fa-bell"></i>
                    <span class="badge">3</span>
                </div>
            </header>

            <div class="content-container">
                <?php if (isset($success)): ?>
                    <div class="alert success">
                        <i class="fas fa-check-circle"></i>
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <?= $content ?>
            </div>
        </main>
    </div>
</body>
</html>