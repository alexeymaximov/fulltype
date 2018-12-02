<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

use Traversable;

/**
 * Iterable type.
 */
class IterableType extends AbstractType {

	/**
	 * Validate value.
	 *
	 * @param mixed $aValue -- value
	 *
	 * @return ValidationResultInterface -- validation result
	 */
	public function validate($aValue): ValidationResultInterface {
		if (!is_array($aValue) && !($aValue instanceof Traversable)) {
			$type = fulltype::typeof($aValue);
			return new InvalidValueError($this, "Value must be iterable, $type given");
		}
		return fulltype::valid();
	}
}