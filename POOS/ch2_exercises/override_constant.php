<?php
class classA
{
    const FIXED_NUMBER = 4;
}

class classB extends classA
{
    const FIXED_NUMBER = 20;
}

echo classA::FIXED_NUMBER . '<br />';
echo classB::FIXED_NUMBER;
