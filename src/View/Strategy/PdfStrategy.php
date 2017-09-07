<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @author Raymond J. Kolbe <raymond.kolbe@maine.edu>
 * @copyright Copyright (c) 2012 University of Maine
 * @license	http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace DOMPDFModule\View\Strategy;

use DOMPDFModule\View\Model;
use DOMPDFModule\View\Renderer\PdfRenderer;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\View\ViewEvent;

class PdfStrategy extends AbstractListenerAggregate
{
    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = [];

    /**
     * @var PdfRenderer
     */
    protected $renderer;

    /**
     * Constructor
     *
     * @param  PdfRenderer $renderer
     */
    public function __construct(PdfRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * Attach the aggregate to the specified event manager
     *
     * @param  EventManagerInterface $events
     * @param  int $priority
     * @return void
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(ViewEvent::EVENT_RENDERER, [$this, 'selectRenderer'], $priority);
        $this->listeners[] = $events->attach(ViewEvent::EVENT_RESPONSE, [$this, 'injectResponse'], $priority);
    }

    /**
     * Detect if we should use the PdfRenderer based on model type
     *
     * @param  ViewEvent $e
     * @return null|PdfRenderer
     */
    public function selectRenderer(ViewEvent $e)
    {
        $model = $e->getModel();

        if (! $model instanceof Model\PdfModel) {
            // no PdfModel; do nothing
            return;
        }

        // JsonModel found
        return $this->renderer;
    }

    /**
     * Inject the response with the PDF payload and appropriate Content-Type header
     *
     * @param  ViewEvent $e
     * @return void
     */
    public function injectResponse(ViewEvent $e)
    {
        $renderer = $e->getRenderer();
        if ($renderer !== $this->renderer) {
            // Discovered renderer is not ours; do nothing
            return;
        }

        $result   = $e->getResult();
        if (! is_string($result)) {
            // We don't have a string, and thus, no JSON
            return;
        }
        
        $response = $e->getResponse();
        $response->setContent($result);
        $response->getHeaders()->addHeaderLine('content-type', 'application/pdf');
        
        $fileName = $e->getModel()->getOption('filename');
        if (isset($fileName)) {
            if (substr($fileName, -4) != '.pdf') {
                $fileName .= '.pdf';
            }
            
            $response->getHeaders()->addHeaderLine(
                'Content-Disposition',
                'attachment; filename=' . $fileName
            
            );
        }
    }
}
