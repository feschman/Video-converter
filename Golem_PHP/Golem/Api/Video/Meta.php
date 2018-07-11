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
 * $Id: Meta.php 2117 2009-07-21 14:43:17Z am $
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
 * Class for accessing meta data for a video via web interface.
 *
 * @property-read Integer $videoid                   The video identifier
 * @property-read String  $embeddedcode              The HTML code for the embedded videoplayer
 * @property-read String  $pageurl                   The URL of the video specific page on Golem.de
 * @property-read String  $title                     The title of the video
 * @property-read Float   $playtime                  The duration of the video in seconds
 * @property-read Array   $medium                    The data for the video in regular quality
 * @property-read String  $medium['videourl']        The URL to the video file
 * @property-read Integer $medium['height']          The video height in pixel
 * @property-read Integer $medium['width']           The video width in pixel
 * @property-read Integer $medium['size']            The filesize of the video in bytes
 * @property-read String  $medium['mimetype']        The mimetype of the video file
 * @property-read Array   $medium['image']           The teaser image for the video
 * @property-read String  $medium['image']['url']    The image URL
 * @property-read Integer $medium['image']['height'] The image height in pixel
 * @property-read Integer $medium['image']['width']  The image width in pixel
 * @property-read Array   $high                      The data for the video in high quality (Optional)
 * @property-read String  $high['videourl']          The URL to the video file
 * @property-read Integer $high['height']            The video height in pixel
 * @property-read Integer $high['width']             The video width in pixel
 * @property-read Integer $high['size']              The filesize of the video in bytes
 * @property-read String  $high['mimetype']          The mimetype of the video file
 * @property-read Array   $high['image']             The teaser image for the video
 * @property-read String  $high['image']['url']      The image URL
 * @property-read Integer $high['image']['height']   The image height in pixel
 * @property-read Integer $high['image']['width']    The image width in pixel
 * @property-read Array   $apple                     The data for the video encoded for special devices (Optional)
 * @property-read String  $apple['videourl']         The URL to the video file
 * @property-read Integer $apple['height']           The video height in pixel
 * @property-read Integer $apple['width']            The video width in pixel
 * @property-read Integer $apple['size']             The filesize of the video in bytes
 * @property-read String  $apple['mimetype']         The mimetype of the video file
 * @property-read Array   $apple['image']            The teaser image for the video
 * @property-read String  $apple['image']['url']     The image URL
 * @property-read Integer $apple['image']['height']  The image height in pixel
 * @property-read Integer $apple['image']['width']   The image width in pixel
 *
 * @category  WebInterface
 * @package   Golem.de
 * @author    Alexander Merz <am@golem.de>
 * @copyright 2009 Klaﬂ&Ihlenfeld Verlag GmbH
 * @license   http://api.golem.de/LICENCE.txt BSD Licence
 * @link      http://api.golem.de/
 */
class Golem_Api_Video_Meta
{

    /**
     * Base url of the webservice
     */
    const URL = 'http://api.golem.de/api/video/meta/';

    /**
     * Error code for an invalid video identifier
     */
    const ERROR_INVALID_IDENTIFIER = 32;

    /**
     * Holds the meta data of a the video
     * @var Array
     */
    protected $video = array();

    /**
     * Video identifier
     * @var Integer
     */
    protected $idVideo = null;

    /**
     * Developer access key
     * @var String
     */
    protected $key = null;

    /**
     * Creates a Video_Meta object
     *
     * @param String  $key the developer key
     * @param Integer $id  the video identifier
     */
    public function __construct($key, $id)
    {

        $this->key       = $key;
        $this->idVideo   = $id;

    }

    /**
     * Accessor for a meta data value
     *
     * @param String $value the name of the meta data value
     *
     * @return Mixed the value
     */
    public function __get($value)
    {

        if (null != $this->idVideo && array_key_exists($value, $this->video)) {

            return $this->video[$value];

        }

        return null;

    }

    /**
     * Does the request to get the meta data.
     *
     * If the request was succesful, true will be returned,
     * else an exception will be thrown.
     *
     * The possible Exception codes are
     * Golem_Request::DATABASE, Golem_Api_Video_Meta::ERROR_INVALID_IDENITIFER
     *
     * After a successful request the meta data is available
     * as properties of the class instance
     *
     * @param Array $curlOptions optional options for cUrl
     *
     * @return Boolean true, if request was successful
     */
    public function fetch($curlOptions = array())
    {

        $request = new Golem_Request($this->key, self::URL.$this->idVideo.'/' );

        if (!$request->doRequest($curlOptions)) {

            throw new Exception($request->getErrorMessage(),
                                $request->getErrorCode());

        }

        $data = $request->getData();

        $this->video = $data;

        return true;

    }

}

?>
