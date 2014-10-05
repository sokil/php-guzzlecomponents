Guzzle 3 Components
=================

Installation
------------

Installation can be made through Composer:
```
require: {
    "sokil/php-guzzlecomponents": "dev-master"
}
```

Signing request
---------------

This plugin used to sign request on client. For example server gives access to
 API for that applications who knows "Application ID" and corresponding "Key".

Guzzle client must add configured plugin:
```php
$client->addSubscriber(new \Sokil\Guzzle\Plugin\RequestSign(array(
    'key'               => $cryptKey,
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

Algorithm of validation signed request on server:

```php
// check if fields passed in query
if(empty($_GET['sign']) || empty($_GET['app_id']) {
    Header('HTTP/1.0 403 Forbidden');
    exit;
}

// get crypt key from storage by application id
$applicationId = $_GET['app_id'];
$cryptKey = get_crypt_key($applicationId);

// get message
if('POST' === $_SERVER['REQUEST_METHOD']) {
    $body = file_get_contents('php://input');
} else {
    $body = $_GET;
    // sign key not crypted so it must be unset from message
    unset($body['sign']);
    // params must be sorted
    ksort($body);
    // query gathered to string
    $body = http_build_query($body);
}

// calculate and compare sign with passed
return ($_GET['sign'] === hash_hmac('sha1', $body, $cryptKey));
```

