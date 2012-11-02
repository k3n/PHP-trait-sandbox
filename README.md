Just some experiments with PHP's traits.
========================================

Basic Traits
------
### GetSet

Automatically handles get/set methods for your class _provided you have created the class member_.

```php
class foo {
    use GetSet;
    protected $bar;
}
(new foo())->setBar(1)->getBar(); // 1
```

### Logger

Class-specific logging with a unified directory & filename structure.

```php
class foo {
    use Logger;
}
(new foo())->Log('ok');         // logs "[date] ok" to ./Logger/foo/default.log
foo::LogStatic('ok', 'test');   // logs "[date] ok" to ./Logger/foo/test.log
```

### Singleton

For when you absolutely, positively, can only have 1 of an object. _Maybe._

```php
class foo {
    use Singleton;
}
foo::getInstance(); // now what?
```