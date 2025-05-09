<?php
declare(strict_types=1);

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";

use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\StudentController;
use App\Controllers\TeacherController;
use App\Controllers\DomainController;
use App\Core\Application;


$app = new Application();

/**
 * Les routes pour la partie authentification.
 */
$app->router->get('', AuthController::class, 'login');
$app->router->post('/authenticate', AuthController::class, 'authenticate');

/**
 * Les routes pour la gestion des users.
 */
$app->router->get('users', UserController::class, 'index');
$app->router->get('users/create', UserController::class, 'create');
$app->router->post('users/store', UserController::class, 'store');
$app->router->get('users/edit/{id}', UserController::class, 'edit');
$app->router->post('users/update', UserController::class, 'update');
$app->router->get('users/destroy/{id}', UserController::class, 'destroy');

/**
 * Les routes pour la gestion des students.
 */
$app->router->get('students', StudentController::class, 'index');
$app->router->get('students/create', StudentController::class, 'create');
$app->router->post('students/store', StudentController::class, 'store');
$app->router->get('students/edit/{id}', StudentController::class, 'edit');
$app->router->post('students/update', StudentController::class, 'update');
$app->router->get('students/destroy/{id}', StudentController::class, 'destroy');
$app->router->post('students/submit-cdc', StudentController::class, 'submitCdc');

/**
 * Les routes pour la gestion des teachers.
 */
$app->router->get('teachers', TeacherController::class, 'index');
$app->router->get('teachers/create', TeacherController::class, 'create');
$app->router->post('teachers/store', TeacherController::class, 'store');
$app->router->get('teachers/edit/{id}', TeacherController::class, 'edit');
$app->router->post('teachers/update', TeacherController::class, 'update');
$app->router->get('teachers/destroy/{id}', TeacherController::class, 'destroy');

/**
 * Les routes pour gerer les domaines d'études.
 */
$app->router->get('domains', DomainController::class, 'index');
$app->router->get('domains/create', DomainController::class, 'create');
$app->router->post('domains/store', DomainController::class, 'store');
$app->router->get('domains/edit/{id}', DomainController::class, 'edit');
$app->router->post('domains/update', DomainController::class, 'update');
$app->router->get('domains/destroy/{id}', DomainController::class, 'destroy');

$app->run();