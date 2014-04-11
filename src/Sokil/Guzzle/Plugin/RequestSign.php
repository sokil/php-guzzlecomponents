<?php

namespace Sokil\Guzzle\Plugin;

use \Symfony\Component\EventDispatcher\EventSubscriberInterface;
use \Guzzle\Common\Event;
use \Guzzle\Http\Message\EntityEnclosingRequestInterface;

/**
 * Usage:
 * $client->addSubscriber(new \Sokil\Guzzle\Plugin\RequestSign(array(
 *     'key'   => $key,
 * )));
 */
class RequestSign implements EventSubscriberInterface
{
    /**
     * Default options
     * @var type 
     */
    private $_options = array(
        'algo'              => 'sha1',
        'key'               => null,
        'queryParamName'    => 'sign',
    );
    
    public function __construct(array $options)
    {
        $this->_options = array_merge($this->_options, $options);
    }
    
    public static function getSubscribedEvents()
    {
        return array(
            'request.before_send' => array('onRequestBeforeSend', -1000)
        );
    }
    
    public function onRequestBeforeSend(Event $event)
    {
        // POST request - sign POST body
        if($event['request'] instanceof EntityEnclosingRequestInterface) {
            $messageToSign = $event['request']->getBody();
        }
        // GET request - sign QUERY_STRING
        else {
            $query = $event['request']->getQuery()->getAll();
            ksort($query);
            $messageToSign = http_build_query($query);
        }
        
        // get digest
        $digest = hash_hmac(
            $this->_options['algo'],
            $messageToSign,
            $this->_options['key']
        );
        
        $event['request']->getQuery()->set($this->_options['queryParamName'], $digest);
    }
    
    
}

