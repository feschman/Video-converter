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
 * $Id: Api_Video_Meta_1.php 2118 2009-07-21 14:44:47Z am $
 */

$includePathGolem  = '';       // path to include files from Golem.de API
$developerKey     = '';           // insert your developer key here

require_once $includePathGolem.'Golem/Request.php';
require_once $includePathGolem.'Golem/Api/Video/Meta.php';

$articles     = array();
$error        = '';

$request = new Golem_Api_Video_Meta($developerKey, 2227);

try {

 $request->fetch();

} catch( Exception $e ) {

 switch($e->getErrorCode()) {

  case Golem_Api_Article_Meta::ERROR_INVALID_IDENTIFIER :
       $error = 'Der Video-Identifier ist fehlerhaft!';
       break;

  default :
        $error = 'Es trat ein interner Fehler auf!';

 }

}

?>

<html>
 <head>
  <title>Meta data video example</title>
 </head>
 <body>
  <?php
    if('' != $error) {
        echo $error;
    } else {
  ?>
  <table border="1">
   <tr>
    <td>Video-Identifier:</td>
    <td><?php echo $request->videoid; ?></td>
   </tr>
   <tr>
    <td>Titel:</td>
    <td><?php echo $request->title; ?></td>
   </tr>
   <tr>
    <td>Spieldauer in Minuten und Sekunden:</td>
    <?php
    
        $min = floor($request->playtime/60);
        $sec = floor($request->playtime - ($min * 60));

        if($sec < 10) {

            $sec = '0'.$sec;
        }

        $playtime = $min.':'.$sec;
    ?>
    <td><?php echo $playtime; ?></td>
   </tr>
   <?php

    if(null != $request->medium && '' != $request->medium['videourl']) {

   ?>
   <tr>
    <td>Normale Auflösung:</td>
    <td><?php echo '<a href="'.$request->medium['videourl'].'">'.$request->medium['videourl'].'</a>'; ?></td>
   </tr>

   <?php

    }

    if(null != $request->high && '' != $request->high['videourl']) {

   ?>
   <tr>
    <td>Hohe Auflösung:</td>
    <td><?php echo '<a href="'.$request->high['videourl'].'">'.$request->high['videourl'].'</a>'; ?></td>
   </tr>

   <?php

    }

    if(null != $request->apple && '' != $request->apple['videourl']) {

   ?>
   <tr>
    <td>Auflösung für spezielle Geräte:</td>
    <td><?php echo '<a href="'.$request->high['videourl'].'">'.$request->apple['videourl'].'</a>'; ?></td>
   </tr>

   <?php

    }

   ?>

  </table>
  <?php
    }
  ?>
 </body>
</html>
