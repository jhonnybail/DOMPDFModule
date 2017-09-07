<?php

/**
 * Created by PhpStorm.
 * User: ismail
 * Date: 7-9-17
 * Time: 11:33
 */

namespace DOMPDFModuleTest\View\Strategy;

use Zend\EventManager\EventManager;
use Zend\View\Model\ViewModel;
use Zend\View\Resolver\TemplatePathStack;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\ViewEvent;
use Zend\Http\Response as HttpResponse;
use DOMPDFModuleTest\Framework\TestCase;
use DOMPDFModule\View\Model\PdfModel;
use DOMPDFModule\View\Renderer\PdfRenderer;
use DOMPDFModule\View\Strategy\PdfStrategy;

class PdfStrategyTest extends TestCase
{
    /**
     * @var ViewEvent
     */
    private $event;
    /**
     * @var PdfRenderer
     */
    private $renderer;
    /**
     * @var TemplatePathStack
     */
    private $resolver;
    /**
     * @var HttpResponse
     */
    private $response;
    /**
     * System under test.
     *
     * @var PdfStrategy
     */
    private $strategy;
    public function testEventSubscribers()
    {
        $manager = new EventManager();
        $this->assertCount(0, $manager->getListeners(ViewEvent::EVENT_RENDERER), 'Renderer listener before attach');
        $this->assertCount(0, $manager->getListeners(ViewEvent::EVENT_RESPONSE), 'Response listener before attach');
        $this->strategy->attach($manager);
        $this->assertCount(1, $manager->getListeners(ViewEvent::EVENT_RENDERER), 'Renderer listener after attach');
        $this->assertCount(1, $manager->getListeners(ViewEvent::EVENT_RESPONSE), 'Response listener after attach');
        $this->strategy->detach($manager);
        $this->assertCount(0, $manager->getListeners(ViewEvent::EVENT_RENDERER), 'Renderer listener after detach');
        $this->assertCount(0, $manager->getListeners(ViewEvent::EVENT_RESPONSE), 'Response listener after detach');
    }
    public function testSelectsRendererWhenProvidedPdfModel()
    {
        $this->event->setModel(new PdfModel());
        $result = $this->strategy->selectRenderer($this->event);
        $this->assertSame($this->renderer, $result);
    }
    public function testDoesNotSelectRendererWhenNotProvidedPdfModel()
    {
        $this->event->setModel(new ViewModel());
        $result = $this->strategy->selectRenderer($this->event);
        $this->assertNull($result);
    }
    public function testDoesNotRenderPdfWhenRenderMismatch()
    {
        $this->event->setRenderer(new PhpRenderer());
        $result = $this->strategy->injectResponse($this->event);
        $this->assertNull($result);
    }
    public function testDoesNotRenderPdfWhenResultIsNotString()
    {
        $this->event->setRenderer($this->renderer);
        $this->event->setResult(new \stdClass());
        $result = $this->strategy->injectResponse($this->event);
        $this->assertNull($result);
    }

    public function testContentTypeResponseHeader()
    {
        $model = new PdfModel();
        $model->setTemplate('basic.phtml');

        $this->event->setModel($model);
        $this->event->setResponse($this->response);
        $this->event->setRenderer($this->renderer);
        $this->event->setResult($this->renderer->render($model));

        $this->strategy->injectResponse($this->event);

        $headers           = $this->event->getResponse()->getHeaders();
        $contentTypeHeader = $headers->get('content-type');

        $this->assertInstanceOf('Zend\Http\Header\ContentType', $contentTypeHeader);
        $this->assertEquals($contentTypeHeader->getFieldValue(), 'application/pdf');
        ob_end_flush(); // Clear out any buffers held by renderers.
    }

    public function testResponseHeadersWithFileName()
    {
        $model = new PdfModel();
        $model->setTemplate('basic.phtml');
        $model->setOption('filename', 'testPdfFileName');

        $this->event->setModel($model);
        $this->event->setResponse($this->response);
        $this->event->setRenderer($this->renderer);
        $this->event->setResult($this->renderer->render($model));

        $this->strategy->injectResponse($this->event);

        $headers = $this->event->getResponse()->getHeaders();
        $contentDisposition = $headers->get('Content-Disposition');

        $this->assertInstanceOf('Zend\Http\Header\ContentDisposition', $contentDisposition);
        $this->assertEquals($contentDisposition->getFieldValue(), 'attachment; filename=testPdfFileName.pdf');
        ob_end_flush(); // Clear out any buffers held by renderers.
    }
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->renderer = new PdfRenderer();
        $this->strategy = new PdfStrategy($this->renderer);
        $this->event    = new ViewEvent();
        $this->response = new HttpResponse();
        $this->resolver = new TemplatePathStack();
        $this->resolver->addPath(dirname(__DIR__) . '/_templates');
        $this->renderer->setResolver($this->resolver);
        $htmlRenderer = new PhpRenderer();
        $htmlRenderer->setResolver($this->resolver);
        $this->renderer->setHtmlRenderer($htmlRenderer);
        $this->renderer->setEngine($this->getServiceManager()->get('dompdf'));
    }
}
