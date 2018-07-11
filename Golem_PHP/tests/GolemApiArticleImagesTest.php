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
 * $Id: GolemApiArticleImagesTest.php 2033 2009-06-28 23:30:48Z am $
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
require_once '../Golem/Api/Article/Images.php';

class GolemApiArticleImagesTest extends PHPUnit_Framework_TestCase
{

    const API_KEY = 'YOUR_DEV_KEY'; // insert your developer key here

    protected $request = null;

    protected $idArticle = 12345;

    public function setUp()
    {

        $this->request = new Golem_Api_Article_Images(self::API_KEY, $this->idArticle);

        $this->request->fetch();

    }

    public function testImagesCount()
    {

        $this->assertEquals(5, count($this->request));

    }

    public function testImagesData()
    {

        foreach($this->request as $image) {

            $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_INT,
                              $image['imageid']);
            $this->assertEquals(12345, $image['imageid']);

            $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_STRING,
                              $image['subtext']);
            $this->assertEquals('Lorem ipsum dolor sit amet consectetuer '.
                                'sadipscing elitr sed',
                                $image['subtext']);

            $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_ARRAY,
                              $image['small']);
            $this->assertEquals(3, count($image['small']));

            $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_INT,
                              $image['small']['height']);
            $this->assertEquals(90, $image['small']['height']);

            $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_INT,
                              $image['small']['width']);
            $this->assertEquals(120, $image['small']['width']);

            $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_STRING,
                              $image['small']['url']);
            $this->assertEquals('http://api.golem.de/img/example_120.png', $image['small']['url']);

            $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_ARRAY,
                              $image['medium']);
            $this->assertEquals(3, count($image['medium']));

            $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_INT,
                              $image['medium']['height']);
            $this->assertEquals(270, $image['medium']['height']);

            $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_INT,
                              $image['medium']['width']);
            $this->assertEquals(480, $image['medium']['width']);

            $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_STRING,
                              $image['medium']['url']);
            $this->assertEquals('http://api.golem.de/img/example_480.png', $image['medium']['url']);

            $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_ARRAY,
                              $image['native']);
            $this->assertEquals(3, count($image['native']));

            $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_INT,
                              $image['native']['height']);
            $this->assertEquals(535, $image['native']['height']);

            $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_INT,
                              $image['native']['width']);
            $this->assertEquals(800, $image['native']['width']);

            $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_STRING,
                              $image['native']['url']);
            $this->assertEquals('http://api.golem.de/img/example_native.png', $image['native']['url']);
        }

    }

}

?>