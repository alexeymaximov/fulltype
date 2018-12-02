<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

use InvalidArgumentException;
use RuntimeException;

/**
 * Array type.
 */
class ArrayType extends AbstractType {

	/**
	 * @var int|NULL -- length
	 */
	private $_length;

	/**
	 * @var bool -- objective
	 */
	private $_objective = false;

	/**
	 * @var TypeInterface|NULL -- key type
	 */
	private $_keyType = null;

	/**
	 * @var TypeInterface|NULL -- value type
	 */
	private $_valueType = null;

	/**
	 * Constructor.
	 *
	 * @param int|NULL $aLength -- length
	 *
	 * @throws InvalidArgumentException -- Array type length must be greater than 0
	 */
	public function __construct(int $aLength = null) {
		if ($aLength !== null && $aLength <= 0) {
			throw new InvalidArgumentException("Array type length must be greater than 0");
		}
		$this->_length = $aLength;
	}

	/**
	 * Make objective.
	 *
	 * @return $this
	 *
	 * @throws RuntimeException -- Array type is already objective
	 */
	public function objective() {
		if ($this->_objective) {
			throw new RuntimeException("Array type is already objective");
		}
		$this->_objective = true;
		return $this;
	}

	/**
	 * Set key type.
	 *
	 * @param TypeInterface $aKeyType -- key type
	 *
	 * @return $this
	 *
	 * @throws RuntimeException -- Array key type is already defined
	 */
	public function by(TypeInterface $aKeyType) {
		if ($this->_keyType !== null) {
			throw new RuntimeException("Array key type is already defined");
		}
		$this->_keyType = $aKeyType;
		return $this;
	}

	/**
	 * Set value type.
	 *
	 * @param TypeInterface $aValueType -- value type
	 *
	 * @return $this
	 *
	 * @throws RuntimeException -- Array value type is already defined
	 */
	public function of(TypeInterface $aValueType) {
		if ($this->_valueType !== null) {
			throw new RuntimeException("Array value type is already defined");
		}
		$this->_valueType = $aValueType;
		return $this;
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
	 * Whether is objective or not.
	 *
	 * @return bool -- objective
	 */
	public function isObjective(): bool {
		return $this->_objective;
	}

	/**
	 * Get key type.
	 *
	 * @return TypeInterface|NULL -- key type
	 */
	public function getKeyType() {
		return $this->_keyType;
	}

	/**
	 * Get value type.
	 *
	 * @return TypeInterface|NULL -- value type
	 */
	public function getValueType() {
		return $this->_valueType;
	}

	/**
	 * Validate value.
	 *
	 * @param mixed $aValue -- value
	 *
	 * @return ValidationResultInterface -- validation result
	 */
	public function validate($aValue): ValidationResultInterface {
		if (!is_array($aValue) && !($this->_objective && fulltype::is_array_object($aValue))) {
			$type = fulltype::typeof($aValue);
			return new InvalidValueError($this, "Value must be an array, $type given");
		}
		if ($this->_length !== null) {
			$length = count($aValue);
			if ($length !== $this->_length) {
				return new LengthError($this, "Array must contain $this->_length items, $length given");
			}
		}
		if ($this->_keyType !== null || $this->_valueType !== null) {
			foreach ($aValue as $key => $value) {
				if ($this->_keyType !== null) {
					$validationResult = $this->_keyType->validate($key);
					if (!$validationResult->isValid()) {
						return new MemberError($this, "Invalid array key", 0, $validationResult);
					}
				}
				if ($this->_valueType !== null) {
					$validationResult = $this->_valueType->validate($value);
					if (!$validationResult->isValid()) {
						return new MemberError($this, "Invalid array item", 0, $validationResult);
					}
				}
			}
		}
		return fulltype::valid();
	}
}