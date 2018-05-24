<?php


namespace Bixie\Datacollectief\Event;


use Bixie\Emailreader\Event\EmailreaderEvent;
use Pagekit\Event\EventSubscriberInterface;
use Pagekit\Application as App;

class DatacollectiefEmailreaderListener implements EventSubscriberInterface {


    public function incomingEmail (EmailreaderEvent $event) {

        $matched = null;
        foreach ($event->getOwnReceivers() as $receiver) {
            if (preg_match('/^websiteleads/', $receiver)) {
                $matched = true;
                break;
            }
        }
        if (!$matched) {
            return;
        }
        $body = $event->getIncomingMail()->textHtml;

        $dc_link = null;
        $dc_id = null;
        $pages = [];
        if (preg_match('/\"(https:\/\/mijn\.datacollectief\.nl\?id=(\d{2,})&amp;a=wsl)\"/', $body, $matches)) {
            $dc_link = $matches[1];
            $dc_id = $matches[2];
        }
        if (preg_match_all('/>(https:\/\/www\.thefreighthero\.nl([^<]*)?)</', $body, $matches)) {
            $pages = $matches[1];
        }

        if ($dc_link && $dc_id) {
            App::trigger(new DatacollectiefApiEvent('datacollectief.api.websitelead', [
                'dc_link' => $dc_link,
                'dc_id' => $dc_id,
                'text' => $event->getCleanedBody(),
                'pages' => $pages,
            ]));
            $event->addProcessedBy('dc.websiteleads', sprintf(
                'Websitelead triggered for datacollectief id %d',
                $dc_id
            ));
        }

    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     * @return array
     */
    public function subscribe () {
        return [
            'emailreader.mail.incoming' => 'incomingEmail',
        ];
    }
}