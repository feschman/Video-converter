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
 */

$includePathGolem = '';     // path to include files from Golem.de API
$developerKey     = '';     // insert your developer key here

require_once $includePathGolem.'Golem/Request.php';
require_once $includePathGolem.'Golem/Api/Theme/List.php';
require_once $includePathGolem.'Golem/Api/Theme/Article.php';

$themes     = array();
$articles   = array();
$error      = '';

$requestList = new Golem_Api_Theme_List($developerKey);

try {

 $requestList->fetch();

 $themes = $requestList->getThemes();

 $themeSelected = $themes[0];

} catch( Exception $e ) {

 switch($e->getErrorCode()) {

  default :
        $error = 'Es trat ein interner Fehler auf!';

 }

}

if(isset($_GET['theme']) && '' != $_GET['theme']) {

    $themeSelected = $_GET['theme'];

    $requestArticles = new Golem_Api_Theme_Article($developerKey, $themeSelected, 5);
    
    try {
    
     $requestArticles->fetch();
    
     $articles = $requestArticles->getArticles();
    
    } catch( Exception $e ) {
    
     switch($e->getErrorCode()) {
    
      case Golem_Api_Theme_Article::ERROR_LIMIT :
           $error = 'Es wurden zuwenig oder zuviele Artikel angefordert!';
           break;

      case Golem_Api_Theme_Article::ERROR_NO_THEME :
           $error = 'Themen-Kategorie existiert nicht oder enthält keine Artikel!';
           break;

      default :
            $error = 'Es trat ein interner Fehler auf!';
    
     }

    }

}

?>

<html>
 <head>
  <title>Show theme categories</title>
 </head>
 <body>
  <h1>Theme categories</h1>
  <?php echo $error; ?>
  <form action="Api_Theme_Article_1.php" method="GET">
   <select name="theme">
    <?php

    foreach($themes as $theme) {

        $selected = '';

        if($theme['shortname'] == $themeSelected) {
            $selected = 'selected="selected"';
        }

        echo '<option value="'.$theme['shortname'].'" '.$selected.'>';
        echo $theme['name'];
        echo '</option>';

    }

    ?>
   </select>
   <input type="submit" value="Show articles!">
    
    <?php
        if(0 < count($articles)) {
          
          echo '<ul>';

          foreach($articles as $article) {
            
            echo '<li>';
            echo '<h2>'.$article['headline'].'</h2>';
            echo '<p>'.$article['abstracttext'].'</p>';
            echo '<a href="'.$article['url'].'">mehr...</a>';
            echo '</li>';

          }

          echo '</ul>';

        }
    ?>

 </body>
</html>
