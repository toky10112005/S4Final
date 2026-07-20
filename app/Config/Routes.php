<?php
use CodeIgniter\Router\RouteCollection;

use App\Controllers\EtudiantController;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'ClientsController::index');

$routes->group('login', function ($routes) {
    $routes->post('client', 'ClientsController::index');
    $routes->get('operateur', 'OperateurController::index');
    $routes->post('operateur', 'OperateurController::loginOperateur');
});

$routes->group('operateur', ['filter' => 'role:operateur'], function ($routes) {
    $routes->get('prefixes', 'PrefixesController::list_prefixes');
    $routes->get('/prefixes/supprimer/(:num)', 'PrefixesController::supprimer/$1');
    $routes->get('/prefixes/ajouter', 'PrefixesController::ajouter');
    $routes->get('situations', 'OperateurController::situationComptes');
    $routes->get('baremes', 'BaremeFraisController::list_barem');
});

    $routes->post('client', 'ClientsController::loginClient');
    $routes->get('operateur', 'OperateursController::loginOperateur');
});

// Client area
$routes->group('client', function($routes){
    $routes->get('dashboard', 'ClientsController::dashboard');
    $routes->post('deposit', 'ClientsController::deposit');
    $routes->post('withdraw', 'ClientsController::withdraw');
    $routes->post('transfer', 'ClientsController::transfer');
    $routes->get('logout', 'ClientsController::logout');
});
