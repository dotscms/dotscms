<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Dots\View\Helper;
use Zend\View\Helper\AbstractHelper;
use Zend\EventManager\Event;
use Dots\Registry;
use Dots\EventManager\GlobalEventManager;

class DotsNav extends AbstractHelper
{
    /**
     * Render the administrator navigation bar
     * @return DotsNav
     */
    public function __invoke()
    {
        // attach default listeners to the following events
        $this->attachConfigEvents();

        // trigger the head.pre event
        GlobalEventManager::trigger('head.pre', $this->view);

        // trigger the head.post event
        GlobalEventManager::trigger('head.post', $this->view);

        //trigger the event listeners
        $dotsBodyInline = $this->triggerInlineEvent('body.inline');

        // do not render anything for the admin section if not logged in
        if (!$this->view->plugin("auth")->isLoggedIn()) {
            return $dotsBodyInline;
        }

        // trigger the admin.head.pre event
        GlobalEventManager::trigger('admin.head.pre', $this->view);

        // trigger the admin.head.post event
        GlobalEventManager::trigger('admin.head.post', $this->view);

        // render the navigation bar
        $navigation = $this->view->render('dots/helpers/dots-nav/admin/main');

        //trigger the event listeners
        $dotsAdminBodyInline = $this->triggerInlineEvent('admin.body.inline');
        return $navigation . $dotsBodyInline . $dotsAdminBodyInline;
    }

    /**
     * Trigger the specified event and merge the results into one string
     * @param $name
     * @return string
     */
    protected function triggerInlineEvent($name){
        $responses = GlobalEventManager::trigger($name, $this->view);
        $inline = "";
        //merge all results and return the response
        foreach ($responses as $response) {
            $inline .= $response;
        }
        return $inline;
    }

    /**
     * Attach scripts and links to the view helpers based on the configuration file.
     * @todo Handle priority
     * @todo Add extended specification for scripts and links
     */
    protected function attachConfigEvents()
    {
        $serviceLocator = Registry::get('service_locator');
        $config = $serviceLocator->get('Configuration');
        if (isset($config['dots']['view']['events']) && is_array($config['dots']['view']['events'])){
            foreach($config['dots']['view']['events'] as $name => $options){
                if (!empty($options)){
                    GlobalEventManager::attach($name, function(Event $event) use($options)
                    {
                        $view = $event->getTarget();
                        if (isset($options['scripts']) && is_array($options['scripts'])) {
                            foreach ($options['scripts'] as $script) {
                                $view->plugin('headScript')->appendFile($script);
                            }
                        }
                        if (isset($options['links']) && is_array($options['links'])) {
                            foreach ($options['links'] as $script) {
                                $view->plugin('headLink')->appendStylesheet($script);
                            }
                        }
                    }, 200);
                }
            }
        }
    }

}