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
    $routes->get('situations', 'OperateurController::situationComptes');
    $routes->get('baremes', 'BaremeFraisController::list_barem');
});

