<?php
class classC
{
    protected static $_counter = 0;
    public $num;
    
    public function __construct()
    {
        self::$_counter++;
        $this->num = self::$_counter;
    }
}

$object1 = new classC();
echo $object1->num . '<br />';
$object2 = new classC();
echo $object2->num;
