<?php

use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\StudentController;
use App\Controllers\TeacherController;
use App\Controllers\DomainController;
use App\Controllers\AdminController;
use App\Controllers\HomeController;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;
use App\Core\Application;

$app = new Application();

/**
 * Route accessibles à tous (suivant qu'on est connecté ou non).
 */

 /* Route pour la page d'acceuil */
$app->router->get('/', HomeController::class, 'index');

/* Routes pour l'authentification */
$app->router->get('register', AuthController::class, 'register');
$app->router->post('register/store', AuthController::class, 'store');
$app->router->get('login', AuthController::class, 'login');
$app->router->post('authenticate', AuthController::class, 'authenticate');
$app->router->get('logout', AuthController::class, 'logout', [new AuthMiddleware()]);

/* Routes pour la gestion du profile */
$app->router->get('profile-show', AuthController::class, 'show', [new AuthMiddleware()]);
$app->router->get('profile-update', AuthController::class, 'profile', [new AuthMiddleware()]);
$app->router->post('profile/update/infos', AuthController::class, 'updateProfile', [new AuthMiddleware()]);
$app->router->get('profile-password', AuthController::class, 'changePassword', [new AuthMiddleware()]);
$app->router->post('profile/update/password', AuthController::class, 'updatePassword', [new AuthMiddleware()]);

/**
 * Routes pour la partie admin.
 * Accessibles uniquement aux admins.
 */

 /* Route pour le dashboard de l'admin */
$app->router->get('admin/dashboard', AdminController::class, 'index', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);

/* Routes permettant à l'admin de gérer les users */
$app->router->get('admin/users', UserController::class, 'index', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->get('admin/users/create', UserController::class, 'create', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->post('admin/users/store', UserController::class, 'store', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->get('admin/users/edit/{id}', UserController::class, 'edit', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->post('admin/users/update', UserController::class, 'update', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->get('admin/users/destroy/{id}', UserController::class, 'destroy', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);

/* Routes permettant à l'admin de gérer les students */
$app->router->get('admin/students/edit/{id}', StudentController::class, 'edit', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->post('admin/students/update', StudentController::class, 'update', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->get('admin/students', StudentController::class, 'index', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->get('admin/students/destroy/{id}', StudentController::class, 'destroy', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);

/* Routes permettant à l'admin de gérer les thèmes des students */
$app->router->get('admin/validate-theme/{studentId}', AdminController::class, 'validateTheme', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->post('admin/validate-theme/{studentId}', AdminController::class, 'validateTheme', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->get('admin/cancel-theme/{studentId}', AdminController::class, 'cancelTheme', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->post('admin/cancel-theme/{studentId}', AdminController::class, 'cancelTheme', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);

/* Routes permettant à l'admin de gérer les teachers */
$app->router->get('admin/teachers', TeacherController::class, 'index', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->get('admin/teachers/edit/{id}', TeacherController::class, 'edit', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->post('admin/teachers/update', TeacherController::class, 'update', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->post('admin/teachers/store', TeacherController::class, 'store', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->get('admin/teachers/destroy/{id}', TeacherController::class, 'destroy', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);

/* Routes permettant à l'admin de gérer les domaines */
$app->router->get('admin/domains', DomainController::class, 'index', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->get('admin/domains/create', DomainController::class, 'create', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->post('admin/domains/store', DomainController::class, 'store', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->get('admin/domains/edit/{id}', DomainController::class, 'edit', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->post('admin/domains/update', DomainController::class, 'update', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);
$app->router->get('admin/domains/destroy/{id}', DomainController::class, 'destroy', [new AuthMiddleware(), new RoleMiddleware(['admin'])]);

/**
 * Route pour les teachers.
 * Accessibles uniquement aux teachers.
 * Permet aux teachers de consulter leur dashboard.
 */

$app->router->post('teacher/dashboard/', TeacherController::class, 'dashboard', [new AuthMiddleware(), new RoleMiddleware(['teacher'])]);

/**
 * Routes pour les students.
 * Accessibles uniquement aux students.
 * Permettent soumettre un CDC, relancer, et voir leur superviseur.
 */
$app->router->get('/student/dashboard', StudentController::class, 'dashboard', [new AuthMiddleware(), new RoleMiddleware(['student'])]);
$app->router->get('student/submit-cdc', StudentController::class, 'submitCdc', [new AuthMiddleware(), new RoleMiddleware(['student'])]);
$app->router->post('student/submit-cdc', StudentController::class, 'submitCdc', [new AuthMiddleware(), new RoleMiddleware(['student'])]);
$app->router->post('student/remind', StudentController::class, 'remind', [new AuthMiddleware(), new RoleMiddleware(['student'])]);

$app->run();