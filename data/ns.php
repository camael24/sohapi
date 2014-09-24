<?php
    namespace {
    }

    namespace Foo {
    }

    namespace Foo\Bar {
    }

    namespace  {
        class Foo {

        }
    }

    namespace Bar {
        class Foo {

        }
    }

    namespace Bar\Qux {
        class Foo {

        }
    }

    namespace {
        class Foo extends Bar {

        }
    }

    namespace  {
        class Doo implements Quz {

        }
    }

    namespace Doo\aaa {
        class Doo implements Quz {

        }
    }
    namespace Doo\aaa {
        class Doo extends foo implements Quz {

        }
        class Doo extends foo implements Quz,Babar {

        }
    }



?>