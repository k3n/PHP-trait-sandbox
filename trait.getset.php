<?php
/**
 * This isn't production-ready; it has many flaws. It's primary purpose is for
 * learning and demonstration, however, it can provide for quick POC's using
 * shallow classes like this:
 *
 * @example
 *		class foo { protected $bar; }
 *		(new foo())->setBar(1)->getBar(); // 1
 */
trait GetSet {

	public function __call($method, $args) {
		$debug = false;
		static $fnFindKey;

		if ($debug) {
			printf('%s->__call("%s", [..%d]);%s', __CLASS__, $method, count($args), PHP_EOL);
			static $hit		= 0;
			static $miss	= 0;
		}

		if (!isset($fnFindKey))	{

			if ($debug) {
				++$miss;
				printf('New closure $fnFindKey() for "%s".%s', __CLASS__, PHP_EOL);
			}

			$fnFindKey = function($searchKey) use ($debug) {

				static $data;

				if (!isset($data)) {
					if ($debug) {
						printf('New static::$keys in $fnFindKey() for "%s".%s', __CLASS__, PHP_EOL);
					}
					// get_class_vars(__CLASS__) to freeze property-list at compile-time
					$objProps = array_keys(get_object_vars($this));
					if (!$objProps) {
						if ($debug) {
							printf('No properties to GetSet in "%s".%s', __CLASS__, PHP_EOL);
						}
						return $this;
					}
				}

				$keys = array(
					lcfirst($searchKey),
					ucfirst($searchKey),
					$searchKey
				);

				$theKey = '';
				foreach ($keys as $i => $key) {
					if (in_array($key, $objProps)) {
						if ($debug) {
							printf('found: "%s"%s', $key, PHP_EOL);
						}
						$theKey = $key;
						break;
					}
				}

				if ($debug) {
					printf('Found in %d iters.%s', $i + 1, PHP_EOL);
				}

				if (!strlen($theKey)) {
					throw new BadMethodCallException(sprintf('Missing "%s" in [%s] (search: [%s]).',
						$searchKey, implode(', ', $objProps), implode(', ', $keys)
					));
				}

				return $theKey;
			};
		} else if ($debug) {
			++$hit;
		}

		$op		= substr($method, 3);
		$key	= $fnFindKey($op);
		$ret	= '';
		if (strpos($method, 'get') === 0) {
			$ret = $this->$key;
		}
		else if (strpos($method, 'set') === 0) {
			$this->$key = $args[0];
			$ret = $this;
		} else {
			throw new BadMethodCallException(sprintf(
				'%s: Call to undefined function %s::%s()',
				__TRAIT__, __CLASS__, $method
			));
		}

		if ($debug) {
			printf('closure: %d / %d / %d (hit/miss/total)%s',
				$hit, $miss, $hit + $miss, PHP_EOL
			);
		}

		return $ret;
	}
}

?>