<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

/**
 * Callable type.
 */
class CallableType extends AbstractType {

	/**
	 * Validate value.
	 *
	 * @param mixed $aValue -- value
	 *
	 * @return ValidationResultInterface -- validation result
	 */
	public function validate($aValue): ValidationResultInterface {
		if (!is_callable($aValue)) {
			$type = fulltype::typeof($aValue);
			return new InvalidValueError($this, "Value must be callable, $type given");
		}
		return fulltype::valid();
	}
}