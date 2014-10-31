<?php
    namespace {
    }

    namespace Foo {
    }

    namespace Foo\Bar {
    }

    namespace  {
        class Foo
        {
            public function g(){

            }
        }
    }

    namespace Bar {
        class Foo
        {
        }
        class Element
        {}
    }

    namespace Bar\Qux {
        class Foo implements Hello
        {
        }
    }

    namespace {
        class Foo extends Bar
        {
        }
    }

    namespace  {
        class Doo implements Quz
        {
        }
    }

    namespace Doo\aaa {
        class Doo implements Quz
        {
        }
    }

    namespace Doo    {
        class SexMachine {

        }
    }

    namespace Doo\aaa {
        class Doo extends foo implements Quz
        {
        }
        class Doo extends foo implements Quz,Babar
        {
        }
    }
