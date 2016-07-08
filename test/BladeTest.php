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
                'sub' => ['index.blade.php' => 'Shared name: {{ $shared }}']
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

    /** @test */
    public function it_should_render_a_blade_php_view()
    {
        $Template = $this->Blade->make('sample', ['name' => 'Bob']);
        $this->assertEquals('Hi Bob', (string) $Template);
    }

    /** @test */
    public function it_should_render_a_blade_php_view_as_a_custom_extension()
    {
        $Template = $this->Blade->make('acme', ['name' => 'Eve']);
        $this->assertEquals('Hey Eve. Using foo.bar extension.', (string) $Template);
    }

    /** @test */
    public function it_should_render_a_view_with_shared_data()
    {
        $this->Blade->share('shared', 'Acme');
        $Template = $this->Blade->make('sub.index');
        $this->assertEquals('Shared name: Acme', (string) $Template);
    }

    /** @test */
    public function it_should_allow_in_memory_cache()
    {
        $Blade = new Blade(vfsStream::url('blade/views'));
        $Template = $Blade->make('sample', ['name' => 'Memory']);
        $this->assertEquals('Hi Memory', (string) $Template);
    }
}
