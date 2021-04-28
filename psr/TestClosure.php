<?php
class TestClosure {
    private $name ='王力宏';
    protected $age ='30';
    private static $weight ='70kg';
    public  $address ='中国';
    public static $height ='180cm';
    protected static $birsday = '2020';
    public static function test() {
        echo '身高是：'.self::$height.PHP_EOL;
        echo '出生日期是：'.self::$birsday.PHP_EOL;
        echo '体重是：'.self::$weight.PHP_EOL;
    }
}
$obj = new TestClosure();

//echo $obj->name;//报错 Cannot access private property A::$name
//echo $obj->age;//报错 Cannot access protected property A::$age
//echo A::$weight; //报错 Cannot access private property A::$weight
echo $obj->address;//正常 输出 中国
echo TestClosure::$height;//正常 输出 180cm

//实例对象可以获得公有属性, $obj->name等私有属性肯定不行 上面例子已列出报错
$fun = function() {
    $obj = new TestClosure();
    return $obj->address;
};
echo $fun();//正常 输出 中国

// 类可以直接访问公有静态属性，但A::$weight肯定不行，因为weight为私有属性
$fun2 = function(){
    return TestClosure::$height;
};
echo $fun2();//正常 输出 180cm

echo TestClosure::test();


$fun = function(){
    return TestClosure::$height;
};
echo $fun();

//echo $obj->birsday;  报错
//
//
//单独这段代码是肯定不能运行调用的，
//因为里面有个$this,程序压根不知道你这个$this是代表那个对象或那个类
//(并且就算知道那个对象或类，该对象是否拥有name属性，如果没有照样会有问题)
//因此想让其正常运行肯定有前提条件啊(就好比你想遍历某个数组一样，如果这个数组压根你就没提前定义 声明 肯定会报错的)
$fun = function() {
    return $this->name;
};
//加上这个就可以执行了
//该函数返回一个新的 Closure 对象 或者在失败时返回 FALSE
//匿名函数$fun里的$this被指定到了或绑定到了A实例对象上了
$cf = Closure::bind($fun,new TestClosure(),'TestClosure');  //绑定
//输出 王力宏 为什么呢：
//bind第一个参数是匿名函数，第二个A实例对象，第三个代表作用域
echo $cf();

//Closure::bind($fun,new A() );
//这个使用 你可以理解成对匿名函数做了如下过程:
//$fun = function(){
//  $this = newA();
    //你可以想象认为,$this就成了A类的实例对象了，然后在去访问name属性，就和我们正常实例化类访问成员属性一样。
    //上面2中的例子$obj = new A()就是这样，
    //(因为$this是关键字，在这里我们其实不能直接$this = new A();这么写，为了好理解我写成$this，但是原理还是这个意思)
    //但是我们都知道因为name属性是私有的，上面2中我已说过，实例对象不能访问私有属性，那该怎么办呢，
    //于是添加第三个参数就很重要了，一般传入一个对应对象，或对应类名
    //(对应的意思是：匿名函数中$this-name想获取name属性值，你这个$this想和那个类和对象绑定在一起呢，就是第二个参数，
    //这时你第三个参数写和第二个参数写一样的对象或类就行了，就是作用域为这个对象或类，这就会让原来的name私有属性变为公有属性)
    //return $this->name;
//} ;


//------------------------加深理解--------------------------------
$fun2 = function() {
    return $this->address;
};
//echo $fun2();这样运行会报错
//所以我们要进行匿名函数的绑定 是匿名函数里的$this有所指

/**必要时还要改变要访问属性的作用域**/
$newfun2 = Closure::bind($fun2, new TestClosure());
//使用了该函数后 ，该函数返回一个全新的匿名的函数，和原来匿名函数$fun2一模一样，
//只是其中 $this被指向了A实例对象,这样就能访问address属性了

echo $newfun2(); //输出，中国
//bind这次为什么没添加第三个参数？
//因为我们要访问的address属性是公有的，一个对象实例是可以直接访问公有属性的，
//这个例子中只要匿名函数中$this被指向了A对象实例(或者叫绑定也可以)，就能访问到公有属性，所以可以不用添加第三个参数，
//当然你加上了第三个参数 如这样Closure::bind($fun2,new TestClosure(),'Closure'); 或 Closure::bind($fun2, new TestClosure(),new TestClosure());
//不影响 照样运行，就好比把原来公有属性 变为公有属性 不影响的
//(一般当我们访问的属性为私有属性时，才使用第三个参数改变作用域 ，使其变为公有属性)



$fun = function() {
    return TestClosure::$weight;
};
//echo $fun();
//运行会报错 因为weight为私有属性 上面2中例子访问height属性是可以的，
//height为公有属性，所以把weight改成height是可以正常运行的，但是我们现在就想访问这个私有静态属性，我们该怎么办，
//于是Closure::bind出场了
$newfun = Closure::bind($fun,null,'TestClosure');
//通过bind函数作用，返回一个和$fun匿名函数一模一样的匿名函数，
//只是该匿名函数中TestClosure::$weight, weight属性由私有变成公有属性了。
echo $newfun(); //正常输出70kg
//为什么第二个参数又成null了呢？
//因为在该匿名函数中TestClosure::$weight 这属于正常类使用啊(php中 类名::公有静态属性，这是正常访问方法，上面2中例子已经说的很清楚了)
//所以不用绑定到某个对象上去了，于是第二个参数可以省略，
//唯一遗憾的是weight属性虽是静态属性，但是其权限是private私有属性，
//于是我们要把私有属性变公有属性就可以了，这时把第三个参数加上去就可以了，第三个参数可以是TestClosure类(Closure::bind($fun,null,'TestClosure'))，
//也可以是TestClosure类的对象实例(Closure::bind($fun,null, new TestClosure() ))，两种写法都可以，
//最终第三个参数的添加使私有属性变成了公有属性。
//(这个例子中当然你非得添加第二个参数肯定也没问题，
//只要第二个参数是TestClosure的实例对象就行Closure::bind($fun,new TestClosure(),'TestClosure')，不影响，
//只是说 TestClosure::$weight 这种使用方法本身就是正常使用，程序本身就知道你用的是TestClosure类，你在去把它指向到TestClosure类自己的对象实例上，属于多此一举，因此第二个参数加不加都行，不加写null就行。
