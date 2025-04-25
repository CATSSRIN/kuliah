<?php
class MyClass {
    const constant = 'constant value';
    function showConstant() {
        echo self::constant . "\n";
    }
}
echo MyClass::constant . "\n"; // Output: constant value
?>