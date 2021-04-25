<?php
namespace php\file;
require_once '../vendor/autoload.php';

//在php中，对于文件的读取时，最快捷的方式使用file、file_get_contents之类的函数，简简单单的几行代码就能 很漂亮的完成我们所需要的功能。但当所操作的文件是一个比较大的文件时，这些函数可能就显的力不从心
//有一个800M的日志文件,大约有500多万行， 用php返回最后几行的内容。

//echo phpinfo();

//由于 file函数是一次性将所有内容读入内存，而php为了防止一些写的比较糟糕的程序占用太多的内存而导致系统内存不足，使服务器出现宕机。
//所以默认情况下限制只能最大使用内存16M,这是通过php.ini里的 memory_limit = 16M来进行设置，这个值如果设置-1，则内存使用量不受限制.
//函数 file ,将整个文件读入数组中   显示当期目录下一级子文件和子目录占用磁盘总量 du -lh --max-depth=1

/*
ini_set('memory_limit','-1');
$file = file('tmp');
$line = $file[count($file)-1]; //获取文件最后一行
var_dump($line);
*/

//直接用linux的tail命令来显示最后几行
//escapeshellarg函数，把字符串转码为可以在 shell 命令里使用的参数
//cat tmp 也会提示内存不足
//$file = escapeshellarg('tmp'); // 对命令行参数进行安全转义
//$line = `tail -n1 tmp`;
//echo $line;


//file_get_contents — 将整个文件读入一个字符串
//file — 把整个文件读入一个数组中
//file_put_contents — 将一个字符串写入文件


//fopen    打开文件或者 URL
//fgets    从文件指针中读取一行
//feof 测试文件指针是否到了文件结束的位置
//不需要将文件的内容全部读入内容,而是直接通过指针来操作,所以效率是相当高效的
//逐行读取文件
/*
$fp = fopen('tmp', 'r');
if ($fp) {
    while (($buffer = fgets($fp, 1024)) !== false) {
        echo $buffer;
    }
    if (!feof($fp)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($fp);
}
*/

//fsockopen   打开一个网络连接或者一个Unix套接字连接
//

//直接使用php的fseek（在文件指针中定位）来进行文件操作
//fseek ( resource $handle , int $offset , int $whence = SEEK_SET ) 在文件指针中定位
//handle  文件系统指针
//offset  偏移量。  要移动到文件尾之前的位置，需要给 offset 传递一个负值，并设置 whence 为 SEEK_END。
//whence ：
//SEEK_SET - 设定位置等于 offset 字节。
//SEEK_CUR - 设定位置为当前位置加上 offset。
//SEEK_END - 设定位置为文件尾加上 offset。

// $data = fgets($fp, 4096);
// var_dump($data);
/*
$fp = fopen('tmp', 'r');
$data = fgets($fp, 4096);
echo $data;
// fseek($fp,0);
// $data = fgets($fp, 4096);
// echo $data;
fseek($fp,50,SEEK_CUR);
$data = fgets($fp,4096);
echo $data;
exit;
if($fp) {
    fseek($fp, 0);
    echo fgets($fp,1024);
}
*/

//返回一个大文件的最后10行
//方法一
//首先通过fseek找到文件的最后一位EOF，
//然后找最后一行的起始位置，取这一行的数据，再找次一行的起始位置， 再取这一行的位置，
//依次类推，直到找到了$num行。
$file = 'tmp';
$fp = fopen($file, "r");
$line = 10;
$pos = -2;
$t = " ";
$data = "";
while ($line > 0) {
    while ($t != "\n") {
        fseek($fp, $pos, SEEK_END);
        $t = fgetc($fp);  //从文件指针中读取字符
       $pos --;
    }
    $t = " ";
    $data .= fgets($fp);
    $line --;
}
fclose ($fp);
//echo $data;

//方法二
//还是采用fseek的方式从文件最后开始读
//但这时不是一位一位的读,而是一块一块的读,每读一块数据时,就将读取后的数据放在一个buf里,
//然后通过换 行符(\n)的个数来判断是否已经读完最后$num行数据.
$fp = fopen($file, "r");
$num = 10;
$chunk = 4096;
$readData = '';
$fs = sprintf("%u", filesize($file));  // 取得文件大小
$max = (intval($fs) == PHP_INT_MAX) ? PHP_INT_MAX : filesize($file);   //PHP_INT_MAX 整数integer值的最大值
for ($len = 0; $len < $max; $len += $chunk) {
    $seekSize = ($max - $len > $chunk) ? $chunk : $max - $len;
    fseek($fp, ($len + $seekSize) * -1, SEEK_END);
    $readData = fread($fp, $seekSize) . $readData;
    if (substr_count($readData, "\n") >= $num + 1) {
        preg_match("!(.*?\n){".($num)."}$!", $readData, $match);
        $data = $match[0];
        break;
    }
}
fclose($fp);
//echo $data;

//方法三
function tail($fp,$n,$base = 5)
{
    assert($n>0); //断言
    $pos = $n+1;
    $lines = [];
    while(count($lines) <= $n){
        try {
            fseek($fp,-$pos,SEEK_END); //设定位置为文件尾加上 offset
        } catch (Exception $e){
            fseek(0);
            break;
        }
        $pos *= $base;
        while(!feof($fp)){
            array_unshift($lines,fgets($fp));;
        }
    }
    return array_slice($lines,0,$n);
}
var_dump(tail(fopen("tmp","r+"),10));


// $handler = fopen("file.txt", "rb+");
// fseek($handler, 0);
// fwrite($handler, "want to add this");
// fclose($handler);

// $fb = @fopen("file.txt", 'r');
// while(!feof($fb)) {
//     var_dump(fgets($fb));
// }
// fclose($fb);



?>
