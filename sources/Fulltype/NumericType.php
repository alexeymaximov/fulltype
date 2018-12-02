<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

use InvalidArgumentException;

/**
 * Numeric type.
 */
class NumericType extends ScalarType {

	/**
	 * @var float|NULL -- minimal value
	 */
	private $_min;

	/**
	 * @var float|NULL -- maximal value
	 */
	private $_max;

	/**
	 * Constructor.
	 *
	 * @param float|NULL $aMin -- minimal value
	 * @param float|NULL $aMax -- maximal value
	 *
	 * @throws InvalidArgumentException -- Minimal numeric type value must be less than maximal
	 */
	public function __construct(float $aMin = null, float $aMax = null) {
		if ($aMin !== null && $aMax !== null && $aMin > $aMax) {
			throw new InvalidArgumentException("Minimal numeric type value must be less than maximal");
		}
		$this->_min = $aMin;
		$this->_max = $aMax;
	}

	/**
	 * Get minimal value.
	 *
	 * @return float|NULL -- minimal value
	 */
	public function getMin() {
		return $this->_min;
	}

	/**
	 * Get maximal value.
	 *
	 * @return float|NULL -- maximal value
	 */
	public function getMax() {
		return $this->_max;
	}

	/**
	 * Validate value.
	 *
	 * @param mixed $aValue -- value
	 *
	 * @return ValidationResultInterface -- validation result
	 */
	public function validate($aValue): ValidationResultInterface {
		if (!is_int($aValue) && !is_float($aValue)) {
			$type = fulltype::typeof($aValue);
			return new InvalidValueError($this, "Value must be numeric, $type given");
		}
		if ($this->_min !== null && $aValue < $this->_min) {
			return new RangeError($this, "Numeric value must not be less than $this->_min");
		}
		if ($this->_max !== null && $aValue > $this->_max) {
			return new RangeError($this, "Numeric value must not be greater than $this->_max");
		}
		return parent::validate($aValue);
	}
}