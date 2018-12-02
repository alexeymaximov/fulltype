<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

/**
 * Property type.
 */
class PropertyType extends AbstractMemberType {

	/**
	 * Validate value.
	 *
	 * @param mixed $aValue -- value
	 *
	 * @return ValidationResultInterface -- validation result
	 */
	public function validate($aValue): ValidationResultInterface {
		$name = $this->getName();
		if (is_object($aValue)) {
			if (!isset($aValue->{$name})) {
				if ($this->isOptional()) {
					return fulltype::valid();
				}
				return new MemberError($this, "Member \"$name\" not found in object");
			}
			return $this->validateAssociated($aValue->{$name});
		}
		$type = fulltype::typeof($aValue);
		return new InvalidValueError($this, "Value must be an object, $type given");
	}
}