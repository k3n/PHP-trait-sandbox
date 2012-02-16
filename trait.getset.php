<?php
/**
 * This isn't production-ready; it has many flaws. It's primary purpose is for
 * learning and demonstration, however, it can provide for quick POC's using
 * shallow classes like this:
 *
 * @example
 *        class foo { protected $bar; }
 *        (new foo())->setBar(1)->getBar(); // 1
 */
trait GetSet {

    public function __call($methodCall, $args) {
        $debug = !false;

        if ($debug) {
            printf('%s%s', str_pad(' ' . __TRAIT__ . ' ', 40, '-', STR_PAD_BOTH), PHP_EOL);
            printf('%s->__call("%s", [argc: %d]);%s', __CLASS__, $methodCall, count($args), PHP_EOL);
        }

        static $fnFindKey;
        if (!isset($fnFindKey)) {

            if ($debug) {
                printf('new closure $fnFindKey() for "%s".%s', __CLASS__, PHP_EOL);
            }

            $fnFindKey = function($searchKey, $debug) {

                $objProps = array_keys(get_object_vars($this));

                $keys = array(
                    lcfirst($searchKey),
                    ucfirst($searchKey),
                    $searchKey
                );

                $theKey = '';
                foreach ($keys as $i => $key) {
                    if (in_array($key, $objProps)) {
                        $theKey = $key;
                        break;
                    }
                    if ($debug) {
                        printf('missed: "%s"%s', $key, PHP_EOL);
                    }
                }

                if ($debug) {
                    printf('took %d iters.%s', $i + 1, PHP_EOL);
                }

                if (!strlen($theKey)) {
                    throw new BadMethodCallException(sprintf('Missing "%s" in [%s] (search: [%s]).',
                        $searchKey, implode(', ', $objProps), implode(', ', $keys)
                    ));
                }

                return $theKey;
            };
        }

        $method = substr($methodCall, 0, 3);
        $op     = substr($methodCall, 3);
        $key    = $fnFindKey($op, $debug);
        $ret    = '';

        if (($method == 'get') && isset($this->$key)) {
            $ret = $this->$key;
        }
        else if (($method == 'set') && isset($args[0])) {
            $this->$key = $args[0];
            $ret = $this;
        } else {
            // we're only here because the method doesn't exist in the class, either.
            throw new BadMethodCallException(sprintf(
                'Call to undefined method %s::%s()? Trait %s couldn\'t find it.',
                __CLASS__, $methodCall, __TRAIT__
            ));
        }

        return $ret;
    }
}

?>