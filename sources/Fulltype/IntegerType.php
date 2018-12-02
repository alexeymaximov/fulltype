<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

/**
 * Integer type.
 */
class IntegerType extends NumericType {

	/**
	 * Constructor.
	 *
	 * @param int|NULL $aMin -- minimal value
	 * @param int|NULL $aMax -- maximal value
	 */
	public function __construct(int $aMin = null, int $aMax = null) {
		parent::__construct($aMin, $aMax);
	}

	/**
	 * Validate value.
	 *
	 * @param mixed $aValue -- value
	 *
	 * @return ValidationResultInterface -- validation result
	 */
	public function validate($aValue): ValidationResultInterface {
		if (!is_int($aValue)) {
			$type = fulltype::typeof($aValue);
			return new InvalidValueError($this, "Value must be integer, $type given");
		}
		return parent::validate($aValue);
	}
}