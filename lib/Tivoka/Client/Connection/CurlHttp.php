<?php
/**
 * Tivoka - JSON-RPC done right!
 * Copyright (c) 2011-2012 by Marcel Klehr <mklehr@gmx.net>
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package  Tivoka
 * @author Marcel Klehr <mklehr@gmx.net>
 * @author Rafał Wrzeszcz <rafal.wrzeszcz@wrzasq.pl>
 * @copyright (c) 2011-2012, Marcel Klehr
 */

namespace BugBuster\Tivoka\Client\Connection;
use BugBuster\Tivoka\Client\BatchRequest;
use BugBuster\Tivoka\Exception;
use BugBuster\Tivoka\Client\Request;

/**
 * HTTP connection over cURL
 * @package Tivoka
 */
class CurlHttp extends AbstractConnection {

    public $target;
    public $headers = array();

    /**
     * Constructs connection
     * @access private
     * @param string $target URL
     */
    public function __construct($target) {
        //validate url...
        if (!filter_var($target, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED)) {
            throw new Exception\Exception('Valid URL (scheme://domain[/path][/file]) required.');
        }

        //validate scheme...
        $t = parse_url($target);
        if (strtolower($t['scheme']) != 'http' && strtolower($t['scheme']) != 'https') {
            throw new Exception\Exception('Unknown or unsupported scheme given.');
        }

        $this->target = $target;
    }

    /**
     * Sets the HTTP headers to use for upcoming send requests
     * @param string label of header
     * @param string value of header
     * @return Http Self instance
     */
    public function setHeader($label, $value) {
        $this->headers[$label] = $value;
        return $this;
    }
    
    /**
     * Sends a JSON-RPC request
     * @param Request $request A Tivoka request
     * @return Request if sent as a batch request the BatchRequest object will be returned
     */
    public function send(Request $request) 
    {
        if ( func_num_args() > 1 ) 
        {
            $request = func_get_args();
        }
        if ( is_array($request) ) 
        {
            $request = new BatchRequest($request);
        }
    
        if ( !($request instanceof Request) ) 
        {
            throw new Exception\Exception('Invalid data type to be sent to server');
        }
    
        // Build the cURL session
        $curl = curl_init("{$this->target}");
        $options = array(
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_FOLLOWLOCATION => TRUE,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_HTTPHEADER => array('Content-type: application/json'),
                CURLOPT_POST => TRUE,
                CURLOPT_POSTFIELDS => $request->getRequest($this->spec),
                CURLOPT_USERAGENT => 'Tivoka/3.4.0 (easyUpdate3 c_url)' //curl = Bot!
        );// Agent scheint trotzdem bei json nicht in der access.log aufzutauchen
        
        curl_setopt_array($curl, $options);
        
        if (isset($this->options['ssl_verify_peer']))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $this->options['ssl_verify_peer']);
        }
        
        // Execute the request and decode to an array
        $raw_response = curl_exec($curl);
        if($raw_response === FALSE) 
        {
            throw new Exception\ConnectionException('Connection to "'.$this->target.'" failed');
        }
        //$this->response = json_decode($this->raw_response, TRUE);
        $request->setResponse($raw_response);

        /*
        // If the status is not 200, something is wrong
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($status == 200) 
        {
        	//TODO Auswerten, ConnectException werfen
        }
        
         */
        
        curl_close($curl);

        return $request;
    }
}
