<?php
namespace ZeTwig\View\Strategy;

use Zend\EventManager\EventCollection,
    Zend\EventManager\ListenerAggregate,
    ZeTwig\View\Renderer as TwigRenderer;

/**
 *
 */
class TwigRendererStrategy implements ListenerAggregate
{
    /**
     * @var TwigRenderer
     */
    protected $renderer;
    /**
     * @var array
     */
    protected $listeners = array();

    /**
     * @param \ZeTwig\View\Renderer $renderer
     */
    public function __construct(TwigRenderer $renderer)
    {
        $this->renderer  = $renderer;
    }

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventCollection $events
     * @param null|int $priority Optional priority "hint" to use when attaching listeners
     */
    public function attach(EventCollection $events, $priority = null)
    {
        if (null === $priority) {
            $this->listeners[] = $events->attach('renderer', array($this, 'selectRenderer'));
            $this->listeners[] = $events->attach('response', array($this, 'injectResponse'));
        } else {
            $this->listeners[] = $events->attach('renderer', array($this, 'selectRenderer'), $priority);
            $this->listeners[] = $events->attach('response', array($this, 'injectResponse'), $priority);
        }
    }

    /**
     * Detach all previously attached listeners
     * @param \Zend\EventManager\EventCollection $events
     */
    public function detach(EventCollection $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * @param $e
     * @return TwigRenderer
     */
    public function selectRenderer($e = null)
    {

        return $this->renderer;
    }

    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * Inject the result into the response object
     * @param $e
     */
    public function injectResponse($e)
    {
        $response = $e->getResponse();
        $result   = $e->getResult();
        $response->setContent($result);
    }

}













