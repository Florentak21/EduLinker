<?php

use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\StudentController;
use App\Controllers\TeacherController;
use App\Controllers\DomainController;
use App\Controllers\AdminController;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;
use App\Middleware\SelfAccessMiddleware;
use App\Core\Application;


$app = new Application();

/**
 * Routes pour l'authentification.
 * Permet de créer un compte, se connecter ou se déconnecter.
 */
$app->router->get('register', UserController::class, 'create');
$app->router->post('register/store', UserController::class, 'store');
$app->router->get('/login', AuthController::class, 'login');
$app->router->post('/authenticate', AuthController::class, 'authenticate');
$app->router->get('/logout', AuthController::class, 'logout', [new AuthMiddleware()]);

/**
 * Routes pour la partie admin.
 * Accessibles uniquement aux admins.
 */
$app->router->get('admin', AdminController::class, 'index', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->get('admin/users', UserController::class, 'index', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->get('admin/users/destroy/{id}', UserController::class, 'destroy', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);

$app->router->get('admin/students', StudentController::class, 'index', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->get('admin/students/destroy/{id}', StudentController::class, 'destroy', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);

$app->router->get('admin/teachers', TeacherController::class, 'index', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->get('admin/teachers/create', TeacherController::class, 'create', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->post('admin/teachers/store', TeacherController::class, 'store', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->get('admin/teachers/destroy/{id}', TeacherController::class, 'destroy', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);

$app->router->get('admin/domains', DomainController::class, 'index', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->get('admin/domains/create', DomainController::class, 'create', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->post('admin/domains/store', DomainController::class, 'store', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->get('admin/domains/edit/{id}', DomainController::class, 'edit', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->post('admin/domains/update', DomainController::class, 'update', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->get('admin/domains/destroy/{id}', DomainController::class, 'destroy', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);

$app->router->get('admin/pending-themes', AdminController::class, 'pendingThemes', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->get('admin/validate-theme/{studentId}', AdminController::class, 'validateTheme', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->post('admin/validate-theme/{studentId}', AdminController::class, 'validateTheme', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->get('admin/cancel-theme/{studentId}', AdminController::class, 'cancelTheme', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->get('admin/assign-supervisor/{studentId}', AdminController::class, 'assignSupervisor', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->post('admin/assign-supervisor/{studentId}', AdminController::class, 'assignSupervisor', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);

/**
 * Routes pour les utilisateurs (admin, teachers, students).
 * Permettent de modifier leurs propres infos.
 */
$app->router->get('users/edit/{id}', UserController::class, 'edit', [new AuthMiddleware(), new SelfAccessMiddleware()]);
$app->router->post('users/update', UserController::class, 'update', [new AuthMiddleware()]);

/**
 * Routes pour les teachers.
 * Permettent aux teachers de modifier leurs propres infos.
 */
$app->router->get('teachers/edit/{id}', TeacherController::class, 'edit', [new AuthMiddleware(), new SelfAccessMiddleware(), new RoleMiddleware(['teacher'])]);
$app->router->post('teachers/update', TeacherController::class, 'update', [new AuthMiddleware(), , new RoleMiddleware(['teacher'])]);
$app->router->post('teachers/dashboard/{id}', TeacherController::class, 'dashboard', [new AuthMiddleware(), new SelfAccessMiddleware(), new RoleMiddleware(['teacher'])]);

/**
 * Routes pour les students.
 * Permettent de créer un compte, modifier leurs infos, soumettre un CDC, relancer, et voir leur superviseur.
 */
$app->router->get('students/dashboard/{id}', StudentController::class, 'dashboard', [new AuthMiddleware(), new RoleMiddleware(['student']), new SelfAccessMiddleware()]);
$app->router->get('students/edit/{id}', StudentController::class, 'edit', [new AuthMiddleware(), new RoleMiddleware(['student']), new SelfAccessMiddleware()]);
$app->router->post('students/update', StudentController::class, 'update', [new AuthMiddleware(), new RoleMiddleware(['student'])]);
$app->router->get('students/submit-cdc/{id}', StudentController::class, 'submitCdc', [new AuthMiddleware(), new RoleMiddleware(['student']), new SelfAccessMiddleware()]);
$app->router->post('students/submit-cdc/{id}', StudentController::class, 'submitCdc', [new AuthMiddleware(), new RoleMiddleware(['student']), new SelfAccessMiddleware()]);
$app->router->get('students/remind/{id}', StudentController::class, 'remind', [new AuthMiddleware(), new RoleMiddleware(['student']), new SelfAccessMiddleware()]);
$app->router->get('students/check-supervisor/{id}', StudentController::class, 'checkSupervisor', [new AuthMiddleware(), new RoleMiddleware(['student']), new SelfAccessMiddleware()]);

$app->run();