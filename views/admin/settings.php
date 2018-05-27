
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
                </ul>

            </div>

        </div>
        <div class="pk-width-content">

            <ul id="tab-content" class="uk-switcher uk-margin">
                <li class="uk-form uk-form-horizontal">

                    <div class="uk-margin uk-flex uk-flex-space-between uk-flex-wrap" data-uk-margin>
                        <div data-uk-margin>

                            <h2 class="uk-margin-remove">{{ 'Datacollectief Settings' | trans }}</h2>

                        </div>
                        <div data-uk-margin>

                            <button class="uk-button uk-button-primary" @click="save">{{ 'Save' | trans }}</button>

                        </div>
                    </div>

                    <bixie-fields :config="$options.fields.settings" :values.sync="config"></bixie-fields>

                    <hr/>

                    <div class="uk-form-row">
                        <label class="uk-form-label">{{ 'Websiteleads last checked' | trans }}</label>
                        <div class="uk-form-controls">
                            <input-date-bix :datetime.sync="config.wl_last_checked"></input-date-bix>
                        </div>
                    </div>

                    <div class="uk-form-row">
                        <label class="uk-form-label">{{ 'Websiteleads tag' | trans }}</label>

                        <div class="uk-form-controls">
                            <select v-model="config.wl_tag" class="uk-form-width-medium">
                                <option value="">{{ 'Select tag' | trans }}</option>
                                <option v-for="tag in indications" :value="tag.slug">{{ tag.title }}</option>
                            </select>
                        </div>
                    </div>

                </li>
            </ul>

        </div>

    </div>
</div>