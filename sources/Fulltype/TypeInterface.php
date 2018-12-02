<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

/**
 * Fulltype interface.
 */
interface TypeInterface {

	/**
	 * Validate value.
	 *
	 * @param mixed $aValue -- value
	 *
	 * @return ValidationResultInterface -- validation result
	 */
	public function validate($aValue): ValidationResultInterface;
}