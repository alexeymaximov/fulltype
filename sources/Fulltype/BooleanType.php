<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

/**
 * Boolean type.
 */
class BooleanType extends ScalarType {

	/**
	 * Validate value.
	 *
	 * @param mixed $aValue -- value
	 *
	 * @return ValidationResultInterface -- validation result
	 */
	public function validate($aValue): ValidationResultInterface {
		if (!is_bool($aValue)) {
			$type = fulltype::typeof($aValue);
			return new InvalidValueError($this, "Value must be boolean, $type given");
		}
		return parent::validate($aValue);
	}
}