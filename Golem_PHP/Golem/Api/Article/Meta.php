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
 * Class for accessing meta data for an article via web interface.
 *
 * @property-read Integer $articleid         the artice identifier
 * @property-read String  $headline          the headline
 * @property-read String  $subheadline       the sub headline
 * @property-read String  $abstracttext      the article abstract
 * @property-read Integer $date              the publishing date as unix timestamp
 * @property-read Integer $pages             the number of pages of the article
 * @property-read Array   $leadimg           the properties of the headline image
 * @property-read String  $leadimg['url']    the URL of the image
 * @property-read Integer $leadimg['height'] the height of the image
 * @property-read Integer $leadimg['width']  the height of the image
 *
 * @category  WebInterface
 * @package   Golem.de
 * @author    Alexander Merz <am@golem.de>
 * @copyright 2009 Klaﬂ&Ihlenfeld Verlag GmbH
 * @license   http://api.golem.de/LICENCE.txt BSD Licence
 * @link      http://api.golem.de/
 */
class Golem_Api_Article_Meta
{

    /**
     * Base url of the webservice
     */
    const URL = 'http://api.golem.de/api/article/meta/';

    /**
     * Error code for an invalid article identifier
     */
    const ERROR_INVALID_IDENTIFIER = 32;

    /**
     * Error code that an identifier does not relate to an article
     */
    const ERROR_NOARTICLE = 33;

    /**
     * Holds the meta data of an article
     * @var Array
     */
    protected $article = array();

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

    /**
     * Creates a MetaArticle object
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
     * Accessor for a meta data value
     *
     * @param String $value the name of the meta data value
     *
     * @return Mixed the value
     */
    public function __get($value)
    {

        if (null != $this->idArticle && array_key_exists($value, $this->article)) {

            return $this->article[$value];

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
     * Golem_Request::DATABASE, Golem_Api_Article_Meta::ERROR_INVALID_IDENITIFER
     * and Golem_Api_Article_Meta::ERROR_NOARTICLE.
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

        $request = new Golem_Request($this->key, self::URL.$this->idArticle.'/' );

        if (!$request->doRequest($curlOptions)) {

            throw new Exception($request->getErrorMessage(),
                                $request->getErrorCode());

        }

        $data = $request->getData();

        $this->article['articleid']    = $data['articleid'];
        $this->article['headline']     = $data['headline'];
        $this->article['subheadline']  = $data['subheadline'];
        $this->article['abstracttext'] = $data['abstracttext'];
        $this->article['url']          = $data['url'];
        $this->article['date']         = $data['date'];
        $this->article['pages']        = $data['pages'];
        $this->article['leadimg']      = $data['leadimg'];
        $this->article['images']       = $data['images'];

        return true;

    }

}

?>
