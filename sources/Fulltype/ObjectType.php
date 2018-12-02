<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

use InvalidArgumentException;

/**
 * Object type.
 */
class ObjectType extends AbstractType {

	/**
	 * @var string|NULL -- base class or interface
	 */
	private $_base;

	/**
	 * Constructor.
	 *
	 * @param string|NULL $aBase -- base class or interface
	 *
	 * @throws InvalidArgumentException -- Invalid object type base
	 */
	public function __construct(string $aBase = null) {
		if ($aBase !== null && !class_exists($aBase) && !interface_exists($aBase)) {
			throw new InvalidArgumentException("Invalid object type base");
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
		if (!is_object($aValue)) {
			$type = fulltype::typeof($aValue);
			return new InvalidValueError($this, "Value must be an object, $type given");
		}
		if ($this->_base !== null && !($aValue instanceof $this->_base)) {
			$class = get_class($aValue);
			return new ClassError($this, "Instance of $class is not compatible with $this->_base");
		}
		return fulltype::valid();
	}
}