
<?php
$view->script('datacollectief-datacollectief-index', 'bixie/datacollectief:app/bundle/datacollectief-datacollectief-index.js', ['bixie-pkframework'],
    ['version' => $app->module('bixie/pk-framework')->getVersionKey($app->package('bixie/datacollectief')->get('version'))]);
?>
<div id="datacollectief-index">
    <h1>{{ 'Datacollectief API' | trans }}</h1>

    <div class="uk-panel uk-panel-box uk-form uk-form-stacked">
        <div class="uk-grid uk-grid-width-medium-1-3" data-uk-grid-margin>
            <div>
                <label class="uk-form-label">{{ 'Check from' | trans }}</label>
                <div class="uk-form-controls">
                    <input-date-bix :datetime.sync="wl_options.From"></input-date-bix>
                </div>
            </div>
            <div>
                <label class="uk-form-label">{{ 'Check until' | trans }}</label>
                <div class="uk-form-controls">
                    <input-date-bix :datetime.sync="wl_options.To"></input-date-bix>
                </div>
            </div>
            <div>
                <label class="uk-form-label">{{ 'Website' | trans }}</label>
                <div class="uk-form-controls uk-flex uk-flex-middle uk-flex-space-between">
                    <select v-model="wl_options.Website" class="uk-width-8-10">
                        <option value="">{{ 'Select website' | trans }}</option>
                        <option v-for="website in websites" :value="website">{{ website }}</option>
                    </select>
                    <a v-spinner="loading" icon="refresh" spinner="refresh" @click="getWebsiteleads"></a>
                </div>
            </div>
        </div>

    </div>


    {{ leads | json }}

</div>