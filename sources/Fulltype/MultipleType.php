<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

/**
 * Multiple type (at least one).
 */
class MultipleType extends AbstractCompositeType {

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
		foreach ($types as $type) {
			$validationResult = $type->validate($aValue);
			if ($validationResult->isValid()) {
				return fulltype::valid();
			}
			$causes[] = $validationResult;
		}
		return new SubsequentError($this, "Value must meet at least one of the specified types", 0, ...$causes);
	}
}