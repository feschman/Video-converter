<?php
/*
 * Copyright (c) 2009, Kla�&Ihlenfeld Verlag GmbH
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
 *    * Neither the name of the Kla�&Ihlenfeld Verlag GmbH nor the names of its
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
 * $Id: Api_Article_Top_1.php 1849 2009-04-28 16:02:13Z am $ 
 */

$includePathGolem = '';     // path to include files from Golem.de API
$developerKey     = '';     // insert your developer key here

require_once $includePathGolem.'Golem/Request.php';
require_once $includePathGolem.'Golem/Api/Article/Top.php';

$articles     = array();
$error        = '';

$request = new Golem_Api_Article_Top($developerKey, 5);

try {

 $request->fetch();

 $articles = $request->getArticles();

} catch( Exception $e ) {

 switch($e->getErrorCode()) {

  case Golem_Api_Article_Top::ERROR_LIMIT :
       $error = 'Es wurden zuwenig oder zuviele Artikel angefordert!';
       break;

  default :
        $error = 'Es trat ein interner Fehler auf!';

 }

}
?>

<html>
 <head>
  <title>Top article example</title>
 </head>
 <body>
  <?php echo $error; ?>
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
 </body>
</html>
