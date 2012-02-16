<?php
/**
 * This isn't production-ready; it has many flaws. It's primary purpose is for
 * learning and demonstration, however, it can provide for quick POC's using
 * shallow classes like this:
 *
 * @example
 *        class foo {
 *          use GetSet;
 *          protected $bar;
 *        }
 *        (new foo())->setBar(1)->getBar(); // 1
 */
trait GetSet {

    public function __call($methodCall, $args) {

        $searchKey = substr($methodCall, 3);
        $keys = array(lcfirst($searchKey), ucfirst($searchKey), $searchKey);

        foreach ($keys as $key) {
            if (property_exists($this, $key)) {
                $theKey = $key;
                break;
            }
        }

        if (!isset($theKey)) {
            throw new BadMethodCallException(sprintf('"%s" not found; tried: [%s].',
                $searchKey, implode(', ', $keys)
            ));
        }

        $method = substr($methodCall, 0, 3);
        $return = '';
        if ($method == 'get') {
            $return = $this->$theKey;
        } else if ($method == 'set') {
            if (!isset($args[0])) {
                throw new BadMethodCallException(sprintf('%s->%s(void), really?',
                    __CLASS__, $methodCall
                ));
            }
            $this->$theKey = $args[0];
            $return = $this;
        } else {
            // we're only here because the method doesn't exist in the class, either.
            throw new BadMethodCallException(sprintf(
                'Call to undefined method %s::%s()? Trait %s couldn\'t find it.',
                __CLASS__, $methodCall, __TRAIT__
            ));
        }

        return $return;
    }
}

?>