<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

use InvalidArgumentException;
use RuntimeException;

/**
 * Path type.
 */
class PathType extends AbstractType {

	/**
	 * @var string|NULL -- base directory
	 */
	private $_base;

	/**
	 * @var bool -- readable
	 */
	private $_readable;

	/**
	 * @var bool -- writable
	 */
	private $_writable;

	/**
	 * @var bool -- executable
	 */
	private $_executable;

	/**
	 * Constructor.
	 *
	 * @param string|NULL $aBase -- base directory
	 *
	 * @throws InvalidArgumentException -- Invalid path type base
	 */
	public function __construct(string $aBase = null) {
		if ($this->_base !== null && !is_dir($this->_base)) {
			throw new InvalidArgumentException("Invalid path type base");
		}
		$this->_base = $aBase;
	}

	/**
	 * Make readable.
	 *
	 * @return $this
	 *
	 * @throws RuntimeException -- Path type is already readable
	 */
	public function readable() {
		if ($this->_readable) {
			throw new RuntimeException("Path type is already readable");
		}
		$this->_readable = true;
		return $this;
	}

	/**
	 * Make writable.
	 *
	 * @return $this
	 *
	 * @throws RuntimeException -- Path type is already writable
	 */
	public function writable() {
		if ($this->_writable) {
			throw new RuntimeException("Path type is already writable");
		}
		$this->_writable = true;
		return $this;
	}

	/**
	 * Make executable.
	 *
	 * @return $this
	 *
	 * @throws RuntimeException -- Path type is already executable
	 */
	public function executable() {
		if ($this->_executable) {
			throw new RuntimeException("Path type is already executable");
		}
		$this->_executable = true;
		return $this;
	}

	/**
	 * Get base directory.
	 *
	 * @return string|NULL -- base directory
	 */
	public function getBase() {
		return $this->_base;
	}

	/**
	 * Whether is readable or not.
	 *
	 * @return bool -- readable
	 */
	public function isReadable(): bool {
		return $this->_readable;
	}

	/**
	 * Whether is writable or not.
	 *
	 * @return bool -- writable
	 */
	public function isWritable(): bool {
		return $this->_writable;
	}

	/**
	 * Whether is executable or not.
	 *
	 * @return bool -- executable
	 */
	public function isExecutable(): bool {
		return $this->_executable;
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
		if (!file_exists($aValue)) {
			return new PathError($this, "Value must be an existing path");
		}
		if ($this->_readable && !is_readable($aValue)) {
			return new PathError($this, "Path \"$aValue\" is not readable");
		}
		if ($this->_writable && !is_writable($aValue)) {
			return new PathError($this, "Path \"$aValue\" is not writable");
		}
		if ($this->_executable && !is_executable($aValue)) {
			return new PathError($this, "Path \"$aValue\" is not executable");
		}
		if ($this->_base !== null) {
			// TODO: Check base directory
		}
		return fulltype::valid();
	}
}