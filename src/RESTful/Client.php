<?php

namespace RESTful;

use RESTful\Exceptions\HTTPError;
use RESTful\Settings;

class Client
{    
    public function __construct($settings_class, $request_class = null)
    {
        $this->request_class = $request_class == null ? '\Httpful\Request' : $request_class;
        $this->settings_class = $settings_class; 
    }
    
    public function get($uri)
    {
    	$settings_class = $this->settings_class;
        $url = $settings_class::$url_root . $uri;
        $request_class = $this->request_class;
        $request = $request_class::get($url);
        return $this->_op($request);
    }    
    
    public function post($uri, $payload)
    {
    	$settings_class = $this->settings_class;
        $url = $settings_class::$url_root . $uri;
        $request_class = $this->request_class;
        $request = $request_class::post($url, $payload, 'json');
        return $this->_op($request);
    }
    
    public function put($uri, $payload)
    {
    	$settings_class = $this->settings_class;
        $url = $settings_class::$url_root . $uri;
        $request_class = $this->request_class;
        $request = $request_class::put($url, $payload, 'json');
        return $this->_op($request);
    }
    
    public function delete($uri)
    {
    	$settings_class = $this->settings_class;
        $url = $settings_class::$url_root . $uri;
        $request_class = $this->request_class;
        $request = $request_class::delete($url);
        return $this->_op($request);
    }
    
    private function _op($request)
    {
    	$settings_class = $this->settings_class;
        $user_agent = $settings_class::$agent . '/' . $settings_class::$version;
        $request->headers['User-Agent'] = $user_agent; 
        if ($settings_class::$api_key != null)
            $request = $request->authenticateWith($settings_class::$api_key , '');
        $request->expects('json');
        $response = $request->sendIt();
        if ($response->hasErrors() || $response->code == 300)
            throw new HTTPError($response);
        return $response; 
    }
}
