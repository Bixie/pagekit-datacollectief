
<?php
$view->script('datacollectief-datacollectief-index', 'bixie/datacollectief:app/bundle/datacollectief-datacollectief-index.js', [
        'bixie-pkframework', 'taxonomy',],
    ['version' => $app->module('bixie/pk-framework')->getVersionKey($app->package('bixie/datacollectief')->get('version'))]);
?>
<div id="datacollectief-index">
    <h1>{{ 'Websiteleads API' | trans }}</h1>

    <div class="uk-panel uk-panel-box uk-form uk-form-stacked">
        <div class="uk-grid uk-grid-width-medium-1-3" data-uk-grid-margin>
            <div>
                <label class="uk-form-label">{{ 'Check from' | trans }}</label>
                <div class="uk-form-controls">
                    <input-date-bix :datetime.sync="wl_options.From" :show-time="false"></input-date-bix>
                </div>
            </div>
            <div>
                <label class="uk-form-label">{{ 'Check until' | trans }}</label>
                <div class="uk-form-controls">
                    <input-date-bix :datetime.sync="wl_options.To" :show-time="false"></input-date-bix>
                </div>
            </div>
            <div>
                <label class="uk-form-label">{{ 'Website' | trans }}</label>
                <div class="uk-form-controls uk-flex uk-flex-middle uk-flex-space-between">
                    <select v-model="wl_options.Website" class="uk-width-7-10">
                        <option value="">{{ 'Select website' | trans }}</option>
                        <option v-for="website in websites" :value="website">{{ website }}</option>
                    </select>
                    <button type="button" class="uk-button uk-button-primary"
                            :disabled="!wl_options.Website || loading"
                            @click="getWebsiteleads">
                        <i v-spinner="loading" icon="refresh" spinner="refresh"></i>
                        {{ 'Load' | trans }}
                    </button>
                </div>
            </div>
        </div>

    </div>

    <div v-if="processed_data.length >= 250" class="uk-alert uk-alert-danger">
        Meer dan 250 leads gevonden. Zoek met een kleiner datumbereik om er zeker van te zijn alle leads te laden</div>

    <div v-if="processed_data.length" class="uk-flex uk-flex-middle uk-flex-space-between uk-margin-top">
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
        <li v-for="processed in filteredProcessedData" track-by="lead.idField">
            <div class="uk-grid uk-grid-small">
                <div class="uk-width-1-4">
                    <div class="uk-text-bold">
                        {{ processed.lead.visitInfoField.startDateTimeField | date }} (score {{ processed.lead.visitInfoField.highestRatingScoreField }})
                    </div>
                    <div v-for="message in processed.messages">{{ message }}</div>
                    <div v-for="changed_data in processed.changed_data">
                        <strong>Changed: {{ changed_data.key }}</strong><br/>
                        {{ changed_data.old_value || '(empty)' }} --> {{ changed_data.new_value }}
                    </div>
                </div>
                <div class="uk-width-3-4">
                    <div class="uk-grid uk-grid-small" data-uk-grid-margin>
                        <div class="uk-width-1-3">
                            <strong>{{ processed.company.name }}</strong><br/>
                            {{ processed.company.address_1 }}<br/>
                            {{ processed.company.zipcode }} {{ processed.company.city }}<br/>
                            <div v-html="processed.clickpath"></div>
                        </div>
                        <div class="uk-width-2-3">
                            <div class="uk-grid uk-grid-small uk-grid-width-medium-1-3" data-uk-grid-margin>
                                <div class="uk-text-truncate">
                                    {{ processed.company.email }}<br/>
                                    {{ processed.company.data.website }}<br/>
                                    Tel: <a v-phone.auto="processed.company.phone">{{ processed.company.phone }}</a><br/>
                                    KvK: {{ processed.company.data.coc_number }}<br/>
                                </div>
                                <div class="uk-text-truncate">
                                    <a :href="$url.route('admin/contactmanager/company/edit', { id: processed.company.id })"
                                       target="_blank">
                                        <i class="uk-icon-external-link uk-margin-small-right"></i>
                                        {{ processed.company.name }}
                                    </a><br/>
                                    <a :href="getDatacollectiefLink(processed.company)" target="_blank">
                                        <i class="uk-icon-external-link uk-margin-small-right"></i>
                                        Websiteleads
                                    </a>

                                </div>
                                <div>

                                    <input-terms-many taxonomy-name="cm.company.indication"
                                                      :item_id="processed.company.id"></input-terms-many>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </li>
    </ul>

</div>