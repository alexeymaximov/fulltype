<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

/**
 * Nullable type.
 */
class NullableType extends AbstractType {

	/**
	 * @var TypeInterface -- type
	 */
	private $_type;

	/**
	 * Constructor.
	 *
	 * @param TypeInterface $aType -- type
	 */
	public function __construct(TypeInterface $aType) {
		$this->_type = $aType;
	}

	/**
	 * Get type.
	 *
	 * @return TypeInterface -- type
	 */
	public function getType(): TypeInterface {
		return $this->_type;
	}

	/**
	 * Validate value.
	 *
	 * @param mixed $aValue -- value
	 *
	 * @return ValidationResultInterface -- validation result
	 */
	public function validate($aValue): ValidationResultInterface {
		if ($aValue === null) {
			return fulltype::valid();
		}
		return $this->_type->validate($aValue);
	}
}