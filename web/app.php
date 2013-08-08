<?php

require_once __DIR__.'/../app/bootstrap.php.cache';
require_once __DIR__.'/../app/AppKernel.php';

use Symfony\Component\HttpFoundation\Request;

$env = getenv('APP_ENV');

switch ($env) {

    case 'dev':
        $kernel = new AppKernel('dev', true);
        break;

    case 'prod':
    default:
        $kernel = new AppKernel('prod', false);
        break;
}

$kernel->loadClassCache();

$request = Request::createFromGlobals();

$response = $kernel->handle($request);

$response->send();

$kernel->terminate($request, $response);
