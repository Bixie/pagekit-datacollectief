<?php
$view->script('datacollectief-datacollectief-salesviewer', 'bixie/datacollectief:app/bundle/datacollectief-datacollectief-salesviewer.js', [
    'bixie-pkframework', 'taxonomy',],
    ['version' => $app->module('bixie/pk-framework')->getVersionKey($app->package('bixie/datacollectief')->get('version'))]);
?>
<div id="datacollectief-salesviewer">
    <h1>{{ 'Salesviewer API' | trans }}</h1>

    <div class="uk-panel uk-panel-box uk-form uk-form-stacked">
        <div class="uk-grid uk-grid-width-medium-1-3" data-uk-grid-margin>
            <div>
                <label class="uk-form-label">{{ 'Check from' | trans }}</label>
                <div class="uk-form-controls">
                    <input-date-bix :datetime.sync="filter.from" :show-time="false"></input-date-bix>
                </div>
            </div>
            <div>
                <label class="uk-form-label">{{ 'Check until' | trans }}</label>
                <div class="uk-form-controls">
                    <input-date-bix :datetime.sync="filter.to" :show-time="false"></input-date-bix>
                </div>
            </div>
            <div class="uk-text-right">
                <button type="button" class="uk-button uk-button-primary"
                        :disabled="loading"
                        @click="getSessions(0)">
                    <i v-spinner="loading" icon="refresh" spinner="refresh"></i>
                    {{ 'Load' | trans }}
                </button>
            </div>
        </div>

    </div>

    <div v-if="sessions.totals" class="uk-margin uk-grid uk-grid-width-medium-1-3">
        <div><h3>{{ 'Visitors: ' | trans }}{{ sessions.totals.VisitorsCount }}</h3></div>
        <div><h3>{{ 'Companies: ' | trans }}{{ sessions.totals.companies }}</h3></div>
        <div>
            <v-pagination :page.sync="page" :pages="sessions.pagination.total" v-show="sessions.pagination.total > 1"></v-pagination>
        </div>
    </div>


    <div v-if="processed_data.length && !loading" class="uk-flex uk-flex-middle uk-flex-space-between uk-margin-top">
        <ul class="uk-subnav uk-subnav-line uk-margin-bottom-remove">
            <li :class="{'uk-active': filter.status === ''}"><a
                        @click="filter.status = ''">Toon alles</a></li>
            <li :class="{'uk-active': filter.status === 'new'}"><a
                        @click="filter.status = 'new'">Alleen nieuwe bedrijven</a></li>
            <li :class="{'uk-active': filter.status === 'matched'}"><a
                        @click="filter.status = 'matched'">Alleen bestaande bedrijven</a></li>
            <li :class="{'uk-active': filter.status === 'modified'}"><a
                        @click="filter.status = 'modified'">Alleen gewijzigde bedrijven</a></li>
        </ul>
        <div>
            <strong>{{ filteredProcessedData.length }} leads</strong>
        </div>
    </div>

    <ul class="uk-list uk-list-striped">
        <li v-for="companyLeads in leadsByCompany" track-by="company.id">
            <div class="uk-grid uk-grid-small uk-grid-width-1-5" data-uk-grid-margin>
                <div>
                    <div v-for="message in companyLeads.messages">{{ message }}</div>
                    <div v-for="changed_data in companyLeads.changed_data">
                        <strong>Changed: {{ changed_data.key }}</strong><br/>
                        {{ changed_data.old_value || '(empty)' }} --> {{ changed_data.new_value }}
                    </div>
                </div>
                <div>
                    <strong>{{ companyLeads.company.name }}</strong><br/>
                    {{ companyLeads.company.address_1 }}<br/>
                    {{ companyLeads.company.zipcode }} {{ companyLeads.company.city }}<br/>

                </div>
                <div class="uk-text-truncate">
                    {{ companyLeads.company.email }}<br/>
                    <a :href="getSiteUrl(companyLeads.company.data.website)" target="_blank">
                        <i class="uk-icon-external-link uk-margin-small-right"></i>
                        {{ companyLeads.company.data.website }}
                    </a><br/>
                    Tel: <a v-phone.auto="companyLeads.company.phone">{{ companyLeads.company.phone }}</a><br/>
                </div>
                <div>
                    <a :href="$url.route('admin/contactmanager/company/edit', { id: companyLeads.company.id })"
                       target="_blank">
                        <i class="uk-icon-external-link uk-margin-small-right"></i>
                        {{ companyLeads.company.name }}
                    </a>

                </div>
                <div>

                    <input-terms-many taxonomy-name="cm.company.indication"
                                      :item_id="companyLeads.company.id"></input-terms-many>

                </div>
            </div>

            <div v-for="lead in companyLeads.leads" track-by="guid" class="uk-grid uk-grid-small">
                <div class="uk-width-1-4">
                    <div class="uk-text-bold">
                        {{ lead.startedAt | date }}
                    </div>
                </div>
                <div class="uk-width-3-4">
                    <div v-html="lead.ClickPathContent"></div>
                </div>
            </div>
        </li>
    </ul>

</div>