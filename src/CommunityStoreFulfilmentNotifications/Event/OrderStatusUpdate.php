<?php
namespace Concrete\Package\CommunityStoreFulfilmentNotifications\Event;

use Concrete\Core\Http\Request;
use Concrete\Core\Support\Facade\Config;
use Concrete\Core\Support\Facade\Application;

class OrderStatusUpdate
{
    public function orderStatusUpdate($event)
    {
        $app = Application::getFacadeApplication();
        $order = $event->getOrder();
        $previousStatusHandle = $event->getPreviousStatusHandle();
        $request = $app->make(Request::class);

        if ($order && $previousStatusHandle !=  $order->getStatusHandle()) {
            $mh = $app->make('helper/mail');

            $email = trim($order->getAttribute('email'));
            $mh->addParameter('order', $order);

            $mh->load('fulfilment_notification', 'community_store_fulfilment_notifications');

            $fromName = Config::get('community_store.emailalertsname');
            $fromEmail = Config::get('community_store.emailalerts');
            if (!$fromEmail) {
                $fromEmail = "store@" . str_replace('www.', '', $request->getHost());
            }

            // new user password email
            if ($fromName) {
                $mh->from($fromEmail, $fromName);
            } else {
                $mh->from($fromEmail);
            }

            $mh->to($email);

            try {
                $mh->sendMail();
            } catch (\Exception $e) {
                \Log::addWarning(t('Community Store: a fulfilment notification failed sending to %s, with error %s', $email, $e->getMessage()));
            }
        }
    }
}
