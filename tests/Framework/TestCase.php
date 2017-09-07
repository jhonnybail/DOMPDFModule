<?php

/**
 * Created by PhpStorm.
 * User: ismail
 * Date: 7-9-17
 * Time: 11:27
 */

namespace DOMPDFModuleTest\Framework;

use Zend\ServiceManager\ServiceManager;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ServiceManager
     */
    protected static $serviceManager;
    /**
     * @param ServiceManager $serviceManager
     */
    public static function setServiceManager(ServiceManager $serviceManager)
    {
        self::$serviceManager = $serviceManager;
    }
    /**
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return self::$serviceManager;
    }
}