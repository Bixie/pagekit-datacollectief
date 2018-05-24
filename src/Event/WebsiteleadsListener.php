<?php

namespace Bixie\Datacollectief\Event;


use Bixie\Contactmanager\Communication\Sender;
use Bixie\Contactmanager\Model\Company;
use Bixie\Contactmanager\Model\Contact;
use Bixie\Contactmanager\Model\Log;
use Pagekit\Event\EventSubscriberInterface;

class WebsiteleadsListener implements EventSubscriberInterface {

    protected $logged_messages = [];

    /**
     * @param DatacollectiefApiEvent $event
     */
    public function incomingWebsitelead (DatacollectiefApiEvent $event) {
        $lead = $event->getData();

        $company = Company::where(['external_id' => $lead['CompanyInfo']['Id']])->first();

        if ($company) {

            $sender = new Sender('Websiteleads', 'info@websiteleads.nl');
            $receiver = new Sender('Sales Freight Hero', 'sales@thefreighthero.com');

            Log::create([
                'date' => new \DateTime(),
                'type' => 'email',
                'description' => 'Emailreader import',
                'subject' => 'Website bezoek',
                'content' => '',
                'company_id' => $company->id,
                'contact_id' => 0,
                'user_id' => 0,
                'sender' => $sender->toArray(),
                'receiver' => $receiver->toArray(),
                'data' => $lead,
            ])->save();

            $event->setProcessedData(['company' => $company->toArray(),]);
        }
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     * @return array
     */
    public function subscribe () {
        return [
            'datacollectief.api.websitelead' => 'incomingWebsitelead',
        ];
    }
}