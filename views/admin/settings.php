
<?php
$view->script('datacollectief-settings', 'bixie/datacollectief:app/bundle/datacollectief-settings.js', ['bixie-pkframework'],
    ['version' => $app->module('bixie/pk-framework')->getVersionKey($app->package('bixie/datacollectief')->get('version'))]);
?>
<div id="datacollectief-settings">
    <div class="uk-grid pk-grid-large" data-uk-grid-margin>
        <div class="pk-width-sidebar">

            <div class="uk-panel">

                <ul class="uk-nav uk-nav-side pk-nav-large" data-uk-tab="{ connect: '#tab-content' }">
                    <li><a><i class="pk-icon-large-settings uk-margin-right"></i> {{ 'Settings' | trans }}</a></li>
                    <li><a><i class="pk-icon-large-settings uk-margin-right"></i> {{ 'Websiteleads' | trans }}</a></li>
                    <li><a><i class="pk-icon-large-database uk-margin-right"></i> {{ 'Branches' | trans }}</a></li>
                    <li><a><i class="pk-icon-large-database uk-margin-right"></i> {{ 'Employees' | trans }}</a></li>
                    <li><a><i class="pk-icon-large-database uk-margin-right"></i> {{ 'Data' | trans }}</a></li>
                    <li><a><i class="pk-icon-large-database uk-margin-right"></i> {{ 'Reasons' | trans }}</a></li>
                </ul>

            </div>

        </div>
        <div class="pk-width-content">

            <ul id="tab-content" class="uk-switcher uk-margin">
                <li class="uk-form uk-form-horizontal">

                    <div class="uk-margin uk-flex uk-flex-space-between uk-flex-wrap" data-uk-margin>
                        <h2 class="uk-margin-remove">{{ 'Datacollectief Settings' | trans }}</h2>
                        <div>

                            <button class="uk-button uk-button-primary" @click="save">{{ 'Save' | trans }}</button>

                        </div>
                    </div>

                    <bixie-fields :config="$options.fields.settings" :values.sync="config"></bixie-fields>

                    <hr/>

                    <button type="button" class="uk-button" @click="getApiInfo">
                        <i class="uk-icon-info uk-margin-small-right"></i>
                        {{ 'Get API info' | trans }}
                    </button>

                    <div v-if="apiInfo.Version" class="uk-margin uk-panel uk-panel-box">
                        <dl class="uk-description-list-horizontal">
                            <dt>{{ 'Version' | trans }}</dt>
                            <dd>{{apiInfo.Version}}</dd>
                            <dt>{{ 'Payment method' | trans }}</dt>
                            <dd>{{apiInfo.PaymentMethod}}</dd>
                            <dt>{{ 'Credits acquired' | trans }}</dt>
                            <dd>{{apiInfo.CreditsAcquired}}</dd>
                            <dt>{{ 'Credits used' | trans }}</dt>
                            <dd>{{apiInfo.CreditsUsed}}</dd>
                            <dt>{{ 'Credits balance' | trans }}</dt>
                            <dd>{{apiInfo.CreditsBalance}}</dd>
                            <dt>{{ 'Credits from mutations' | trans }}</dt>
                            <dd>{{apiInfo.CreditsFromWUS}}</dd>
                            <dt>{{ 'Credits used for alerts' | trans }}</dt>
                            <dd>{{apiInfo.CreditsUsedForAlerts}}</dd>
                        </dl>
                    </div>

                </li>
                <li class="uk-form uk-form-horizontal">

                    <h2>{{ 'Websiteleads Settings' | trans }}</h2>

                    <div class="uk-form-row">
                        <label class="uk-form-label">{{ 'Websiteleads last checked' | trans }}</label>
                        <div class="uk-form-controls">
                            <input-date-bix :datetime.sync="config.wl_last_checked"></input-date-bix>
                        </div>
                    </div>

                    <div class="uk-form-row">
                        <label class="uk-form-label">{{ 'Websiteleads tags negeren' | trans }}</label>

                        <div class="uk-form-controls">
                            <select v-model="config.wl_tag_ignore" class="uk-form-width-medium" multiple size="4">
                                <option value="">{{ 'Select tag' | trans }}</option>
                                <option v-for="tag in indications" :value="tag.slug">{{ tag.title }}</option>
                            </select>
                            <p class="uk-form-help-block">{{ 'Negeer bedrijven met deze tags' | trans }}</p>
                        </div>
                    </div>

                    <div class="uk-form-row">
                        <label class="uk-form-label">{{ 'Websiteleads tags toevoegen' | trans }}</label>

                        <div class="uk-form-controls">
                            <select v-model="config.wl_tag_add" class="uk-form-width-medium" multiple size="4">
                                <option value="">{{ 'Select tag' | trans }}</option>
                                <option v-for="tag in indications" :value="tag.slug">{{ tag.title }}</option>
                            </select>
                            <p class="uk-form-help-block">{{ 'Voeg deze tags toe aan bedrijf' | trans }}</p>
                        </div>
                    </div>

                    <div class="uk-form-row">
                        <label class="uk-form-label">{{ 'Websiteleads tags verwijderen' | trans }}</label>

                        <div class="uk-form-controls">
                            <select v-model="config.wl_tag_remove" class="uk-form-width-medium" multiple size="4">
                                <option value="">{{ 'Select tag' | trans }}</option>
                                <option v-for="tag in indications" :value="tag.slug">{{ tag.title }}</option>
                            </select>
                            <p class="uk-form-help-block">{{ 'Verwijder deze tags bij bedrijf' | trans }}</p>
                        </div>
                    </div>

                </li>
                <li>

                    <div class="uk-margin uk-flex uk-flex-space-between uk-flex-wrap" data-uk-margin>
                        <h2 class="uk-margin-remove">{{ 'Branches' | trans }}</h2>
                        <div>
                            <a @click="getBaseTable('BaseTableBranche', 'BrancheID')">
                                <i class="uk-icon-refresh uk-margin-small-right"></i>
                                {{ 'Refresh data' | trans }}
                            </a>
                        </div>
                    </div>

                    <ul class="uk-margin uk-list uk-list-line">
                        <li v-for="item in config.BaseTableBranche" class="uk-flex">
                            <div class="uk-width-1-6 uk-text-right uk-margin-right"><em>{{item.BrancheID}}</em></div>
                            <div class="uk-flex-item-1">{{item.Description}}</div>
                        </li>
                    </ul>

                </li>
                <li>

                    <div class="uk-margin uk-flex uk-flex-space-between uk-flex-wrap" data-uk-margin>
                        <h2 class="uk-margin-remove">{{ 'Employees' | trans }}</h2>
                        <div>
                            <a @click="getBaseTable('BaseTableEmployee', 'EmployeeID')">
                                <i class="uk-icon-refresh uk-margin-small-right"></i>
                                {{ 'Refresh data' | trans }}
                            </a>
                        </div>
                    </div>

                    <ul class="uk-margin uk-list uk-list-line">
                        <li v-for="item in config.BaseTableEmployee" class="uk-flex">
                            <div class="uk-width-1-6 uk-text-right uk-margin-right"><em>{{item.EmployeeID}}</em></div>
                            <div class="uk-flex-item-1">{{item.Description}}</div>
                        </li>
                    </ul>

                </li>
                <li>

                    <div class="uk-margin uk-flex uk-flex-space-between uk-flex-wrap" data-uk-margin>
                        <h2 class="uk-margin-remove">{{ 'Import Export' | trans }}</h2>
                        <div>
                            <a @click="getBaseTable('BaseTableImportExport', 'ImportExportID')">
                                <i class="uk-icon-refresh uk-margin-small-right"></i>
                                {{ 'Refresh data' | trans }}
                            </a>
                        </div>
                    </div>

                    <ul class="uk-margin uk-list uk-list-line">
                        <li v-for="item in config.BaseTableImportExport" class="uk-flex">
                            <div class="uk-width-1-6 uk-text-right uk-margin-right"><em>{{item.ImportExportID}}</em></div>
                            <div class="uk-flex-item-1">{{item.Description}}</div>
                        </li>
                    </ul>

                    <div class="uk-margin uk-flex uk-flex-space-between uk-flex-wrap" data-uk-margin>
                    <h2 class="uk-margin-remove">{{ 'Legal form' | trans }}</h2>
                        <div>
                            <a @click="getBaseTable('BaseTableLegalForm', 'LegalFormID')">
                                <i class="uk-icon-refresh uk-margin-small-right"></i>
                                {{ 'Refresh data' | trans }}
                            </a>
                        </div>
                    </div>

                    <ul class="uk-margin uk-list uk-list-line">
                        <li v-for="item in config.BaseTableLegalForm" class="uk-flex">
                            <div class="uk-width-1-6 uk-text-right uk-margin-right"><em>{{item.LegalFormID}}</em></div>
                            <div class="uk-flex-item-1">{{item.Description}}</div>
                        </li>
                    </ul>

                </li>
                <li>

                    <div class="uk-margin uk-flex uk-flex-space-between uk-flex-wrap" data-uk-margin>
                        <h2 class="uk-margin-remove">{{ 'Reasons' | trans }}</h2>
                        <div>
                            <a @click="getBaseTable('BaseTableMessageReasons', 'Code')">
                                <i class="uk-icon-refresh uk-margin-small-right"></i>
                                {{ 'Refresh data' | trans }}
                            </a>
                        </div>
                    </div>

                    <ul class="uk-margin uk-list uk-list-line">
                        <li v-for="item in config.BaseTableMessageReasons" class="uk-flex">
                            <div class="uk-width-1-6 uk-text-right uk-margin-right"><em>{{item.Code}}</em></div>
                            <div class="uk-flex-item-1">
                                {{item.Description}}<br/>
                                <small>Comp: {{item.ApplicableForCompanies}}, Cont: {{item.ApplicableForContacts}}, Oth: {{item.ApplicableForOther}}</small>
                            </div>
                        </li>
                    </ul>

                </li>
            </ul>

        </div>

    </div>
</div>