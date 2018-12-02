<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

/**
 * Validation result interface.
 */
interface ValidationResultInterface {

	/**
	 * Whether is value valid or not.
	 *
	 * @return bool -- value is valid
	 */
	public function isValid(): bool;
}