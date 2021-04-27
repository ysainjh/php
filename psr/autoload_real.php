<?php
class ComposerAutoloaderInit5e08fe6b62b33d152affb96e53b63902
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
        //--------------第一部分 单例-------------------------
        if (null !== self::$loader) {   //经典的单例模式，自动加载类只能有一个。
            return self::$loader;
        }
        require __DIR__ . '/platform_check.php';

        //--------------第二部分 构造ClassLoader核心类------------------
        //new 一个自动加载的核心类对象。
        /***********************获得自动加载核心类对象********************/
        //composer 先向 PHP 自动加载机制注册了一个函数，这个函数 require 了 ClassLoader 文件。成功 new 出该文件中核心类 ClassLoader() 后，又销毁了该函数。
        spl_autoload_register(
            array('ComposerAutoloaderInit5e08fe6b62b33d152affb96e53b63902', 'loadClassLoader'), true, true
        );

        self::$loader = $loader = new \Composer\Autoload\ClassLoader();

        spl_autoload_unregister(array('ComposerAutoloaderInit5e08fe6b62b33d152affb96e53b63902', 'loadClassLoader'));

        //--------------第三部分 初始化核心类对象--------------------------
        //对自动加载类的初始化，主要是给自动加载核心类初始化顶级命名空间映射。
        //初始化的方法有两种：
        //1. 使用 autoload_static 进行静态初始化；
        //2. 调用核心类接口初始化。
        //
        //autoload_static 静态初始化 ( PHP >= 5.6 )
        //静态初始化只支持 PHP5.6 以上版本并且不支持 HHVM 虚拟机。
        // autoload_static.php 这个文件发现这个文件定义了一个用于静态初始化的类，名字叫 ComposerAutoloaderInit5e08fe6b62b33d152affb96e53b63902，仍然为了避免冲突而加了 hash 值。
        //
        $useStaticLoader = PHP_VERSION_ID >= 50600 && !defined('HHVM_VERSION') && (!function_exists('zend_loader_file_encoded') || !zend_loader_file_encoded());
        if ($useStaticLoader) {
            require __DIR__ . '/autoload_static.php';
            //这个静态初始化类的核心就是 getInitializer() 函数,
            //它将自己类中的顶级命名空间映射给了 ClassLoader 类。值得注意的是这个函数返回的是一个匿名函数，为什么呢？
            //原因就是 ClassLoader类 中的 prefixLengthsPsr4 、prefixDirsPsr4等等变量都是 private的。
            //利用匿名函数的绑定功能就可以将这些 private 变量赋给 ClassLoader 类 里的成员变量。
            call_user_func(\Composer\Autoload\ComposerStaticInit5e08fe6b62b33d152affb96e53b63902::getInitializer($loader));
        } else {    //如果PHP版本低于 5.6 或者使用 HHVM 虚拟机环境，那么就要使用核心类的接口进行初始化。
            $map = require __DIR__ . '/autoload_namespaces.php';
            foreach ($map as $namespace => $path) {
                $loader->set($namespace, $path);
            }

            $map = require __DIR__ . '/autoload_psr4.php';
            foreach ($map as $namespace => $path) {
                $loader->setPsr4($namespace, $path);
            }

            $classMap = require __DIR__ . '/autoload_classmap.php';
            if ($classMap) {
                $loader->addClassMap($classMap);
            }
        }

        //-------------------第四部分 注册----------------------------------------
        // 经过Composer 自动加载功能的启动与初始化，
        // 经过启动与初始化，自动加载核心类对象已经获得了顶级命名空间与相应目录的映射，
        // 也就是说，如果有命名空间 'App\Console\Kernel，我们已经可以找到它对应的类文件所在位置。
        // 那么，它是什么时候被触发去找的呢？
        // 注册自动加载核心类对象
        $loader->register(true);

        //------------------第五部分 全局函数的自动加载------------------------------
        //ClassLoader 的 register() 函数将 loadClass() 函数注册到 PHP 的 SPL 函数堆栈中，
        //每当 PHP 遇到不认识的命名空间时就会调用函数堆栈的每个函数，直到加载命名空间成功。
        //所以 loadClass() 函数就是自动加载的关键了。


        //全局函数的自动加载
        //Composer 不止可以自动加载命名空间，还可以加载全局函数。
        //怎么实现的呢？把全局函数写到特定的文件里面去，在程序运行前挨个 require就行了。
        //跟核心类的初始化一样，全局函数自动加载也分为两种：静态初始化和普通初始化，静态加载只支持PHP5.6以上并且不支持HHVM。
        //
        if ($useStaticLoader) {
            $includeFiles = Composer\Autoload\ComposerStaticInit5e08fe6b62b33d152affb96e53b63902::$files;
        } else {
            $includeFiles = require __DIR__ . '/autoload_files.php';
        }
        foreach ($includeFiles as $fileIdentifier => $file) {
            composerRequire5e08fe6b62b33d152affb96e53b63902($fileIdentifier, $file);
        }
        return $loader;
    }
}

function composerRequire5e08fe6b62b33d152affb96e53b63902($fileIdentifier, $file)
{
    if (empty($GLOBALS['__composer_autoload_files'][$fileIdentifier])) {
        require $file;

        $GLOBALS['__composer_autoload_files'][$fileIdentifier] = true;
    }
}
