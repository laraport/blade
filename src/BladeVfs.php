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

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamContainer;

class BladeVfs
{
    static public function getVfsStreamDirectory($path)
    {
        if($path instanceof vfsStreamContainer)
        {
            return $path;
        }
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
        return $VfsDirectory;
    }

    static public function getVfsPath($path)
    {
        $VfsStreamDirectory = static::getVfsStreamDirectory($path);
        return vfsStream::url($VfsStreamDirectory->path());
    }

    static public function addTreeStructure($path, array $tree)
    {
        $VfsStreamDirectory = static::getVfsStreamDirectory($path);
        vfsStream::create($tree, $VfsStreamDirectory);
    }
}
