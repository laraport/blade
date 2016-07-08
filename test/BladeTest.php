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
use org\bovigo\vfs\vfsStream;

class BladeTest extends PHPUnit_Framework_TestCase
{
    protected $Blade;

    public function setUp()
    {
        vfsStream::setup('blade', null, array(
            'cache' => [],
            'views' => [
                'index.php' => 'Hello <?php echo $name; ?>!',
                'sample.blade.php' => 'Hi {{ $name }}',
                'acme.foo.bar' => 'Hey {{ $name }}. Using foo.bar extension.',
                'sub' => ['index.php' => 'Shared name: {{ shared }}']
            ]
        ));
        $path2views = vfsStream::url('blade/views');
        $path2cache = vfsStream::url('blade/cache');
        $extensions = ['blade.php', 'foo.bar'];
        $this->Blade = new Blade($path2views, $path2cache, $extensions);
    }

    /** @test */
    public function it_should_render_a_simple_php_view()
    {
        $Template = $this->Blade->make('index', ['name' => 'Alice']);
        $this->assertEquals('Hello Alice!', (string) $Template);
    }
}
