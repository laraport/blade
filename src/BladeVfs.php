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

class BladeVfs
{
    static public function getVfsPath($path)
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
}
