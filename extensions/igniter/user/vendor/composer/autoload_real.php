<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitbcbf339ddf9f92a603b2f0f523c06ecc
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInitbcbf339ddf9f92a603b2f0f523c06ecc', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitbcbf339ddf9f92a603b2f0f523c06ecc', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitbcbf339ddf9f92a603b2f0f523c06ecc::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}