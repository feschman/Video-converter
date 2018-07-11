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
 * $Id: GolemApiThemeListTest.php 1852 2009-04-28 16:15:14Z am $
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
require_once '../Golem/Api/Theme/List.php';

class GolemApiThemeListTest extends PHPUnit_Framework_TestCase
{

    const API_KEY = 'YOUR_DEV_KEY'; // insert your developer key here

    protected $request     = null;

    public function setUp()
    {

        $this->request = new Golem_Api_Theme_List(self::API_KEY);
        
        $this->request->fetch();

    }

    public function testThemeCount()
    {

        $themes = $this->request->getThemes();

        $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_ARRAY, $themes);
        $this->assertEquals(10, count($themes));

    }

    public function testThemeData()
    {

        $themes = $this->request->getThemes();

        foreach($themes as $theme) {

            $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_STRING,
                              $theme['name']);
            $this->assertEquals('Lorem ipsum',
                                $theme['name']);

            $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_STRING,
                              $theme['shortname']);
            $this->assertEquals('lo',
                                $theme['shortname']);

            $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_STRING,
                              $theme['url']);
            $this->assertEquals('http://www.golem.de/lo', $theme['url']);

        }

    }

}

?>