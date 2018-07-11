<?php
/**
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
 * $Id: GolemApiVideoMetaTest.php 2124 2009-07-21 15:22:17Z am $
 *
 * PHP version 5
 *
 * @category  WebInterfaceTest
 * @package   Golem.de_Test
 * @author    Alexander Merz <am@golem.de>
 * @copyright 2009 Klaß&Ihlenfeld Verlag GmbH
 * @license   http://api.golem.de/LICENCE.txt BSD Licence
 * @link      http://api.golem.de/
 */

require_once 'PHPUnit/Framework.php';

require_once '../Golem/Request.php';
require_once '../Golem/Api/Video/Meta.php';

class GolemApiVideoMetaTest extends PHPUnit_Framework_TestCase
{

    const API_KEY = 'YOU_DEV_KEY'; // insert your developer key here

    protected $request = null;

    protected $videoId = 123456;

    public function setUp() {

        $this->request = new Golem_Api_Video_Meta(self::API_KEY, $this->videoId);

    }
    
    public function testData() {
      
        $result = $this->request->fetch();
        
        $this->assertTrue($result);

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_INT,
                          $this->request->videoid);
        $this->assertEquals($this->videoId, $this->request->videoid);

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_STRING,
                          $this->request->title);
        $this->assertEquals('Lorem ipsum dolor sit amet consectetuer '.
                            'sadipscing elitr sed',
                            $this->request->title);

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_STRING,
                          $this->request->pageurl);
        $this->assertEquals('http://video.golem.de/',
                            $this->request->pageurl);

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_STRING,
                          $this->request->embeddedcode);
        $this->assertEquals('<object width="480" height="270">'.
                                '<param name="movie" value="http://video.golem.de/player/videoplayer.swf?id=123456&autoPl=false"></param>'.
                                '<param name="allowFullScreen" value="true"></param>'.
                                '<param name="AllowScriptAccess" value="always">'.
                                    '<embed src="http://video.golem.de/player/videoplayer.swf?id=123456&autoPl=false"'.
                                        'type="application/x-shockwave-flash" allowfullscreen="true" AllowScriptAccess="always" '.
                                        'width="480" height="270"></embed> '.
                            '</object>'.
                            '<div style="width:480px; text-align:center; font-family:verdana,sans-serif; font-size:0.8em;">'.
                                '<a href="http://video.golem.de/">'.
                                 'Video: Beispielvideo'.
                                '</a>&nbsp;(0:0)'.
                            '</div>',
                            $this->request->embeddedcode);

        $medium = $this->request->medium;
        $high   = $this->request->high;
        $apple  = $this->request->apple;

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_STRING,
                          $medium['videourl']);
        $this->assertEquals('http://api.golem.de/video/Golemvideo_medium.flv', $medium['videourl']);

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_INT,
                          $medium['height']);
        $this->assertEquals(270, $medium['height']);

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_INT,
                          $medium['width']);
        $this->assertEquals(480, $medium['width']);

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_INT,
                          $medium['size']);
        $this->assertEquals(210055, $medium['size']);

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_STRING,
                          $medium['mimetype']);
        $this->assertEquals('video/x-flv', $medium['mimetype']);

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_ARRAY,
                          $medium['image']);

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_INT,
                          $medium['image']['width']);
        $this->assertEquals(480, $medium['image']['width']);

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_INT,
                          $medium['image']['height']);
        $this->assertEquals(270, $medium['image']['height']);

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_STRING,
                          $medium['image']['url']);
        $this->assertEquals('http://api.golem.de/video/teaser_video.png', $medium['image']['url']);



        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_STRING,
                          $high['videourl']);
        $this->assertEquals('http://api.golem.de/video/Golemvideo_HD.mp4', $high['videourl']);

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_INT,
                          $high['height']);
        $this->assertEquals(540, $high['height']);

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_INT,
                          $high['width']);
        $this->assertEquals(960, $high['width']);

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_INT,
                          $high['size']);
        $this->assertEquals(715044, $high['size']);

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_STRING,
                          $high['mimetype']);
        $this->assertEquals('video/quicktime', $high['mimetype']);

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_ARRAY,
                          $high['image']);

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_INT,
                          $high['image']['width']);
        $this->assertEquals(480, $high['image']['width']);

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_INT,
                          $high['image']['height']);
        $this->assertEquals(270, $high['image']['height']);

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_STRING,
                          $high['image']['url']);
        $this->assertEquals('http://api.golem.de/video/teaser_video.png', $high['image']['url']);


        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_STRING,
                          $apple['videourl']);
        $this->assertEquals('http://api.golem.de/video/Golemvideo_iPod.m4v', $apple['videourl']);

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_INT,
                          $apple['height']);
        $this->assertEquals(360, $apple['height']);

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_INT,
                          $apple['width']);
        $this->assertEquals(640, $apple['width']);

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_INT,
                          $apple['size']);
        $this->assertEquals(220576, $apple['size']);

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_STRING,
                          $apple['mimetype']);
        $this->assertEquals('video/quicktime', $apple['mimetype']);

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_ARRAY,
                          $apple['image']);

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_INT,
                          $apple['image']['width']);
        $this->assertEquals(480, $apple['image']['width']);

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_INT,
                          $apple['image']['height']);
        $this->assertEquals(270, $apple['image']['height']);

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_STRING,
                          $apple['image']['url']);
        $this->assertEquals('http://api.golem.de/video/teaser_video.png', $apple['image']['url']);
    }

}
?>