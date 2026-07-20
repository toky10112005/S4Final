<?php
use CodeIgniter\Router\RouteCollection;

use App\Controllers\EtudiantController;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'UsersController::index');
// $routes->post('/login', 'UsersController::login');
// $routes->get('/inscription', 'UsersController::RedirectInscription');
// $routes->post('/inscription', 'UsersController::inscription');

// $routes->get('/logout', 'UsersController::logout');

// $routes->get('/admindashboard','UsersController::admindashboard', ['filter'=> 'role:admin']);
// $routes->get('/userdashboard','UsersController::userdashboard', ['filter'=> 'role:user']);

$routes->get('/', 'ClientsController::index');

$routes->group('login', function ($routes) {
    $routes->post('client', 'ClientsController::loginClient');
    $routes->get('operateur', 'OperateursController::loginOperateur');
});