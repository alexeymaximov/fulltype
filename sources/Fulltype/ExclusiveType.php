<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

/**
 * Exclusive type (strictly one).
 */
class ExclusiveType extends AbstractCompositeType {

	/**
	 * Validate value.
	 *
	 * @param mixed $aValue -- value
	 *
	 * @return ValidationResultInterface -- validation result
	 */
	public function validate($aValue): ValidationResultInterface {
		$types = $this->getTypes();
		if (!$types) {
			return fulltype::valid();
		}
		$causes = [];
		$count = 0;
		foreach ($types as $type) {
			$validationResult = $type->validate($aValue);
			if ($validationResult->isValid()) {
				$count++;
			} else {
				$causes[] = $validationResult;
			}
		}
		if (!$count) {
			return new SubsequentError($this, "Value must meet one of the specified types", 0, ...$causes);
		}
		if ($count > 1) {
			return new AmbiguousError($this, "Value must meet only one of the specified types");
		}
		return fulltype::valid();
	}
}