<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

use InvalidArgumentException;

/**
 * Interface type.
 */
class InterfaceType extends AbstractType {

	/**
	 * @var string|NULL -- base interface
	 */
	private $_base;

	/**
	 * Constructor.
	 *
	 * @param string|NULL $aBase -- base interface
	 *
	 * @throws InvalidArgumentException -- Invalid interface type base
	 */
	public function __construct(string $aBase = null) {
		if ($aBase !== null && !interface_exists($aBase)) {
			throw new InvalidArgumentException("Invalid interface type base");
		}
		$this->_base = $aBase;
	}

	/**
	 * Get base interface.
	 *
	 * @return string|NULL -- base interface
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
		if (!is_string($aValue) || !interface_exists($aValue)) {
			$type = fulltype::typeof($aValue);
			return new InvalidValueError($this, "Value must be an interface name, $type given");
		}
		if ($this->_base !== null && !fulltype::is_compatible($aValue, $this->_base)) {
			return new ClassError($this, "Interface $aValue is not compatible with $this->_base");
		}
		return fulltype::valid();
	}
}