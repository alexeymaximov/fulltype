<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

/**
 * Scalar type.
 */
class ScalarType extends AbstractType {

	/**
	 * Validate value.
	 *
	 * @param mixed $aValue -- value
	 *
	 * @return ValidationResultInterface -- validation result
	 */
	public function validate($aValue): ValidationResultInterface {
		if (!is_scalar($aValue)) {
			$type = fulltype::typeof($aValue);
			return new InvalidValueError($this, "Value must be scalar, $type given");
		}
		return fulltype::valid();
	}
}