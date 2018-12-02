<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

/**
 * Resource type.
 */
class ResourceType extends AbstractType {

	/**
	 * Validate value.
	 *
	 * @param mixed $aValue -- value
	 *
	 * @return ValidationResultInterface -- validation result
	 */
	public function validate($aValue): ValidationResultInterface {
		if (!is_resource($aValue)) {
			$type = fulltype::typeof($aValue);
			return new InvalidValueError($this, "Value must be a resource, $type given");
		}
		return fulltype::valid();
	}
}