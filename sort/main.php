<?php
namespace Sort;
require_once '../vendor/autoload.php';
//冒泡算法
$arr = [3,2,5,4,6,9,7,1];
$n = count($arr);
function bubbleSort(&$arr,$n) {
    if($n <= 1)
        return ;
    for ($i = 0;$i < $n;$i++) {  //冒泡趟数,每一趟确定一个最大的数
        $flag = false;
        for($j = 0;$j < $n  - 1 - $i;$j++) {
            if($arr[$j] > $arr[$j+1]) {  //交换
                $temp = $arr[$j];
                $arr[$j] = $arr[$j+1];
                $arr[$j+1] = $temp;
                $flag = true;
            }
        }
        if(!$flag)
            break;  //没有数据交换，提前退出 没交换的时候，代表数组已经是有序的了
    }
}
bubbleSort($arr,$n);
echo '冒泡排序算法：稳定排序算法，空间复杂度：O(1)，原地排序算法，平均时间复杂度为：O(n平方)'.PHP_EOL;
echo '最好情况下，要排序的数据已经是有序的了，我们只需要进行一次冒泡操作,所以最好情况时间复杂度是 O(n)'.PHP_EOL;
echo '最坏的情况是，要排序的数据刚好是倒序排列的，需要进行n次冒泡操作，所以最坏情况时间复杂度为 O(n平方)'.PHP_EOL;
var_dump($arr);

//插入法排序
$arr = [3,2,5,4,6,9,7,1];
$n = count($arr);
for($i = 1;$i < $n; $i ++) {
    $value = $arr[$i];
    $j = $i - 1;
    for(;$j >= 0;$j--) {
        if ($arr[$j] > $value) {
            $arr[$j + 1] = $arr[$j];
        } else {
            break;
        }
    }
    $arr[$j + 1] = $value;
}
echo '插入排序算法：稳定排序算法，空间复杂度：O(1)，原地排序算法，平均时间复杂度为：O(n平方)'.PHP_EOL;;
var_dump($arr);

//选择法排序
$arr = [3,2,5,4,6,9,7,1];
$n = count($arr);
for($i = 0;$i < $n; $i ++) {
    //先假设最小值的位置
    $min_index = $i;
    for($j = $i + 1;$j < $n;$j++) {
        if($arr[$j] < $arr[$min_index]) {
            $min_index = $j;
        }
    }
    //值互换
    $tmp = $arr[$i];
    $arr[$i] = $arr[$min_index];
    $arr[$min_index] = $tmp;

}
echo '选择排序算法：不稳定排序算法（每次找最小值的时候，相同值可能会发生位置变化），空间复杂度：O(1)，原地排序算法，平均时间复杂度为：O(n平方)'.PHP_EOL;
var_dump($arr);


//归并排序
$arr = [3,2,5,4,6,9,7,1];
$n = count($arr);

function merge_sort($arr,$n) {
    return merge_sort_c($arr,0,$n - 1);
}

function merge_sort_c($arr,$start,$end) {
    //终止条件
    if($start >= $end)
        return;
    //取中间位置
    $middle = ceil(($end + 1) / 2);

    merge_sort_c($arr,$start,$middle);
    merge_sort_c($arr,$middle + 1,$end);

}

function merge_arr($left,$right){

    $temp = [];

}


var_dump($arr);


$a = 4;
$b = 5;

[$a,$b] = [$b,$a];

var_dump([$a,$b]);

var_dump($a,$b);

$b = $a;
$a = $b;

var_dump($a,$b);


function quickSort(&$arr){
    $count = count($arr);
    return quickSortInternally($arr,0,$count - 1);
}

function quickSortInternally(&$arr,$l,$r){
    if ($l >= $r)
        return ;
    $i = partition($arr,$l,$r);
    quickSortInternally($arr,$l,$i - 1);
    quickSortInternally($arr,$i+1,$r);
}

//快速排序  空间复杂度O(1)
function partition(&$arr,$l,$r){
    $i = $l;
    $partition = $arr[$r];
    for($j = $l;$j < $r;$j++) {
        if($arr[$j] < $partition) {
            [$arr[$i],$arr[$j]] = [$arr[$j],$arr[$i]];
            $i++;
        }
    }
    [$arr[$i],$arr[$r]] = [$arr[$r],$arr[$i]];
    return $i;
}

$a1 = [1,4,6,2,3,5,4];
quickSort($a1);

var_dump($a1);


//O(n) 时间复杂度内求无序数组中的第 K 大元素
function quickSortInternally_new(&$arr,$l,$r,$k){
    if ($l >= $r || $l + 1 == $k)
        return $arr[$l];
    $i = partition_new($arr,$l,$r);
    //var_dump($i);
    //var_dump($arr);
    if($i + 1 == $k)
        return $arr[$i];
    elseif($i + 1 > $k) {
        quickSortInternally_new($arr,$l,$i - 1,$k);
        echo 33;
    } else {
        quickSortInternally_new($arr,$i+1,$r,$k - $i + 1);
        echo 44;
        var_dump($arr);
    }
}
//从大到小排序
function partition_new(&$arr,$l,$r){
    $i = $l;
    $partition = $arr[$r];
    for($j = $l;$j < $r;$j++) {
        if($arr[$j] > $partition) {
            [$arr[$i],$arr[$j]] = [$arr[$j],$arr[$i]];
            $i++;
        }
    }
    [$arr[$i],$arr[$r]] = [$arr[$r],$arr[$i]];
    return $i;
}

$a1 = [1,4,6,2,3,5,4];
var_dump(quickSortInternally_new($a1,0,count($a1) - 1,4));

//计数排序
//考试成绩数组，最低0分，最高5分
$a = [3,0,3,2,0,3,5,2];
$n = count($a);
function countingSort($a,$n) {
    if($n <= 1)
        return ;
    //遍历成绩表，找出最大最小成绩
    $max = $a[0];
    $min = $a[0];
    for($i = 1;$i < $n;$i++) {
        if($a[$i] > $max) {
            $max = $a[$i];
        }
        if($a[$i] < $min) {
            $min = $a[$i];
        }
    }
    var_dump($min);
    var_dump($max);
    //按照最大，最小分数分数组区间
    $c = [];
    for($i = $min;$i <= $max;$i++) {
        $c[$i] = 0;
    }

    for($i = 0;$i < $n;$i++) {
        $c[$a[$i]] += 1;
    }
    var_dump($c);

    //C数组顺序求和
    for($i = $min + 1;$i <= $max;$i++) {
        $c[$i] += $c[$i - 1];
    }
    var_dump($c);

    //求考生的排名
    $r = [];
    for($i = 0;$i < $n;$i++) {
        $r[$c[$a[$i]] - 1] = $a[$i];
        $c[$a[$i]]--;
    }
    var_dump($r);
    for($i = 0;$i < $n;$i++) {
        $a[$i] = $r[$i];
    }
    var_dump($a);
}

countingSort($a,$n);


?>
