Guzzle Components
=================

Signing request
---------------

This plugin used to sign request on client. For example server gives access to
 API for that applications who knows "Application ID" and corresponding "Key".

Guzzle client must add configured plugin:
```php
$client->addSubscriber(new \Sokil\Guzzle\Plugin\RequestSign(array(
    'key'               => $key,
    'algo'              => 'sha1',
    'queryParamName'    => 'sign',
    'additionalParams'  => [
        'app_id'    => $applicationId,
    ]
)));
```

