<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

use InvalidArgumentException;

/**
 * Character type.
 */
class CharacterType extends ScalarType {

	/**
	 * @var int -- length
	 */
	private $_length;

	/**
	 * Constructor.
	 *
	 * @param int $aLength -- length
	 *
	 * @throws InvalidArgumentException -- Character type length must be greater than 0
	 */
	public function __construct(int $aLength = 1) {
		if ($aLength <= 0) {
			throw new InvalidArgumentException("Character type length must be greater than 0");
		}
		$this->_length = $aLength;
	}

	/**
	 * Get length.
	 *
	 * @return int -- length
	 */
	public function getLength(): int {
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
		if ($length !== $this->_length) {
			return new LengthError($this, "String must contain $this->_length characters, $length given");
		}
		return parent::validate($aValue);
	}
}