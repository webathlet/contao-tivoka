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
 * @author Glen Langer
 * @copyright (c) 2015, Glen Langer
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
        $max_redirects = 10;
        
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
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt($curl, CURLOPT_POST      , true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request->getRequest($this->spec));
        curl_setopt($curl, CURLOPT_USERAGENT , 'Tivoka/3.4.0 (easyUpdate3 c_url)'); //curl = Bot!
        
        if (isset($this->options['ssl_verify_peer']))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $this->options['ssl_verify_peer']);
        }
        
        if (($met = ini_get('max_execution_time')) > 0) 
        {
            curl_setopt($curl, CURLOPT_TIMEOUT, round($met * 0.9));
        }
        
        
        //Fixed #3, the Horror Workaround for CURLOPT_FOLLOWLOCATION bug 
        //          with open_basedir or safe_mode restriction enabled.
        if (ini_get('open_basedir') === '' && ini_get('safe_mode' === 'Off')) 
        {
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_MAXREDIRS     , $max_redirects);
            $raw_response = curl_exec($curl);
        }
        else 
        {
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
            $mr = $max_redirects;
            if ($mr > 0)
            {
                $newurl = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
                	
                $rcurl = curl_copy_handle($curl);
                curl_setopt($rcurl, CURLOPT_HEADER, true);
                curl_setopt($rcurl, CURLOPT_NOBODY, true);
                curl_setopt($rcurl, CURLOPT_FORBID_REUSE, false);
                curl_setopt($rcurl, CURLOPT_RETURNTRANSFER, true);
                do 
                {
                    curl_setopt($rcurl, CURLOPT_URL, $newurl);
                    $header = curl_exec($rcurl);
                    if (curl_errno($rcurl)) 
                    {
                        $code = 0;
                    } 
                    else 
                    {
                        $code = curl_getinfo($rcurl, CURLINFO_HTTP_CODE);
                        if ($code == 301 || $code == 302 || $code == 307) 
                        {
                            preg_match('/Location:(.*?)\n/i', $header, $matches);
                            $newurl = trim(array_pop($matches));
                        } 
                        else 
                        {
                            $code = 0;
                        }
                    }
                } while ($code && --$mr);
                curl_close($rcurl);
                if ($mr > 0) 
                {
                    curl_setopt($curl, CURLOPT_URL, $newurl);
                }
                
            }// $mr > 0
            
            if($mr == 0 && $max_redirects > 0) 
            {
                $raw_response = false;
            } 
            else 
            {
                // Execute the request and decode to an array
                $raw_response = curl_exec($curl);
            }
        }

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
