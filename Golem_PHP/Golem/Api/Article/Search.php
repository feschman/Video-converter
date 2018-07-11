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
 * $Id: Search.php 1903 2009-05-17 21:16:27Z am $ 
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
 * Class for searching of articles via web interface.
 *
 * @category  WebInterface
 * @package   Golem.de
 * @author    Alexander Merz <am@golem.de>
 * @copyright 2009 Klaﬂ&Ihlenfeld Verlag GmbH
 * @license   http://api.golem.de/LICENCE.txt BSD Licence
 * @link      http://api.golem.de/
 */
class Golem_Api_Article_Search
{

    /**
     * Base url of the webservice 
     */
    const URL =  'http://api.golem.de/api/article/search/';
    
    /**
     * Error code for a word in the search query is too short
     */
    const ERROR_WORD_TOO_SHORT = 12;
    
    /**
     * Error code for a word in the search query is too long
     */
    const ERROR_WORD_TOO_LONG = 13;
    
    /**
     * Error code for a search query too long
     */
    const ERROR_QUERY_TOO_LONG = 14;
    
    /**
     * Error code for too much search words in the search query
     */
    const ERROR_TOO_MUCH_WORDS = 15;

    /**
     * Article date
     * @var Array
     */
    protected $articles = array();

    /**
     * The search query
     * @var String
     */
    protected $query = '';

    /**
     * Number of article records to show with the request = "page"
     * @var Integer
     */
    protected $itemsPerPage = 10;

    /**
     * The "page" to show
     * @var Integer
     */
    protected $startIndex = 1;

    /**
     * The total number of results for a search query
     * @var String
     */
    protected $totalResults = -1;

    /**
     * Creates the ArticleSearch object
     *
     * @param String $key the developer key
     */
    public function __construct($key)
    {

        $this->key = $key;

    }

    /**
     * Sets the query for the search request
     *
     * @param String $query the search query to execute
     *
     * @return void
     */
    public function setQuery($query)
    {

        $this->query = urlencode($query);

    }

    /**
     * Sets the number of articles to get per request
     *
     * @param Integer $itemsPerPage number of articles
     *
     * @return void
     */
    public function setItemsPerPage($itemsPerPage) 
    {

        $this->itemsPerPage = $itemsPerPage;

    }

    /**
     * Returns the number of articles to get per request
     *
     * After a succesful fetch(), the value could be updated
     * to a value actually used by the service.
     *
     * @return Integer the number of items to get per request
     */
    public function getItemsPerPage() 
    {

        return $this->itemsPerPage;

    }

    /**
     * Sets the request page
     *
     * @param Integer $startIndex the page to request
     *
     * @return void
     */
    public function setStartIndex($startIndex) 
    {
      
        $this->startIndex = $startIndex;

    }

    /**
     * Returns the request page
     *
     * After a succesful fetch(), the value could be updated
     * to a value actually used by the service.
     *
     * @return Integer the request page
     */
    public function getStartIndex() 
    {

        return $this->startIndex;

    }

    /**
     * Returns the number of total results from the search query
     *
     * @return Integer the number of results
     */
    public function getTotalResults() 
    {

        return $this->totalResults;

    }

    /**
     * Returns the list of articles.
     *
     * In the array, each entry represents a article record.
     * The keys of record array are:
     * <ul>
     * <li><i>articleid</i></li>
     * <li><i>headline</i></li>
     * <li><i>abstracttext</i></li>
     * <li><i>url</i></li>
     * <li><i>date</i></li>
     * <li><i>leadimg</i> is an array with the keys:
     *   <ul>
     *    <li>url</li>
     *    <li>height</li>
     *    <li>width</li>
     *   </ul>
     * </li>
     * </ul>
     *
     * @return Array
     */
    public function getArticles() 
    {

        return $this->articles;

    }

    /**
     * Does the request to do the search.
     *
     * If the request was succesful, true will be returned,
     * else an exception will be thrown.
     *
     * The possible Exception codes are
     * Golem_Request::DATABASE, Golem_Api_Article_Search::ERROR_WORD_TOO_SHORT,
     * Golem_Api_Article_Search::ERROR_WORD_TOO_LONG, 
     * Golem_Api_Article_Search::ERROR_QUERY_TOO_LONG,
     * Golem_Api_Article_Search::ERROR_TOO_MUCH_WORDS.
     *
     * After a successful request the articles are available
     * from getArticles()
     *
     * @param Array $curlOptions optional options for cUrl
     *
     * @return Boolean true, if request was successful
     */
    public function fetch($curlOptions = array())
    {

        $request = new Golem_Request($this->key, self::URL .
                                    $this->startIndex.'/'.
                                    $this->itemsPerPage.'/'.
                                    $this->query.'/');

        if (!$request->doRequest($curlOptions)) {

            throw new Exception($request->getErrorMessage(),
                                $request->getErrorCode());

        }

        $data = $request->getData();

        $this->articles     = $data['records'];
        $this->totalResults = $data['totalResults'];

        return true;

    }

}

?>