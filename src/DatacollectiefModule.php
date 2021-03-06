<?php

namespace Bixie\Datacollectief;


use Pagekit\Application as App;
use Pagekit\Module\Module;

/**
 * Datacollectief Main Module
 */
class DatacollectiefModule extends Module
{

    /**
     * @param App $app
     * @return void
     */
    public function main(App $app)
    {

        $app->on('boot', function () use ($app) {

            $app['datacollectief.api'] = function ($app) {
                return new Api\Api($this->config([
                    'api_url', 'application_name', 'user', 'password',
                 ]), $app['debug']);
            };

            $app['salesviewer.api'] = function ($app) {
                return new Salesviewer\Api($this->config(['sv_api_key',]), $app['debug']);
            };

        });

    }

    /**
     * @param string $functionID
     * @return string
     */
    public function getFunctionDescription ($functionID) {
        if (isset($this->config['BaseTableFunction'][$functionID])) {
            return $this->config['BaseTableFunction'][$functionID]['Description'];
        }
        return $functionID;
    }

    /**
     * @param string $brancheID
     * @return string
     */
    public function getBrancheDescription ($brancheID) {
        if (isset($this->config['BaseTableBranche'][$brancheID])) {
            return $this->config['BaseTableBranche'][$brancheID]['Description'];
        }
        return $brancheID;
    }

    /**
     * @param string $EmployeeID
     * @return string
     */
    public function getNumberOfEmployeesDescription ($EmployeeID) {
        if (isset($this->config['BaseTableEmployee'][$EmployeeID])) {
            return $this->config['BaseTableEmployee'][$EmployeeID]['Description'];
        }
        return $EmployeeID;
    }

    /**
     * @param string $ImportExportID
     * @return string
     */
    public function getImportExportDescription ($ImportExportID) {
        if (isset($this->config['BaseTableImportExport'][$ImportExportID])) {
            return $this->config['BaseTableImportExport'][$ImportExportID]['Description'];
        }
        return $ImportExportID;
    }

    /**
     * @param string $LegalFormID
     * @return string
     */
    public function getLegalFormDescription ($LegalFormID) {
        if (isset($this->config['BaseTableLegalForm'][$LegalFormID])) {
            return $this->config['BaseTableLegalForm'][$LegalFormID]['Description'];
        }
        return $LegalFormID;
    }

    /**
     * @param string $Code
     * @return string
     */
    public function getMessageReasonDescription ($Code) {
        if (isset($this->config['BaseTableMessageReason'][$Code])) {
            return $this->config['BaseTableMessageReason'][$Code]['Description'];
        }
        return $Code;
    }


}

