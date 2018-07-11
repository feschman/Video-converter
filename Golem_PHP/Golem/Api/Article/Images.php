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
 * $Id: Meta.php 1903 2009-05-17 21:16:27Z am $
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
 * Class for accessing the images associated with an article via web interface.
 *
 * @category  WebInterface
 * @package   Golem.de
 * @author    Alexander Merz <am@golem.de>
 * @copyright 2009 Klaﬂ&Ihlenfeld Verlag GmbH
 * @license   http://api.golem.de/LICENCE.txt BSD Licence
 * @link      http://api.golem.de/
 */
class Golem_Api_Article_Images implements Iterator, Countable
{

    /**
     * Base url of the webservice
     */
    const URL = 'http://api.golem.de/api/article/images/';

    /**
     * Error code for an invalid article identifier
     */
    const ERROR_INVALID_IDENTIFIER = 10;

    /**
     * Holds the image list data
     * @var Array
     */
    protected $result = array();

    /**
     * Article identifier
     * @var Integer
     */
    protected $idArticle = null;

    /**
     * Developer access key
     * @var String
     */
    protected $key = null;


    private $counter = 0;

    /**
     * Creates a Article_Images object
     *
     * @param String  $key the developer key
     * @param Integer $id  the article identifier
     */
    public function __construct($key, $id)
    {

        $this->key       = $key;
        $this->idArticle = $id;

    }

    /**
     * Does the request to get the list of images of an article.
     *
     * If the request was succesful, true will be returned,
     * else an exception will be thrown.
     *
     * The possible Exception codes are
     * Golem_Request::DATABASE and Golem_Api_Article_Meta::ERROR_INVALID_IDENITIFER
     *
     * After a successful request the image data is available
     * via the getImages method.
     *
     * @param Array $curlOptions optional options for cUrl
     *
     * @return Boolean true, if request was successful
     */
    public function fetch($curlOptions = array())
    {

        $request = new Golem_Request($this->key, self::URL.$this->idArticle.'/' );

        if (!$request->doRequest($curlOptions)) {

            throw new Exception($request->getErrorMessage(),
                                $request->getErrorCode());

        }

        $this->result  = $request->getData();
        $this->counter = 0;

        return true;

    }

    /**
     * Returns the number of images in an article.
     *
     * @return int the number of images
     */
    public function count() {

        return count($this->result);

    }

    /**
     * Returns the list of images.
     *
     * Each entry in the array holds the data for an image:
     * <ul>
     * <li><i>imageid</i> - {Integer} - The image identifier. The identifier should be not expected to be unique! </li>
     * <li><i>subtext</i> - {String} - A text associated with the image. </li>
     * <li><i>small</i> - {Array} - The data for the image as thumbnail. The typical size of an thumbnail is 120x90 pixel.
     *    <ul>
     *      <li><i>url</i> - {String} - The image url</li>
     *      <li><i>width</i> - {Integer} - The image width in pixel </li>
     *      <li><i>height</i> - {Integer} - The image height in pixel </li>
     *    </ul>
     * </li>
     * <li><i>medium</i> - {Array} - The data for the image in a medium size. The maximum width of a medium size image is 480 pixel. The height may differ.
     *    <ul>
     *      <li><i>url</i> - {String} - The image url</li>
     *      <li><i>width</i> - {Integer} - The image width in pixel </li>
     *      <li><i>height</i> - {Integer} - The image height in pixel </li>
     *    </ul>
     * </li>
     * <li><i>native</i> - {Array} - The data for the image. The size of the image is not limited, but a size of 1024x786 is exceeded in rare situations only.
     *    <ul>
     *      <li><i>url</i> - {String} - The image url</li>
     *      <li><i>width</i> - {Integer} - The image width in pixel </li>
     *      <li><i>height</i> - {Integer} - The image height in pixel </li>
     *    </ul>
     * </li>
     * </ul>
     *
     * @return Array the list of images
     */
    public function getImages() {
      
        return $this->result;

    }

    /**
     * Sets the internal counter to 0
     */
    public function rewind() {

        $this->counter = 0;

    }
    
    /**
     * Returns the current image entry.
     *
     * An entry contains:
     *
     * <ul>
     * <li><i>imageid</i> - {Integer} - The image identifier. The identifier should be not expected to be unique! </li>
     * <li><i>subtext</i> - {String} - A text associated with the image. </li>
     * <li><i>small</i> - {Array} - The data for the image as thumbnail. The typical size of an thumbnail is 120x90 pixel.
     *    <ul>
     *      <li><i>url</i> - {String} - The image url</li>
     *      <li><i>width</i> - {Integer} - The image width in pixel </li>
     *      <li><i>height</i> - {Integer} - The image height in pixel </li>
     *    </ul>
     * </li>
     * <li><i>medium</i> - {Array} - The data for the image in a medium size. The maximum width of a medium size image is 480 pixel. The height may differ.
     *    <ul>
     *      <li><i>url</i> - {String} - The image url</li>
     *      <li><i>width</i> - {Integer} - The image width in pixel </li>
     *      <li><i>height</i> - {Integer} - The image height in pixel </li>
     *    </ul>
     * </li>
     * <li><i>native</i> - {Array} - The data for the image. The size of the image is not limited, but a size of 1024x786 is exceeded in rare situations only.
     *    <ul>
     *      <li><i>url</i> - {String} - The image url</li>
     *      <li><i>width</i> - {Integer} - The image width in pixel </li>
     *      <li><i>height</i> - {Integer} - The image height in pixel </li>
     *    </ul>
     * </li>
     * </ul>
     *
     * @return Array 
     */
    public function current() {

        return $this->result[$this->counter];

    }
    
    /**
     * Returns the current key
     *
     * @return int
     */
    public function key() {

        return $this->counter;

    }
    
    /**
     * Moves the internal counter to the next entry
     * in the image list
     */
    public function next() {

        ++$this->counter;

    }

    /**
     * Checks if the current counter refers to an existing entry
     */
    public function valid() {
      
        return isset($this->result[$this->counter]);

    }


}

?>
