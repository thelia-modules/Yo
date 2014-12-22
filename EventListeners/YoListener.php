<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace Yo\EventListeners;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Router;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Tools\URL;
use Thelia\Model\ConfigQuery;
use Yo\Client;
use Yo\Yo;

/**
 * Class YoListener
 * @package YoT\EventListeners
 * @author Manuel Raynaud <manu@thelia.net>
 */
class YoListener implements EventSubscriberInterface
{
    /**
     * @var Router $adminRouter
     */
    protected $adminRouter;

    public function __construct(Router $adminRouter)
    {
        $this->adminRouter = $adminRouter;
    }

    public function createOrder(OrderEvent $event)
    {
        $yo = new Client(ConfigQuery::read('yo_api_key'));
        $orderUrl = URL::getInstance()->absoluteUrl($this->adminRouter->generate("admin.order.update.view", ['order_id' => $event->getOrder()->getId()]));
        $yo->yo(ConfigQuery::read('yo_username'), $orderUrl);
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return [
            TheliaEvents::ORDER_AFTER_CREATE => "createOrder"
        ];
    }
}
