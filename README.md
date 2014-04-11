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

| Parameter | Type | Required | Default value	| Description |
|---	|--- |--- |---	|---	|
| key | string | required || Key which used to crypt message |
| algo | string | optional | sha1 | Crypt algorythm |
| queryParamName | string | optional | sign | Name of query string parameter where signatupe passed	|
| additionalParams | array | optional || Parameters, additionaly send in query string and signed if request passed through GET method |

