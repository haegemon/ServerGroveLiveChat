<?php

use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;

$loader = include __DIR__.'/../vendor/autoload.php';

// intl
if (!function_exists('intl_get_error_code')) {
    require_once __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs/functions.php';

    $loader->add('', __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs');
}

AnnotationDriver::registerAnnotationClasses();

return $loader;