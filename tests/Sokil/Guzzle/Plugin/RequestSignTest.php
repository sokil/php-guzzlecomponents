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
            'key'   => 'Shared secret key used for generating the HMAC variant of the message digest'
        )));
    }
    
    public function testSignGetRequest() 
    {
        $request = $this->_client->get('/search?z=z&y=y&x=x&q=q');
        $request->send();
        
        $this->assertEquals('c77c4d736078f037b44d01d187521b472d19d1bc', $request->getQuery()->get('sign'));
    }
    
    public function testSignPostRequest() 
    {
        $request = $this->_client
            ->post('/search')
            ->setBody(array(
                'z' => 'z',
                'y' => 'y',
                'x' => 'x',
                'q' => 'q',
            ), 'application/x-www-form-urlencoded');
        
        $request->send();
        
        $this->assertEquals('0cb09dd6b92cc5a7e6bcc315879ac24dc229465f', $request->getQuery()->get('sign'));
    }
}