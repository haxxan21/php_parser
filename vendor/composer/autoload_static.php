<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc3fc42424c920c7e5dfe218c62fcec0b
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPHtmlParser\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPHtmlParser\\' => 
        array (
            0 => __DIR__ . '/..' . '/paquettg/php-html-parser/src/PHPHtmlParser',
        ),
    );

    public static $prefixesPsr0 = array (
        's' => 
        array (
            'stringEncode' => 
            array (
                0 => __DIR__ . '/..' . '/paquettg/string-encode/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc3fc42424c920c7e5dfe218c62fcec0b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc3fc42424c920c7e5dfe218c62fcec0b::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitc3fc42424c920c7e5dfe218c62fcec0b::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
