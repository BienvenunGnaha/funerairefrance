<?php
namespace App\Services;


class ObjectComplex {

private $foo;
    private $bar;
    private $baz;
    private $other;
    
    public function __construct(
        Foo $foo,
        Bar $bar,
        Baz $baz,
        Other $other
    )
    {
        $this->foo = $foo;
        $this->bar = $bar;
        $this->baz = $baz;
        $this->other = $other;
    }

    public function doSomething() {
        // ...
    }

}