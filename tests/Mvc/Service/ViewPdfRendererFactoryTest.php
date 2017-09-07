<?php

/**
 * Created by PhpStorm.
 * User: ismail
 * Date: 7-9-17
 * Time: 11:29
 */

namespace DOMPDFModuleTest\Mvc\Service;

use DOMPDFModule\Mvc\Service\ViewPdfRendererFactory;
use DOMPDFModuleTest\Framework\TestCase;

class ViewPdfRendererFactoryTest extends TestCase
{
    public function testCreatesService()
    {
        $factory = new ViewPdfRendererFactory();
        /* @var $instance \DOMPDFModule\View\Renderer\PdfRenderer */
        $instance = $factory->createService($this->getServiceManager());
        $this->assertInstanceOf('\DOMPDFModule\View\Renderer\PdfRenderer', $instance);
    }
}
