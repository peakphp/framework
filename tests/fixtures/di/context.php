<?php

namespace Di;

interface InterfaceA {}
interface InterfaceAA extends InterfaceA {}
interface InterfaceB {}

class A implements InterfaceA {}
class AA implements InterfaceAA {}
class B implements InterfaceB {}
class C {}

class W implements InterfaceA {}
class X implements InterfaceA {}

class Y implements InterfaceB {}
class Z implements InterfaceB {}

class Finger {
    public function __construct(A $a, $arg1, $arg2) {
        $this->a = $a;
        $this->arg1 = $arg1;
        $this->arg2 = $arg2;
    }
}

class Hand {
    public function __construct(A $a, B $b, C $c) {
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
    }
}

class Arm {
    public function __construct(InterfaceA $a, $argv = null) {
        $this->a = $a;
        $this->argv = $argv;
    }
}

class Chest {
    public function __construct(Arm $arm, $argv = null) {
        $this->arm = $arm;
        $this->argv = $argv;
    }
}

class Body {
    public function __construct(InterfaceA $a, InterfaceB $b, $argv = null) {
        $this->a = $a;
        $this->b = $b;
        $this->argv = $argv;
    }
}