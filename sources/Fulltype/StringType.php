<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

use InvalidArgumentException;

/**
 * String type.
 */
class StringType extends ScalarType {

	/**
	 * @var int|NULL -- length
	 */
	private $_length;

	/**
	 * Constructor.
	 *
	 * @param int|NULL $aLength -- length
	 *
	 * @throws InvalidArgumentException -- String type length must be greater than 0
	 */
	public function __construct(int $aLength = null) {
		if ($aLength !== null && $aLength <= 0) {
			throw new InvalidArgumentException("String type length must be greater than 0");
		}
		$this->_length = $aLength;
	}

	/**
	 * Get length.
	 *
	 * @return int|NULL -- length
	 */
	public function getLength() {
		return $this->_length;
	}

	/**
	 * Validate value.
	 *
	 * @param mixed $aValue -- value
	 *
	 * @return ValidationResultInterface -- validation result
	 */
	public function validate($aValue): ValidationResultInterface {
		if (!is_string($aValue)) {
			$type = fulltype::typeof($aValue);
			return new InvalidValueError($this, "Value must be a string, $type given");
		}
		$length = strlen($aValue);
		if ($this->_length !== null && $length > $this->_length) {
			return new LengthError($this, "String must contain $this->_length characters maximal, $length given");
		}
		return parent::validate($aValue);
	}
}