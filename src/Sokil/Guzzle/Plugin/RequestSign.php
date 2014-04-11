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
        'additionalParams'  => null,
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
        $query = $event['request']->getQuery();
        
        // add additional data if specified
        if(is_array($this->_options['additionalParams'])) {
            $query->merge($this->_options['additionalParams']);
        }
            
        if($event['request'] instanceof EntityEnclosingRequestInterface) {
            // POST request - sign POST body
            $messageToSign = $event['request']->getBody();
        } else {
            // GET request - sign QUERY_STRING
            $queryArray = $query->getAll();
            ksort($queryArray);
            $messageToSign = http_build_query($queryArray);
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

