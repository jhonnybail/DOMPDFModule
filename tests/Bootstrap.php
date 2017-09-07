<?php
/**
 * Created by PhpStorm.
 * User: ismail
 * Date: 7-9-17
 * Time: 11:39
 */

use DOMPDFModuleTest\Framework\TestCase;
use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Service\ServiceManagerConfig;

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

if (is_readable(__DIR__ . '/TestConfiguration.php')) {
    $configuration = include_once __DIR__ . '/TestConfiguration.php';
} else {
    $configuration = include_once __DIR__ . '/TestConfiguration.php.dist';
}

require_once __DIR__ . '/../vendor/autoload.php';

$application = \Zend\Mvc\Application::init($configuration);
$serviceManager = $application->getServiceManager();
TestCase::setServiceManager($serviceManager);
