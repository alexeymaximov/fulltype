<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

use InvalidArgumentException;

/**
 * Class type.
 */
class ClassType extends AbstractType {

	/**
	 * @var string|NULL -- base class or interface
	 */
	private $_base;

	/**
	 * Constructor.
	 *
	 * @param string|NULL $aBase -- base class or interface
	 *
	 * @throws InvalidArgumentException -- Invalid class type base
	 */
	public function __construct(string $aBase = null) {
		if ($aBase !== null && !class_exists($aBase) && !interface_exists($aBase)) {
			throw new InvalidArgumentException("Invalid class type base");
		}
		$this->_base = $aBase;
	}

	/**
	 * Get base class or interface.
	 *
	 * @return string|NULL -- base class or interface
	 */
	public function getBase() {
		return $this->_base;
	}

	/**
	 * Validate value.
	 *
	 * @param mixed $aValue -- value
	 *
	 * @return ValidationResultInterface -- validation result
	 */
	public function validate($aValue): ValidationResultInterface {
		if (!is_string($aValue) || !class_exists($aValue)) {
			$type = fulltype::typeof($aValue);
			return new InvalidValueError($this, "Value must be a class name, $type given");
		}
		if ($this->_base !== null && !fulltype::is_compatible($aValue, $this->_base)) {
			return new ClassError($this, "Class $aValue is not compatible with $this->_base");
		}
		return fulltype::valid();
	}
}