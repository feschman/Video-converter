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
 * $Id: Top.php 1903 2009-05-17 21:16:27Z am $ 
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
 * Class for accessing a list of top videos via web interface.
 *
 * @category  WebInterface
 * @package   Golem.de
 * @author    Alexander Merz <am@golem.de>
 * @copyright 2009 Klaﬂ&Ihlenfeld Verlag GmbH
 * @license   http://api.golem.de/LICENCE.txt BSD Licence
 * @link      http://api.golem.de/
 */
class Golem_Api_Video_Top
{

    /**
     * Base url of the webservice 
     */
    const URL = 'http://api.golem.de/api/video/top/';

    /**
     * Error code for an invalid limit value
     */
    const ERROR_LIMIT = 52;

    /**
     * Amount of top articles to fetch
     * @var Integer
     */
    protected $maxVideos = 5;

    /**
     * Top video data
     * @var Array
     */
    protected $videos = array();

    /**
     * Developer access key
     * @var String
     */
    protected $key = null;

    /**
     * Creates the TopVideo object
     *
     * @param String  $key       your developer key
     * @param Integer $maxVideos the number of top videoes to fetch
     */
    public function __construct($key, $maxVideos = 5) 
    {

        $this->key       = $key;
        $this->maxVideos = $maxVideos;

    }

    /**
     * Does the request to get the top list.
     *
     * If the request was succesful, true will be returned,
     * else an exception will be thrown.
     *
     * The possible Exception codes are
     * Golem_Request::DATABASE and Golem_Api_Video_Top::ERROR_LIMIT.
     *
     * After a successful request the video list is available
     * from getVideos().
     *
     * @param Array $curlOptions optional options for cUrl
     *
     * @return Boolean true, if request was successful
     */
    public function fetch($curlOptions = array())
    {

        $request = new Golem_Request($this->key, self::URL.$this->maxVideos.'/');

        if (!$request->doRequest($curlOptions)) {

            throw new Exception($request->getErrorMessage(),
                                $request->getErrorCode());

        }

        $this->videos = $request->getData();

        return true;
    }

    /**
     * Returns the list of videos.
     *
     * In the array, each entry represents a video record.
     * The keys of record array are:
     * <ul>
     * <li>videoid - Integer - Video identifier</li>
     * <li>title - String - the video title</li>
     * <li>url - String - the URL to the video page</li>
     * </ul>
     *
     * @return Array
     */
    public function getVideos() 
    {

        return $this->videos;

    }

}

?>