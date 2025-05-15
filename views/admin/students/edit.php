<?php $content = ob_start();?>

<div class="card">

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

    <form action="/admin/students/update" method="POST" class="form">
        <input type="hidden" name="id" value="<?= $student['id'] ?>">
        
        <div class="form-row">
            <div class="form-group">
                <label for="firstname">Prénom *</label>
                <input type="text" id="firstname" name="firstname" class="form-control <?= isset($errors['firstname']) ? 'is-invalid' : '' ?>" 
                       value="<?= htmlspecialchars($data['firstname'] ?? $student['student_firstname']) ?>" required>
                <?php if (isset($errors['firstname'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['firstname']) ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="lastname">Nom *</label>
                <input type="text" id="lastname" name="lastname" class="form-control <?= isset($errors['lastname']) ? 'is-invalid' : '' ?>" 
                       value="<?= htmlspecialchars($data['lastname'] ?? $student['student_lastname']) ?>" required>
                <?php if (isset($errors['lastname'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['lastname']) ?></div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                       value="<?= htmlspecialchars($data['email'] ?? $student['student_email']) ?>" required>
                <?php if (isset($errors['email'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['email']) ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="gender">Genre *</label>
                <select id="gender" name="gender" class="form-control <?= isset($errors['gender']) ? 'is-invalid' : '' ?>" required>
                    <option value="">Sélectionner...</option>
                    <option value="M" <?= ($data['gender'] ?? $student['student_gender']) === 'M' ? 'selected' : '' ?>>Masculin</option>
                    <option value="F" <?= ($data['gender'] ?? $student['student_gender']) === 'F' ? 'selected' : '' ?>>Féminin</option>
                </select>
                <?php if (isset($errors['gender'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['gender']) ?></div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="form-row">
            <?php if (isset($student['teacher_id'])): ?>
                <div class="form-group">
                    <label for="teacher_id">Encadreur</label>
                    <select id="teacher_id" name="teacher_id" class="form-control <?= isset($errors['teacher_id']) ? 'is-invalid' : '' ?>">
                        <option value="" disabled>Non attribué</option>
                        <?php foreach ($teachers as $teacher): ?>
                            <option value="<?= $teacher['id'] ?>" <?= ($data['teacher_id'] ?? $student['teacher_id']) == $teacher['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($teacher['firstname'] . ' ' . $teacher['lastname']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['teacher_id'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['teacher_id']) ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="domain_id">Domaine *</label>
                <select id="domain_id" name="domain_id" class="form-control <?= isset($errors['domain_id']) ? 'is-invalid' : '' ?>" required>
                    <option value="">Sélectionner un domaine...</option>
                    <?php foreach ($domains as $domain): ?>
                        <option value="<?= $domain['id'] ?>" <?= ($data['domain_id'] ?? $student['domain_id']) == $domain['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($domain['label']) ?> (<?= htmlspecialchars($domain['code']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['domain_id'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['domain_id']) ?></div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="form-actions">
            <a href="/admin/students" class="btn btn-outline">Annuler</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Enregistrer
            </button>
        </div>
    </form>
</div>

<?php
unset($_SESSION['error'], $_SESSION['success']);
$content = ob_get_clean();
require_once dirname(__DIR__, 2) . '/layouts/admin.php';
?>