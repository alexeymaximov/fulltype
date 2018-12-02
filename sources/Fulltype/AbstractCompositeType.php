<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

/**
 * Abstract composite type.
 */
abstract class AbstractCompositeType extends AbstractType {

	/**
	 * @var TypeInterface[] -- types
	 */
	private $_types;

	/**
	 * Constructor.
	 *
	 * @param TypeInterface[] $aTypes -- types
	 */
	public function __construct(TypeInterface ...$aTypes) {
		$this->_types = $aTypes;
	}

	/**
	 * Get types.
	 *
	 * @return TypeInterface[] -- types
	 */
	public function getTypes(): array {
		return $this->_types;
	}
}