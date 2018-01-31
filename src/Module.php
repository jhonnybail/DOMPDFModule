<?php

namespace DOMPDFModule;

use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;

class Module
{
    const VERSION = '1.0.1';

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
