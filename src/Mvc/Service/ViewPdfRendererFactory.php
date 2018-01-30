<?php

namespace DOMPDFModule\Mvc\Service;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DOMPDFModule\View\Renderer\PdfRenderer;

class ViewPdfRendererFactory implements FactoryInterface
{
    /**
     * @param \Interop\Container\ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|void
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $viewResolver = $container->get('ViewResolver');
        $viewRenderer = $container->get('ViewRenderer');
        $domPdf       = $container->get('DOMPDF');

        $pdfRenderer = new PdfRenderer();
        $pdfRenderer->setResolver($viewResolver);
        $pdfRenderer->setHtmlRenderer($viewRenderer);
        $pdfRenderer->setEngine($domPdf);

        return $pdfRenderer;
    }
}
