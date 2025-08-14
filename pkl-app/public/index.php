<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

start_session_if_needed();

$route = $_GET['route'] ?? '';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

function controller(string $name) {
    $file = __DIR__ . '/../controllers/' . $name . 'Controller.php';
    if (!file_exists($file)) {
        http_response_code(404);
        echo 'Controller not found';
        exit;
    }
    require_once $file;
    $class = $name . 'Controller';
    return new $class();
}

if ($route === '' || $route === 'dashboard') {
    require_login();
    controller('Dashboard')->index();
    exit;
}

switch ($route) {
    case 'login':
        if ($method === 'POST') {
            controller('Auth')->loginPost();
        } else {
            controller('Auth')->login();
        }
        break;
    case 'logout':
        controller('Auth')->logout();
        break;

    case 'monitoring':
        require_login();
        if ($method === 'POST') {
            controller('Monitoring')->store();
        } else {
            controller('Monitoring')->index();
        }
        break;
    case 'monitoring/create':
        require_login();
        controller('Monitoring')->create();
        break;
    case 'monitoring/review':
        require_login();
        controller('Monitoring')->review();
        break;
    case 'monitoring/approve':
        require_login();
        controller('Monitoring')->approve();
        break;
    case 'monitoring/reject':
        require_login();
        controller('Monitoring')->reject();
        break;

    case 'reports':
        require_login();
        if ($method === 'POST') {
            controller('Report')->store();
        } else {
            controller('Report')->index();
        }
        break;
    case 'reports/create':
        require_login();
        controller('Report')->create();
        break;
    case 'reports/review':
        require_login();
        controller('Report')->review();
        break;
    case 'reports/approve':
        require_login();
        controller('Report')->approve();
        break;
    case 'reports/reject':
        require_login();
        controller('Report')->reject();
        break;

    case 'admin/users':
        require_role(['admin']);
        controller('Admin')->users();
        break;
    case 'admin/users/create':
        require_role(['admin']);
        controller('Admin')->usersCreate();
        break;
    case 'admin/users/store':
        require_role(['admin']);
        controller('Admin')->usersStore();
        break;
    case 'admin/companies':
        require_role(['admin']);
        controller('Admin')->companies();
        break;
    case 'admin/companies/store':
        require_role(['admin']);
        controller('Admin')->companiesStore();
        break;
    case 'admin/assignments':
        require_role(['admin']);
        controller('Admin')->assignments();
        break;
    case 'admin/assignments/store':
        require_role(['admin']);
        controller('Admin')->assignmentsStore();
        break;

    case 'install':
        controller('Auth')->install();
        break;

    default:
        http_response_code(404);
        echo 'Not Found';
}