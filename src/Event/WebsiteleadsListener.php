<?php

namespace Bixie\Datacollectief\Event;


use Bixie\Contactmanager\Communication\Sender;
use Bixie\Contactmanager\Model\Company;
use Bixie\Contactmanager\Model\Contact;
use Bixie\Contactmanager\Model\Log;
use Pagekit\Event\EventSubscriberInterface;
use Pagekit\Application as App;
use Pagekit\Routing\Generator\UrlGenerator;

class WebsiteleadsListener implements EventSubscriberInterface {

    /**
     * @var array keys xref dc_key => cm_key
     */
    protected $dc_cm_keys = [
        'idField' => 'external_id',
        'nameField' => 'name',
        'zipCodeField' => 'zipcode',
        'cityField' => 'city',
        'emailField' => 'email',
        'telephoneField' => 'phone',
    ];
    /**
     * @var array keys xref dc_key => cm_key
     */
    protected $dc_cm_keys_data = [
        'uRLField' => 'website',
        'coCnumberField' => 'coc_number',
    ];
    /**
     * @param DatacollectiefApiEvent $event
     */
    public function incomingWebsitelead (DatacollectiefApiEvent $event) {
        $lead = $event->getData();
        $result = [
            'handler' => 'WebsiteleadsListener',
            'messages' => [],
            'changed_data' => [],
            'isNewCompany' => false,
            'company' => null,
            'lead' => null,
        ];

        $lead_id = $lead['idField'];
        $company_info = $lead['companyInfoField'];
        $visit_info = $lead['visitInfoField'];
        $clickpath_routes = $lead['clickPathRouteField'];
        //try to find company by external id
        if (!$company = Company::where(['external_id' => $company_info['idField'],])->first()) {
            //then try name/coc
            $coc_number_unpadded = trim($company_info['coCnumberField'], '0');
            if (!$company = Company::where(['name' => $company_info['nameField'],])->first()) {
                //try to get coc from data
                $company = Company::where(['data LIKE :coc_like'], ['coc_like' => "%$coc_number_unpadded%",])
                    ->first();
            }
            //check with coc if found with name/cocdata
            if ($company && stripos($company_info['coCnumberField'], $company->get('coc_number')) === false) {
                $company = null;
            }
        }
        if (!$company) {
            //create company
            $company = $this->createCompany($company_info);
            $indications = [];
            //add task to check

            $result['messages'][] = sprintf('New company %d created', $company->id);
            $result['isNewCompany'] = true;
        } else {
            $result['messages'][] = sprintf('Existing company %d matched', $company->id);
            $indications = $company->getIndications();
            //check data
            $result['changed_data'] = $this->updateCompany($company, $company_info);
        }
        //todo check contacts

        //check tags
        $wl_tag = App::module('bixie/datacollectief')->config('wl_tag', '');
        $hasWlTag = false;
        foreach ($indications as $index => $indication) {
            if ($indication->slug == $wl_tag) {
                $hasWlTag = true;
                break;
            }
        }
        if (!$hasWlTag) {
            $taxonomy = App::taxonomy('cm.company.indication');
            $indications[] = $taxonomy->termBySlug($wl_tag);
            $taxonomy->saveTerms($company->id, $indications);
            $result['messages'][] = sprintf('Tag %s added', $wl_tag);
        }

        //set log. Index lead_id in description to prevent double entries
        $description = "$lead_id.websiteleads.API";
        $result['clickpath'] = $this->getClickPathContent($clickpath_routes);
        if (!$log = Log::where(compact('description'))->first()) {
            $visit_date = new \DateTime($visit_info['startDateTimeField']);
            $this->createLog('site_visit', [
                'date' => $visit_date,
                'description' => $description,
                'subject' => sprintf('Sitebezoek, %d pagina\'s, %d minuten (%d) - %s',
                    $visit_info['numberOfPagesField'], round($visit_info['durationField'] / 60, 1),
                    $visit_info['highestRatingScoreField'], $visit_date->format('d-m H:i')
                ),
                'content' => $result['clickpath'],
                'company_id' => $company->id,
                'data' => $lead,
            ]);
            $result['messages'][] = 'Log added';
        }

        $result['company'] = $company->toArray();
        $result['lead'] = $lead;

        $event->setProcessedData(['websiteleads' => $result,]);
    }

