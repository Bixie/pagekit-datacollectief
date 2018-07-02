# Bixie Datacollectief

Data-collectief API Client for Pagekit.

https://api.datacollectief.nl/Help

### Usage

Easiest to use directly via Pagekit API in Vue components. For examples, see API-calls in [settings.js](/app/views/admin/settings.js).
All entrypoints are in the [DatacollectiefApiController](/src/Controller/DatacollectiefApiController.php) and can easily be found in the Pagekit Debugbar Routes section.


The API itself can be used on its own as well:

```php
    use Bixie\Datacollectief\Api\Api as DatacollectiefApi;

    $api = new DatacollectiefApi([
        'api_url' => 'https://api.datacollectief.nl/api/', 
        'application_name' => 'Datacollectief.API', 
        'user' => 'user@domain.com', 
        'password' => 'xxxxx',
    ]);
    
    //The API will return the expected data array or will throw a DatacollectiefApiException
    try {
        $data = $api->version();
        $version = $data['Version'];
        
        echo 'API version: ' . $version;
        
    } catch (DatacollectiefApiException $e) {
        //handle error
        //(Response)errorcode: $e->getCode()
        //Errormessage: $e->getMessage()
    }

```

All endpoints can be found in the Traits in the [Api folder](/src/Api).

### License

MIT.