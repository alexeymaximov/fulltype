<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

/**
 * Abstract type.
 */
abstract class AbstractType implements TypeInterface {

	/**
	 * Invocation.
	 *
	 * @param mixed $aValue -- value
	 *
	 * @return bool -- invocation result
	 */
	public function __invoke($aValue): bool {
		$validationResult = $this->validate($aValue);
		return $validationResult->isValid();
	}
}