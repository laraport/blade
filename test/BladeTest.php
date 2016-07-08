<?php

/*
 * This file is part of the laraport/blade package.
 *
 * (c) 2016 Kamal Khan <shout@bhittani.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Laraport\Blade;

class BladeTest extends PHPUnit_Framework_TestCase
{
    protected $defaultConfig;

    public function setUp()
    {
        $this->defaultConfig = [];
    }

    /** @test */
    public function it_works()
    {
        $this->assertTrue(true);
    }
}
