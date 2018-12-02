<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

/**
 * Directory type.
 */
class DirectoryType extends PathType {

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
		if (!is_dir($aValue)) {
			return new PathError($this, "Value must be a path to existing directory");
		}
		return parent::validate($aValue);
	}
}