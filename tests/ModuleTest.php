<?php
/**
 * Created by PhpStorm.
 * User: ismail
 * Date: 7-9-17
 * Time: 11:26
 */

namespace DOMPDFModuleTest;
use DOMPDFModule\Module;

class ModuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Module
     */
    private $module;
    public function testHasConfig()
    {
        $config = $this->module->getConfig();
        // Test the obvious required keys.
        $this->assertArrayHasKey('dompdf_module', $config, 'dompdf_module');
        $this->assertArrayHasKey('service_manager', $config, 'service_manager');
    }
    public function testHasAutoloaderConfig()
    {
        $config = $this->module->getAutoloaderConfig();
        $this->assertInternalType('array', $config, 'config is array');
    }
    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->module = new Module();
    }
}
