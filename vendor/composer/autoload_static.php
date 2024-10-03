<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5ae1f4770aff25bbe88adad59f3133b5
{
    public static $prefixLengthsPsr4 = array (
        's' => 
        array (
            'src\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'src\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5ae1f4770aff25bbe88adad59f3133b5::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5ae1f4770aff25bbe88adad59f3133b5::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit5ae1f4770aff25bbe88adad59f3133b5::$classMap;

        }, null, ClassLoader::class);
    }
}