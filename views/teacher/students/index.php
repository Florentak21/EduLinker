<?php 
$teacher = $this->data['teacher'] ?? [];
$this->layout('layouts/teacher', [
    'title' => 'Mes étudiants', 
    'active' => 'students',
    'teacher' => $teacher
]) 
?>

<div class="card">
    <div class="card-header">
        <h2>Liste de mes étudiants encadrés</h2>
        <div class="card-actions">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Rechercher un étudiant...">
            </div>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Étudiant</th>
                    <th>Matricule</th>
                    <th>Thème</th>
                    <th>Statut</th>
                    <th>Dernière activité</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                <tr>
                    <td>
                        <div class="student-cell">
                            <div class="student-avatar">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="student-info">
                                <h3><?= htmlspecialchars($student['firstname'] . ' ' . $student['lastname']) ?></h3>
                                <p><?= htmlspecialchars($student['email']) ?></p>
                            </div>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($student['matricule']) ?></td>
                    <td><?= htmlspecialchars($student['theme'] ?? 'Non défini') ?></td>
                    <td>
                        <span class="status-badge <?= strtolower(str_replace('-', '', $student['theme_status'])) ?>">
                            <?= ucfirst($student['theme_status']) ?>
                        </span>
                    </td>
                    <td><?= date('d/m/Y', strtotime($student['updated_at'])) ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="/student/details/<?= $student['id'] ?>" class="btn btn-sm btn-outline" title="Voir détails">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="/messages/<?= $student['user_id'] ?>" class="btn btn-sm btn-outline" title="Envoyer message">
                                <i class="fas fa-envelope"></i>
                            </a>
                            <a href="/meetings/schedule/<?= $student['id'] ?>" class="btn btn-sm btn-primary" title="Planifier RDV">
                                <i class="fas fa-calendar-plus"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="card-footer">
        <div class="pagination">
            <a href="#" class="page-item disabled"><i class="fas fa-chevron-left"></i></a>
            <a href="#" class="page-item active">1</a>
            <a href="#" class="page-item">2</a>
            <a href="#" class="page-item">3</a>
            <a href="#" class="page-item"><i class="fas fa-chevron-right"></i></a>
        </div>
    </div>
</div>