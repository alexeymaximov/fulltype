<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

/**
 * Key type.
 */
class KeyType extends AbstractMemberType {

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
		$type = fulltype::typeof($aValue);
		return new InvalidValueError($this, "Value must be an array, $type given");
	}
}