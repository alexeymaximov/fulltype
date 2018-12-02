<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

/**
 * Complex type (all).
 */
class ComplexType extends AbstractCompositeType {

	/**
	 * Validate value.
	 *
	 * @param mixed $aValue -- value
	 *
	 * @return ValidationResultInterface -- validation result
	 */
	public function validate($aValue): ValidationResultInterface {
		$causes = [];
		foreach ($this->getTypes() as $type) {
			$validationResult = $type->validate($aValue);
			if (!$validationResult->isValid()) {
				$causes[] = $validationResult;
			}
		}
		if ($causes) {
			return new SubsequentError($this, "Value must meet all of the specified types", 0, ...$causes);
		}
		return fulltype::valid();
	}
}