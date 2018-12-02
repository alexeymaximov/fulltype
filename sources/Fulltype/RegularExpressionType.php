<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

/**
 * Regular expression type.
 */
class RegularExpressionType extends AbstractType {

	/**
	 * @var string -- regular expression
	 */
	private $_regularExpression;

	/**
	 * Constructor.
	 *
	 * @param string $aRegularExpression -- regular expression
	 */
	public function __construct(string $aRegularExpression) {
		$this->_regularExpression = $aRegularExpression;
	}

	/**
	 * Get regular expression.
	 *
	 * @return string -- regular expression
	 */
	public function getRegularExpression(): string {
		return $this->_regularExpression;
	}

	/**
	 * Validate value.
	 *
	 * @param mixed $aValue -- value
	 *
	 * @return ValidationResultInterface -- validation result
	 */
	public function validate($aValue): ValidationResultInterface {
		if (!is_string($aValue)) {
			$type = fulltype::typeof($aValue);
			return new InvalidValueError($this, "Value must be a string, $type given");
		}
		if (!preg_match($this->_regularExpression, $aValue)) {
			return new MatchError($this, "String must be compatible with regular expression \"$this->_regularExpression\"");
		}
		return fulltype::valid();
	}
}