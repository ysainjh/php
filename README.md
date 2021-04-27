# php
安装composer<br/>
curl -sS https://getcomposer.org/installer | php<br/>
mv composer.phar /usr/local/bin/composer<br/>
创建composer.json文件，文件内容：<br/>
{}<br/>
执行<br/>
composer install 解决依赖（在命令行执行 composer install 后，在根目录会生成出一个vendor的文件夹）<br/>

安装PHPUnit<br/>
composer require --dev phpunit/phpunit ^6.2<br/>

安装 Monolog 日志包,做 phpunit 测试记录日志用<br/>
composer require monolog/monolog<br/>
2021.4.27 新增psr-4自动加载规范<br/>
          新增composer自动加载原理（闭包，匿名函数等知识点）<br/>
2021.4.25 新增php文件操作一些方法<br/>
          新增git操作<br/>
2021.4.23 新增排序方法详解<br/>


