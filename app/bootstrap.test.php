<?php

require_once __DIR__.'/bootstrap.php.cache';
require_once __DIR__.'/../vendor/phpunit/phpunit/PHPUnit/Framework/Assert/Functions.php';
require_once __DIR__.'/AppKernel.php';


/**
 * Class TestCase
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Prophecy\Prophet $prophet
     */
    protected $prophet;

    public function setUp()
    {
        $this->prophet = new \Prophecy\Prophet();
    }

    public function tearDown()
    {
        $this->prophet->checkPredictions();
    }

}
