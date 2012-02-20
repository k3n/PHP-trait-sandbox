Just some experiments with PHP's traits.
========================================

Traits
------
### GetSet

_Automatically handles get/set methods for your class (provided you have created the class member)._

    class foo {
        use GetSet;
        protected $bar;
    }
    (new foo())->setBar(1)->getBar(); // 1

### Logger: _Class-specific logging within a unified directory & filename structure._

    class foo {
        use Logger;
    }
    (new foo())->Log('ok');         // logs "[date] ok" to ./Logger/foo/default.log
    foo::LogStatic('ok', 'test');   // logs "[date] ok" to ./Logger/foo/test.log

### Singleton: _For when you absolutely, positively, can only have 1 of an object._ Maybe.

    class foo {
        use Singleton;
    }
    foo::getInstance(); // now what?