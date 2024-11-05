<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2a5b5dfa45e8630f639a72e7e6bcb743
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Spatie\\ArrayToXml\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Spatie\\ArrayToXml\\' => 
        array (
            0 => __DIR__ . '/..' . '/spatie/array-to-xml/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2a5b5dfa45e8630f639a72e7e6bcb743::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2a5b5dfa45e8630f639a72e7e6bcb743::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit2a5b5dfa45e8630f639a72e7e6bcb743::$classMap;

        }, null, ClassLoader::class);
    }
}