    /**
     * @param array $company_info
     * @return Company
     */
    protected function createCompany ($company_info) {
        $company = Company::create([
            'external_id' => $company_info['idField'],
            'name' => $company_info['nameField'],
            'address_1' => trim($company_info['streetField'] . ' ' . $company_info['streetNumberField'] . ' ' . $company_info['streetnumberSuffixField']),
            'zipcode' => $company_info['zipCodeField'],
            'city' => $company_info['cityField'],
            'country_code' => 'NL',
            'email' => $company_info['emailField'],
            'phone' => $company_info['telephoneField'],
            'description' => implode("\n\n", [
                $company_info['branchField']['valueField'],
                $company_info['numberOfEmployeesField']['valueField'],
                $company_info['legalFormField']['valueField'],
            ]),
        ]);
        $company->set('website', $company_info['uRLField']);
        $company->set('coc_number', $company_info['coCnumberField']);
        $company->save();

        return $company;
    }

    /**
     * @param Company $company
     * @param $company_info
     * @return array
     */
    protected function updateCompany (&$company, $company_info) {
        $changed_data = [];
        foreach ($this->dc_cm_keys as $dc_key => $cm_key) {
            if ($company->$cm_key != $company_info[$dc_key]) {
                $changed_data[] = [
                    'key' => $cm_key,
                    'old_value' => $company->$cm_key,
                    'new_value' => $company_info[$dc_key],
                ];
                $company->$cm_key = $company_info[$dc_key];
            }
        }
        //address
        $address = trim($company_info['streetField'] . ' ' . $company_info['streetNumberField'] . ' ' . $company_info['streetnumberSuffixField']);
        if ($company->address_1 != $address) {
            $changed_data[] = [
                'key' => 'address_1',
                'old_value' => $company->address_1,
                'new_value' => $address,
            ];
            $company->address_1 = $address;
        }
        foreach ($this->dc_cm_keys_data as $dc_key => $data_key) {
            if ($company->get($data_key) != $company_info[$dc_key]) {
                $changed_data[] = [
                    'key' => $dc_key,
                    'old_value' => $company->get($dc_key),
                    'new_value' => $company_info[$dc_key],
                ];
                $company->set($data_key, $company_info[$dc_key]);
            }
        }
        if (count($changed_data)) {
            $company->save();
            $this->createLog('data_change', [
                'description' => 'Import data from Websiteleads',
                'subject' => sprintf('%d veld(en) gewijzigd', count($changed_data)),
                'company_id' => $company->id,
                'data' => ['changed_data' => $changed_data,],
            ]);
        }
        return $changed_data;
    }

    /**
     * @param array $clickpath_routes
     * @return string
     */
    protected function getClickPathContent($clickpath_routes) {
        $lines = [];
        $base = App::url()->get('', [], UrlGenerator::ABSOLUTE_URL);
        foreach ($clickpath_routes as $clickpath_route) {
            $duration = $clickpath_route['durationField'];
            $lines[] = sprintf('<a href="%s" target="_blank">%s</a> - %s - %s',
                $clickpath_route['urlField'],
                str_replace($base, '', $clickpath_route['urlField']),
                ($duration > 120 ? round($duration / 60, 1) . ' minuten' : $duration . ' sec.'),
                $clickpath_route['clickTypeField']
            );
        }
        return implode("<br/>", $lines);
    }

    protected function createLog ($type, $data) {
        $sender = new Sender('Websiteleads', 'info@websiteleads.nl');
        $receiver = new Sender('Sales Freight Hero', 'sales@thefreighthero.com');
        $log = Log::create(array_merge([
            'date' => new \DateTime(),
            'type' => $type,
            'description' => '',
            'subject' => '',
            'content' => '',
            'company_id' => 0,
            'contact_id' => 0,
            'user_id' => 0,
            'sender' => $sender->toArray(),
            'receiver' => $receiver->toArray(),
            'data' => [],
        ], $data));
        $log->save();
        return $log;
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