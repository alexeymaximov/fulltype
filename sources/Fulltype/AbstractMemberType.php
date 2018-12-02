<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

use RuntimeException;

/**
 * Abstract member type.
 */
abstract class AbstractMemberType extends AbstractType {

	/**
	 * @var string -- name
	 */
	private $_name;

	/**
	 * @var TypeInterface|NULL -- type
	 */
	private $_type;

	/**
	 * @var bool -- optional
	 */
	private $_optional = false;

	/**
	 * Constructor.
	 *
	 * @param string $aName -- name
	 * @param TypeInterface|NULL $aType -- type
	 */
	public function __construct(string $aName, TypeInterface $aType = null) {
		$this->_name = $aName;
		$this->_type = $aType;
	}

	/**
	 * Make optional.
	 *
	 * @return $this
	 *
	 * @throws RuntimeException -- Member is already optional
	 */
	public function optional() {
		if ($this->_optional) {
			throw new RuntimeException("Member  is already optional");
		}
		$this->_optional = true;
		return $this;
	}

	/**
	 * Get name.
	 *
	 * @return string -- name
	 */
	public function getName(): string {
		return $this->_name;
	}

	/**
	 * Get type.
	 *
	 * @return TypeInterface|NULL -- type
	 */
	public function getType() {
		return $this->_type;
	}

	/**
	 * Whether is optional or not.
	 *
	 * @return bool -- optional
	 */
	public function isOptional(): bool {
		return $this->_optional;
	}

	/**
	 * Validate associated value.
	 *
	 * @param mixed $aValue -- value
	 *
	 * @return ValidationResultInterface -- validation result
	 */
	public function validateAssociated($aValue): ValidationResultInterface {
		if ($this->_type === null) {
			return fulltype::valid();
		}
		$validationResult = $this->_type->validate($aValue);
		if ($validationResult->isValid()) {
			return fulltype::valid();
		}
		return new MemberError($this, "Invalid member \"$this->_name\"", 0, $validationResult);
	}
}