<?php

namespace DOMPDFModule;

use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;

class Module
{
    const VERSION = '3.0.3-dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function init(ModuleManager $moduleManager)
    {
        //echo "User init <br/>";
    }

    public function onBootstrap(MvcEvent $mvcEvent)
    {
        //echo "User bootstrap <br/>";
    }
}
