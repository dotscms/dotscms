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

class DotsNav extends AbstractHelper
{
    /**
     * Render the administrator navigation bar
     * @return DotsNav
     */
    public function __invoke()
    {
        // do not render anything for the admin section if not logged in
        if (!$this->view->plugin("auth")->isLoggedIn()) {
            return '';
        }

        // render the navigation bar
        $navigation = $this->view->render('dots/helpers/dots-nav/admin/main');
        return $navigation;
    }
}