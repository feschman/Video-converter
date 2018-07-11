<?php
/**
 * Copyright (c) 2010, Klaﬂ&Ihlenfeld Verlag GmbH
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
 * $Id$
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
 * Class for accessing the list of special categories via web interface.
 *
 * @category  WebInterface
 * @package   Golem.de
 * @author    Alexander Merz <am@golem.de>
 * @copyright 2009 Klaﬂ&Ihlenfeld Verlag GmbH
 * @license   http://api.golem.de/LICENCE.txt BSD Licence
 * @link      http://api.golem.de/
 */
class Golem_Api_Special_List
{
  
    /**
     * Base url of the webservice
     */
    const URL = 'http://api.golem.de/api/special/list/';

    /**
     * special category data
     * @var Array
     */
    protected $specials = array();

    /**
     * Developer access key
     * @var String
     */
    protected $key = null;

    /**
     * Creates the Special_List object
     *
     * @param String $key your developer key
     */
    public function __construct($key)
    {

        $this->key = $key;

    }

    /**
     * Does the request to get the list of special categories.
     *
     * If the request was succesful, true will be returned,
     * else an exception will be thrown.
     *
     * The possible Exception code is
     * Golem_Request::DATABASE.
     *
     * After a successful request the special category list is available
     * from getSpecials().
     *
     * @param Array $curlOptions optional options for cUrl
     *
     * @return Boolean true, if request was successful
     */
    public function fetch($curlOptions = array())
    {

        $request = new Golem_Request($this->key, self::URL);

        if (!$request->doRequest($curlOptions)) {

            throw new Exception($request->getErrorMessage(), 
                                $request->getErrorCode());

        }

        $this->specials = $request->getData();

        return true;
    }

    /**
     * Returns the list of special categories.
     *
     * In the array, each entry represents a special category record.
     * The keys of record array are:
     * <ul>
     * <li>name - String - special category name</li>
     * <li>shortname - String - short special category name, used as identifier</li>
     * <li>url - String - URL to the special category name</li>
     * </ul>     
     *
     * @return Array
     */
    public function getSpecials()
    {

        return $this->specials;

    }


}


?>