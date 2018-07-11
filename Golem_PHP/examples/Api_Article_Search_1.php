<?php
/*
 * Copyright (c) 2009, Klaß&Ihlenfeld Verlag GmbH
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
 *    * Neither the name of the Klaß&Ihlenfeld Verlag GmbH nor the names of its
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
 * $Id: Api_Article_Search_1.php 1849 2009-04-28 16:02:13Z am $
 */

$includePathGolem = '';     // path to include files from Golem.de API
$developerKey     = '';     // insert your developer key here

require_once $includePathGolem.'Golem/Request.php';
require_once $includePathGolem.'Golem/Api/Article/Search.php';

$query    = '';
$page     = 1;
$articles = array();

$backElement = '&nbsp;';
$nextElement = '&nbsp;';

$error = '';

if (isset($_GET['query']) && '' != $_GET['query']) {

    $query = $_GET['query'];

}

if (isset($_GET['page']) && preg_match('/\d+/', $_GET['page'])) {

    $page = $_GET['page'];

}

if ('' != $query) {

    $request = new Golem_Api_Article_Search($developerKey);

    $request->setQuery($query);
    $request->setStartIndex($page);
    $request->setItemsPerPage(10);

    try {

        $request->fetch();

        $articles = $request->getArticles();
        
        $baseLink = $_SERVER['PHP_SELF'].'?query='.$query.'&page=';

        if (0 < ($request->getStartIndex() - 1)) {

            $backElement = '<a href="'.$baseLink.($request->getStartIndex() - 1).'">Previous page</a>';

        }

        if($request->getTotalResults() > ($request->getStartIndex() * $request->getItemsPerPage())) {

            $nextElement = '<a href="'.$baseLink.($request->getStartIndex() + 1).'">Next page</a>';

        }

    } catch (Exception $e) {
      
        $error = $e->getMessage();

    }


}

?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>Golem.de Article search example</title>
    </head>
    <body>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            Suchbegriff: <input type="text" name="query" value="<?php echo $query; ?>">
            <input type="submit" value="Suchen">
        </form>
        
        <?php echo $error; ?>

        <?php
            if (0 < count($articles)) {
        ?>

            <ul>

            <?php

              foreach($articles as $article) {

                echo '<li>';
                echo '<h1>'.$article['headline'].'</h1>';
                echo '<p>'.$article['abstracttext'].'</p>';
                echo '<a href="'.$article['url'].'">mehr...</a>';
                echo '</li>';

              }

            ?>

            </ul>

            <span style="float:left;">
            
                <?php echo $backElement; ?>

            </span>
            <span style="float:right;">

                <?php echo $nextElement; ?>

            </span>

        <?php

            }
            
        ?>

    </body>
</html>