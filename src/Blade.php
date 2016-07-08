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
            $path2cache = $this->getVfsPath(uniqid('cache-'));
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

    protected function getVfsPath($path)
    {
        $VfsRootDirectory = vfsStreamWrapper::getRoot();
        if(is_null($VfsRootDirectory))
        {
            vfsStreamWrapper::register();
            $VfsDirectory = new vfsStreamDirectory($path);
            $VfsRootDirectory = new vfsStreamDirectory(uniqid('blade-'));
            $VfsRootDirectory->addChild($VfsDirectory);
            vfsStreamWrapper::setRoot($VfsRootDirectory);
        }
        else
        {
            $VfsDirectory = new vfsStreamDirectory($path);
            $VfsBladeDirectory = new vfsStreamDirectory(uniqid('blade-'));
            $VfsBladeDirectory->addChild($VfsDirectory);
            $VfsRootDirectory->addChild($VfsBladeDirectory);
        }
        return vfsStream::url($VfsDirectory->path());
    }

    public function setEngine(EngineResolver $Engine)
    {
        $this->engines = $Engine;
    }

    public function getEngine()
    {
        return $this->engines;
    }
}
