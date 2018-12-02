<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

use InvalidArgumentException;

/**
 * Cardinal type.
 */
class CardinalType extends IntegerType {

	/**
	 * Constructor.
	 *
	 * @param int|NULL $aMax -- maximal value
	 *
	 * @throws InvalidArgumentException -- Maximal cardinal type value must be greater than 0
	 */
	public function __construct(int $aMax = null) {
		if ($aMax !== null && $aMax <= 0) {
			throw new InvalidArgumentException("Maximal cardinal type value must be greater than 0");
		}
		parent::__construct(0, $aMax);
	}
}