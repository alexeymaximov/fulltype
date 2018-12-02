<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

use InvalidArgumentException;

/**
 * Enumerable type.
 */
class EnumerableType extends AbstractType {

	/**
	 * @var array -- items
	 */
	private $_items;

	/**
	 * Constructor.
	 *
	 * @param array $aItems -- items
	 *
	 * @throws InvalidArgumentException -- Enumerable type items are not defined
	 */
	public function __construct(...$aItems) {
		if (!$aItems) {
			throw new InvalidArgumentException("Enumerable type items are not defined");
		}
		$this->_items = $aItems;
	}

	/**
	 * Get values.
	 *
	 * @return array -- values
	 */
	public function getItems(): array {
		return $this->_items;
	}

	/**
	 * Validate value.
	 *
	 * @param mixed $aValue -- value
	 *
	 * @return ValidationResultInterface -- validation result
	 */
	public function validate($aValue): ValidationResultInterface {
		if (!in_array($aValue, $this->_items, true)) {
			return new MatchError($this, "Value does not match any of enumeration items");
		}
		return fulltype::valid();
	}
}