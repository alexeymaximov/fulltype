<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

use ArrayAccess;

/**
 * Offset type.
 */
class OffsetType extends AbstractMemberType {

	/**
	 * Validate value.
	 *
	 * @param mixed $aValue -- value
	 *
	 * @return ValidationResultInterface -- validation result
	 */
	public function validate($aValue): ValidationResultInterface {
		$name = $this->getName();
		if (is_array($aValue)) {
			if (!array_key_exists($name, $aValue)) {
				if ($this->isOptional()) {
					return fulltype::valid();
				}
				return new MemberError($this, "Member \"$name\" not found in array");
			}
			return $this->validateAssociated($aValue[$name]);
		}
		if ($aValue instanceof ArrayAccess) {
			if (!isset($aValue[$name])) {
				if ($this->isOptional()) {
					return fulltype::valid();
				}
				return new MemberError($this, "Member \"$name\" not found in instance of ArrayAccess");
			}
			return $this->validateAssociated($aValue[$name]);
		}
		$type = fulltype::typeof($aValue);
		return new InvalidValueError($this, "Value must be an array or instance of ArrayAccess, $type given");
	}
}