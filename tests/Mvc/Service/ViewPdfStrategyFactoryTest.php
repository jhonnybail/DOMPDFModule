<?php
/**
 * Created by PhpStorm.
 * User: ismail
 * Date: 7-9-17
 * Time: 11:30
 */

namespace DOMPDFModuleTest\Mvc\Service;

use DOMPDFModule\Mvc\Service\ViewPdfStrategyFactory;
use DOMPDFModuleTest\Framework\TestCase;

class ViewPdfStrategyFactoryTest extends TestCase
{
    public function testCreatesService()
    {
        $factory = new ViewPdfStrategyFactory();
        /* @var $instance \DOMPDFModule\View\Strategy\PdfStrategy */
        $instance = $factory->createService($this->getServiceManager());
        $this->assertInstanceOf('\DOMPDFModule\View\Strategy\PdfStrategy', $instance);
    }
}
