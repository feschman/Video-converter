<?php
/**
 * Copyright (c) 2009, Klaﬂ&Ihlenfeld Verlag GmbH
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *    * Redistributions of source code must retain the above copyright notice,
 *      this list of conditions and the following disclaimer.
 *    * Redistributions in binary form must reproduce the above copyright
 *      notice, this list of conditions and the following disclaimer in the
 *      documentation and/or other materials provided with the distribution.
 *    * Neither the name of the Klaﬂ&Ihlenfeld Verlag GmbH nor the names of its
 *      contributors may be used to endorse or promote products derived from
 *      this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF
 * THE POSSIBILITY OF SUCH DAMAGE.
 *
 * $Id: Request.php 1847 2009-04-28 15:30:44Z am $
 *
 * PHP version 5
 *
 * @category  WebInterface
 * @package   Golem.de
 * @author    Alexander Merz <am@golem.de>
 * @copyright 2009 Klaﬂ&Ihlenfeld Verlag GmbH
 * @license   http://api.golem.de/LICENCE.txt BSD Licence
 * @link      http://api.golem.de/
 */

/**
 * Base class for accessing the golem.de web interface
 *
 * @category  WebInterface
 * @package   Golem.de
 * @author    Alexander Merz <am@golem.de>
 * @copyright 2009 Klaﬂ&Ihlenfeld Verlag GmbH
 * @license   http://api.golem.de/LICENCE.txt BSD Licence
 * @link      http://api.golem.de/
 */
class Golem_Request
{

    /**
     * An error caused by a failing data access
     */
    const ERROR_DATABASE = 1;

    /** 
     * No developer key was provided
     */
    const ERROR_KEYAUTH_NOT_PROVIDED = 2;

    /**
     * Developer key does not looks like one
     */
    const ERROR_KEYAUTH_NOT_VALID = 3;

    /**
     * Authentication of developer key failed
     */
    const ERROR_KEYAUTH_FAILED = 4;
    
    /**
     * Developer key can't be used currently
     */
    const ERROR_KEYAUTH_NOT_ENABLED = 5;
    
    /**
     * Developer key was used too often and reached the access limit
     */
    const ERROR_KEYAUTH_LIMIT_EXCEEDED = 6;

    /**
     * The url to use for request
     * @var String
     */
    protected $url = null;

    /**
     * The additional parameters for the url request
     * @var Array
     */
    protected $params = array();

    /**
     * The decoded JSON data
     * @var Array
     */
    protected $data = null;

    /**
     * The developer access key
     * @var String
     */
    protected $key = null;
    
    /**
     * The error code returned by the request
     * @var Integer
     */
    protected $errorCode = -1;

    /**
     * The error message returned by the request
     * @var String
     */
    protected $errorMessage = '';

    /**
     * Creates a request object
     *
     * @param String $key    your developer key
     * @param String $url    the URL to request
     * @param Array  $params additional parameters for the request
     */
    public function __construct($key = null, $url = null, $params = array())
    {

        $this->url    = $url;
        $this->params = $params;
        $this->key    = $key;

    }

    /**
     * Returns the error code of a failed request.
     *
     * The value returns the error code after a request
     * failed. Except of '0' for a failed HTTP request,
     * the error code is always URL service spezific.
     *
     * If this method is called after a successful operation
     * the return value is not determined.
     *
     * @return Integer the error code
     */
    public function getErrorCode() 
    {

        return $this->errorCode;

    }

    /**
     * Returns the error message of a failed request.
     *
     * The value returns the error message after a request
     * failed. Except for a failed HTTP request,
     * the error message is always URL service spezific.
     *
     * If this method is called after a successful operation
     * the return value is not determined.
     *
     * @return String the error message
     */
    public function getErrorMessage() 
    {

        return $this->errorMessage;

    }

    /**
     * Does the HTTP request.
     *
     * The URL service is called and the
     * result is decoded from JSON
     * into a usable datastructure.
     *
     * If the the method returns true, you can
     * get the data from getData().
     *
     * If the method returns false, you can
     * check the reason by using getErrorCode()
     * and getErrorMessage().
     *
     * @param Array $curlOptions Array of Options passed to curl_setopt()
     *                           the array keys are used as curl option
     *
     * @return Boolean true if request was successful, false if failed
     */
    public function doRequest($curlOptions = array())
    {

        $ps = array();

        foreach ($this->params as $k => $v) {

            $ps[] = $k.'='.urlencode($v);

        }

        $ps[] = 'key='.urlencode($this->key);

        $p = implode('&', $ps);

        if ('' != $p) {

            $p = '?'.$p;

        }

        $url = $this->url.$p;

        $ch = curl_init($url);

        foreach($curlOptions as $k => $v) {

            curl_setopt($ch, $k, $v);

        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $json = curl_exec($ch);

        if (false === $json
            || 0 != curl_errno($ch)
            || "200" != curl_getinfo($ch, CURLINFO_HTTP_CODE)) {

            $this->errorMessage = 'HTTP request failed';
            $this->errorCode    = 0;

            curl_close($ch);

            return false;

        }

        curl_close($ch);

        $data = json_decode($json, true);

        if (null === $data) {
          
            $this->errorMessage = 'Decoding failed';
            $this->errorCode    = 0;

            return false;

        }
        
        if (!isset($data["success"]) || false == $data["success"]) {

            if (isset($data['errorCode'])) {

                $this->errorCode = $data['errorCode'];

            }
            
            if (isset($data['errorMessage'])) {

                $this->errorMessage = $data['errorMessage'];

            }


            return false;

        }


        if (isset($data["data"])) {

            $this->data = $data["data"];

        } 

        return true;

    }
    
    /**
     * Returns the data from the request.
     *
     * The content of the data depends 
     * on the requested service.
     *
     * The returned value is not determined,
     * if the request failed.
     *
     * @return Mixed the request result
     */
    public function getData() 
    {

        return $this->data;

    }

}

?>