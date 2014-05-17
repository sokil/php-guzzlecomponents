<?php

namespace Sokil\Guzzle\Plugin;

class RequestSignTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var \Guzzle\Http\Client
     */
    private $_client;
    
    public function setUp()
    {
        // create client
        $this->_client = new \Guzzle\Http\Client('http://www.bing.com/');
        
        // add request sign plugin
        $this->_client->addSubscriber(new \Sokil\Guzzle\Plugin\RequestSign(array(
            'key'   => 'Shared secret key used for generating the HMAC variant of the message digest',
            'additionalParams' => array(
                'akey' => 'avalue',
            )
        )));
    }
    
    public function testSignGetRequest() 
    {
        $request = $this->_client->get('/search?z=z&y=y&x=x&q=q');
        $request->send();
        
        $this->assertEquals('b36591ff2b8bdda9611b1e136cadc064f3a12e4a', $request->getQuery()->get('sign'));
    }
    
    public function testSignPostRequest() 
    {
        $request = $this->_client
            ->post('/search?z=z&y=y&x=x&q=q')
            ->setBody(array(
                'c' => 'c',
                'b' => 'b',
                'a' => 'a',
                'd' => 'd',
            ), 'application/x-www-form-urlencoded');
        
        $request->send();
        
        $this->assertEquals('611ca71c62848c6fa911bcb6d89fa0b49b90573b', $request->getQuery()->get('sign'));
    }
}