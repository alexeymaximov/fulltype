<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

/**
 * Validation error.
 */
class ValidationError implements ValidationResultInterface {

	/**
	 * @var TypeInterface -- type
	 */
	private $_type;

	/**
	 * @var int -- code
	 */
	private $_code;

	/**
	 * @var string -- message
	 */
	private $_message;

	/**
	 * @var ValidationResultInterface[] -- causes
	 */
	private $_causes;

	/**
	 * Constructor.
	 *
	 * @param TypeInterface $aType -- type
	 * @param string $aMessage -- message
	 * @param int $aCode -- code
	 * @param ValidationResultInterface[] $aCauses -- causes
	 */
	public function __construct(TypeInterface $aType, string $aMessage = '', int $aCode = 0, ValidationResultInterface ...$aCauses) {
		$this->_type = $aType;
		$this->_code = $aCode;
		$this->_message = $aMessage;
		$this->_causes = $aCauses;
	}

	/**
	 * Whether is value valid or not.
	 *
	 * @return bool -- value is valid
	 */
	public function isValid(): bool {
		return false;
	}

	/**
	 * Get type.
	 *
	 * @return TypeInterface -- type
	 */
	public function getType(): TypeInterface {
		return $this->_type;
	}

	/**
	 * Get code.
	 *
	 * @return int -- code
	 */
	public function getCode(): int {
		return $this->_code;
	}

	/**
	 * Get message.
	 *
	 * @return string -- message
	 */
	public function getMessage(): string {
		return $this->_message;
	}

	/**
	 * Get causes.
	 *
	 * @return ValidationResultInterface[] -- causes
	 */
	public function getCauses(): array {
		return $this->_causes;
	}
}