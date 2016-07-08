<?php

/*
 * This file is part of the laraport/blade package.
 *
 * (c) 2016 Kamal Khan <shout@bhittani.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Laraport;

use Illuminate\View\Factory;
use org\bovigo\vfs\vfsStream;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\View\FileViewFinder;
use org\bovigo\vfs\vfsStreamWrapper;
use Illuminate\Filesystem\Filesystem;
use org\bovigo\vfs\vfsStreamDirectory;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Compilers\BladeCompiler;

class Blade extends Factory
{
    public function __construct($path2views, $path2cache = null, $extensions = 'blade.php')
    {
        $this->extensions = [];

        if(is_null($path2cache))
        {
            $path2cache = BladeVfs::getVfsPath(uniqid('cache-'));
        }

        $this->setDispatcher(new Dispatcher);

        $paths = is_array($path2views) ? $path2views : [$path2views];
        $this->setFinder(new FileViewFinder(new Filesystem, $paths));

        $this->setContainer(new Container);

        $this->setEngine(new EngineResolver);

        $this->registerPhpResolver('php');

        $this->registerBladeResolver($extensions, $path2cache);
    }

    protected function registerPhpResolver($extensions)
    {
        $extensions = is_array($extensions) ? $extensions : [$extensions];
        array_walk($extensions, function($extension){
            $this->addExtension($extension, 'php');
        });
        $this->getEngine()->register('php', function(){
            return new PhpEngine;
        });
    }

    protected function registerBladeResolver($extensions, $path)
    {
        $extensions = is_array($extensions) ? $extensions : [$extensions];
        array_walk($extensions, function($extension){
            $this->addExtension($extension, 'blade');
        });
        $this->getEngine()->register('blade', function() use ($path){
            $Compiler = new BladeCompiler(new Filesystem, $path);
            return new CompilerEngine($Compiler);
        });
    }

    public function setEngine(EngineResolver $Engine)
    {
        $this->engines = $Engine;
    }

    public function getEngine()
    {
        return $this->engines;
    }

    static public function render($template, array $data = [], array $merge = [])
    {
        $VfsStreamDirectory = BladeVfs::getVfsStreamDirectory(uniqid('views-'));

        $fileName = uniqid('view-');

        BladeVfs::addTreeStructure($VfsStreamDirectory, [
            $fileName . '.blade.php' => $template
        ]);

        $Blade = new self(BladeVfs::getVfsPath($VfsStreamDirectory));
        return $Blade->make($fileName, $data, $merge);
    }
}
