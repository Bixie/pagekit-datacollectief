
<?php
$view->script('datacollectief-datacollectief-index', 'bixie/datacollectief:app/bundle/datacollectief-datacollectief-index.js', ['bixie-pkframework'],
    ['version' => $app->module('bixie/pk-framework')->getVersionKey($app->package('bixie/datacollectief')->get('version'))]);
?>
<div id="datacollectief-index">
    <h1>{{ 'Datacollectief index' | trans }}</h1>
</div>