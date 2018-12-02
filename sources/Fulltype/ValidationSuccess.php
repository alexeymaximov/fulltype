<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

/**
 * Validation success.
 */
class ValidationSuccess implements ValidationResultInterface {

	/**
	 * @var ValidationSuccess -- instance
	 */
	private static $_instance = null;

	/**
	 * Get instance.
	 *
	 * @return ValidationSuccess -- instance
	 */
	public static function getInstance(): ValidationSuccess {
		if (self::$_instance === null) {
			self::$_instance = new ValidationSuccess();
		}
		return self::$_instance;
	}

	/**
	 * Whether is value valid or not.
	 *
	 * @return bool -- value is valid
	 */
	public function isValid(): bool {
		return true;
	}
}