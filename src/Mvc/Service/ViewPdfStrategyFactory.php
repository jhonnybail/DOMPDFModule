<?php

namespace DOMPDFModule\Mvc\Service;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use DOMPDFModule\View\Strategy\PdfStrategy;

class ViewPdfStrategyFactory implements FactoryInterface
{
    /**
     * @param \Interop\Container\ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|void
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $pdfRenderer = $container->get('ViewPdfRenderer');
        $pdfStrategy = new PdfStrategy($pdfRenderer);

        return $pdfStrategy;
    }
}
