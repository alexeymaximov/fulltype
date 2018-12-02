<?php

declare(strict_types=1);

namespace ArsMnemonica\Fulltype;

use ArrayAccess;
use Countable;
use RuntimeException;
use Traversable;
use TypeError;

/**
 * Type validation.
 */
final class fulltype {

	/**
	 * @var TypeInterface[] -- custom types
	 */
	private static $_types = [];

	/**
	 * Create complex type.
	 *
	 * @param TypeInterface[] $aTypes -- types
	 *
	 * @return ComplexType -- complex type
	 */
	public static function all(TypeInterface ...$aTypes): ComplexType {
		return new ComplexType(...$aTypes);
	}

	/**
	 * Create nullable complex type.
	 *
	 * @param TypeInterface[] $aTypes -- types
	 *
	 * @return NullableType -- nullable complex type
	 */
	public static function all？(TypeInterface ...$aTypes): NullableType {
		return new NullableType(new ComplexType(...$aTypes));
	}

	/**
	 * Create multiple type.
	 *
	 * @param TypeInterface[] $aTypes -- types
	 *
	 * @return MultipleType -- multiple type
	 */
	public static function any(TypeInterface ...$aTypes): MultipleType {
		return new MultipleType(...$aTypes);
	}

	/**
	 * Create nullable multiple type.
	 *
	 * @param TypeInterface[] $aTypes -- types
	 *
	 * @return NullableType -- nullable multiple type
	 */
	public static function any？(TypeInterface ...$aTypes): NullableType {
		return new NullableType(new MultipleType(...$aTypes));
	}

	/**
	 * Create array type.
	 *
	 * @param int|NULL $aLength -- length
	 *
	 * @return ArrayType -- array type
	 */
	public static function array(int $aLength = null): ArrayType {
		return new ArrayType($aLength);
	}

	/**
	 * Create nullable array type.
	 *
	 * @param int|NULL $aLength -- length
	 *
	 * @return NullableType -- nullable array type
	 */
	public static function array？(int $aLength = null): NullableType {
		return new NullableType(new ArrayType($aLength));
	}

	/**
	 * Assert value type.
	 *
	 * @param mixed $aValue -- value
	 * @param TypeInterface $aType -- type
	 *
	 * @return mixed -- value
	 *
	 * @throws TypeError -- Unexpected %s in file "%s" on line %d
	 */
	public static function assert($aValue, TypeInterface $aType) {
		$validationResult = $aType->validate($aValue);
		if ($validationResult->isValid()) {
			return $aValue;
		}
		if ($validationResult instanceof ValidationError) {
			$message = $validationResult->getMessage();
		} else {
			$type = fulltype::typeof($aValue);
			$message = "Unexpected $type";
		}
		if ($debug = debug_backtrace(0)) {
			$file = $debug[0]['file'];
			$line = $debug[0]['line'];
		} else {
			$file = __FILE__;
			$line = __LINE__;
		}
		throw new class($message, $file, $line) extends TypeError {

			/**
			 * Constructor.
			 *
			 * @param string $aMessage -- message
			 * @param string $aFile -- path to file where assertion was caused
			 * @param int $aLine -- file line number where assertion was caused
			 */
			public function __construct(string $aMessage, string $aFile, int $aLine) {
				parent::__construct($aMessage);
				$this->file = $aFile;
				$this->line = $aLine;
			}
		};
	}

	/**
	 * Create boolean type.
	 *
	 * @return BooleanType -- boolean type
	 */
	public static function bool(): BooleanType {
		return new BooleanType();
	}

	/**
	 * Create nullable boolean type.
	 *
	 * @return NullableType -- nullable boolean type
	 */
	public static function bool？(): NullableType {
		return new NullableType(new BooleanType());
	}

	/**
	 * Create callable type.
	 *
	 * @return CallableType -- callable type
	 */
	public static function callable(): CallableType {
		return new CallableType();
	}

	/**
	 * Create nullable callable type.
	 *
	 * @return NullableType -- nullable callable type
	 */
	public static function callable？(): NullableType {
		return new NullableType(new CallableType());
	}

	/**
	 * Create cardinal type.
	 *
	 * @param int|NULL $aMax -- maximal value
	 *
	 * @return CardinalType -- cardinal type
	 */
	public static function cardinal(int $aMax = null): CardinalType {
		return new CardinalType($aMax);
	}

	/**
	 * Create nullable cardinal type.
	 *
	 * @param int|NULL $aMax -- maximal value
	 *
	 * @return NullableType -- nullable cardinal type
	 */
	public static function cardinal？(int $aMax = null): NullableType {
		return new NullableType(new CardinalType($aMax));
	}

	/**
	 * Create character type.
	 *
	 * @param int $aLength -- length
	 *
	 * @return CharacterType -- character type
	 */
	public static function char(int $aLength = 1): CharacterType {
		return new CharacterType($aLength);
	}

	/**
	 * Create nullable character type.
	 *
	 * @param int $aLength -- length
	 *
	 * @return NullableType -- nullable character type
	 */
	public static function char？(int $aLength = 1): NullableType {
		return new NullableType(new CharacterType($aLength));
	}

	/**
	 * Create class type.
	 *
	 * @param string|NULL $aBase -- base class or interface
	 *
	 * @return ClassType -- class type
	 */
	public static function class(string $aBase = null): ClassType {
		return new ClassType($aBase);
	}

	/**
	 * Create nullable class type.
	 *
	 * @param string|NULL $aBase -- base class or interface
	 *
	 * @return NullableType -- nullable class type
	 */
	public static function class？(string $aBase = null): NullableType {
		return new NullableType(new ClassType($aBase));
	}

	/**
	 * Define custom type.
	 *
	 * @param string $aName -- name
	 * @param TypeInterface $aType -- type
	 *
	 * @return TypeInterface -- type
	 *
	 * @throws RuntimeException -- Type "%s" is already defined
	 */
	public static function define(string $aName, TypeInterface $aType): TypeInterface {
		if (array_key_exists($aName, self::$_types)) {
			throw new RuntimeException("Type \"$aName\" is already defined");
		}
		self::$_types[$aName] = $aType;
		return $aType;
	}

	/**
	 * Create directory type.
	 *
	 * @param string $aBase -- base directory
	 *
	 * @return DirectoryType -- directory type
	 */
	public static function dir(string $aBase = null): DirectoryType {
		return new DirectoryType($aBase);
	}

	/**
	 * Create nullable directory type.
	 *
	 * @param string $aBase -- base directory
	 *
	 * @return NullableType -- nullable directory type
	 */
	public static function dir？(string $aBase = null): NullableType {
		return new NullableType(new DirectoryType($aBase));
	}

	/**
	 * Create enumerable type.
	 *
	 * @param array $aValues -- values
	 *
	 * @return EnumerableType -- enumerable type
	 */
	public static function enum(...$aValues): EnumerableType {
		return new EnumerableType(...$aValues);
	}

	/**
	 * Create nullable enumerable type.
	 *
	 * @param array $aValues -- values
	 *
	 * @return NullableType -- nullable enumerable type
	 */
	public static function enum？(...$aValues): NullableType {
		return new NullableType(new EnumerableType(...$aValues));
	}

	/**
	 * Create file type.
	 *
	 * @param string $aBase -- base directory
	 *
	 * @return FileType -- file type
	 */
	public static function file(string $aBase = null): FileType {
		return new FileType($aBase);
	}

	/**
	 * Create nullable file type.
	 *
	 * @param string $aBase -- base directory
	 *
	 * @return NullableType -- nullable file type
	 */
	public static function file？(string $aBase = null): NullableType {
		return new NullableType(new FileType($aBase));
	}

	/**
	 * Create integer type.
	 *
	 * @param int|NULL $aMin -- minimal value
	 * @param int|NULL $aMax -- maximal value
	 *
	 * @return IntegerType -- integer type
	 */
	public static function int(int $aMin = null, int $aMax = null): IntegerType {
		return new IntegerType($aMin, $aMax);
	}

	/**
	 * Create nullable integer type.
	 *
	 * @param int|NULL $aMin -- minimal value
	 * @param int|NULL $aMax -- maximal value
	 *
	 * @return NullableType -- nullable integer type
	 */
	public static function int？(int $aMin = null, int $aMax = null): NullableType {
		return new NullableType(new IntegerType($aMin, $aMax));
	}

	/**
	 * Create interface type.
	 *
	 * @param string|NULL $aBase -- base interface
	 *
	 * @return InterfaceType -- interface type
	 */
	public static function interface(string $aBase = null): InterfaceType {
		return new InterfaceType($aBase);
	}

	/**
	 * Create nullable interface type.
	 *
	 * @param string|NULL $aBase -- base interface
	 *
	 * @return NullableType -- nullable interface type
	 */
	public static function interface？(string $aBase = null): NullableType {
		return new NullableType(new InterfaceType($aBase));
	}

	/**
	 * Validate value.
	 *
	 * @param mixed $aValue -- value
	 * @param TypeInterface $aType -- type
	 *
	 * @return bool -- value is valid
	 */
	public static function is($aValue, TypeInterface $aType): bool {
		$validationResult = $aType->validate($aValue);
		return $validationResult->isValid();
	}

	/**
	 * Whether value is array object or not.
	 *
	 * @param mixed $aValue -- value
	 *
	 * @return bool -- value is array object
	 */
	public static function is_array_object($aValue): bool {
		return $aValue instanceof ArrayAccess && $aValue instanceof Countable && $aValue instanceof Traversable;
	}

	/**
	 * Whether is class compatible with base or not.
	 *
	 * @param string $aName -- class or interface
	 * @param string $aBase -- base class or interface
	 *
	 * @return bool -- class is compatible with base
	 */
	public static function is_compatible(string $aName, string $aBase): bool {
		if (!class_exists($aName) && !interface_exists($aName)) {
			return false;
		}
		if (!class_exists($aBase) && !interface_exists($aBase)) {
			return false;
		}
		return $aName === $aBase || is_subclass_of($aName, $aBase);
	}

	/**
	 * Create iterable type.
	 *
	 * @return IterableType -- iterable type
	 */
	public static function iterable(): IterableType {
		return new IterableType();
	}

	/**
	 * Create nullable iterable type.
	 *
	 * @return NullableType -- nullable iterable type
	 */
	public static function iterable？(): NullableType {
		return new NullableType(new IterableType());
	}

	/**
	 * Create key type.
	 *
	 * @param string $aName -- name
	 * @param TypeInterface|NULL $aType -- type
	 *
	 * @return KeyType -- key type
	 */
	public static function key(string $aName, TypeInterface $aType = null): KeyType {
		return new KeyType($aName, $aType);
	}

	/**
	 * Create optional key type.
	 *
	 * @param string $aName -- name
	 * @param TypeInterface|NULL $aType -- type
	 *
	 * @return KeyType -- optional key type
	 */
	public static function key✱(string $aName, TypeInterface $aType = null): KeyType {
		return (new KeyType($aName, $aType))->optional();
	}

	/**
	 * Create link type.
	 *
	 * @param string $aBase -- base directory
	 *
	 * @return LinkType -- link type
	 */
	public static function link(string $aBase = null): LinkType {
		return new LinkType($aBase);
	}

	/**
	 * Create nullable link type.
	 *
	 * @param string $aBase -- base directory
	 *
	 * @return NullableType -- nullable link type
	 */
	public static function link？(string $aBase = null): NullableType {
		return new NullableType(new LinkType($aBase));
	}

	/**
	 * Create numeric type.
	 *
	 * @param float|NULL $aMin -- minimal value
	 * @param float|NULL $aMax -- maximal value
	 *
	 * @return NumericType -- numeric type
	 */
	public static function number(float $aMin = null, float $aMax = null): NumericType {
		return new NumericType($aMin, $aMax);
	}

	/**
	 * Create nullable numeric type.
	 *
	 * @param float|NULL $aMin -- minimal value
	 * @param float|NULL $aMax -- maximal value
	 *
	 * @return NullableType -- nullable numeric type
	 */
	public static function number？(float $aMin = null, float $aMax = null): NullableType {
		return new NullableType(new NumericType($aMin, $aMax));
	}

	/**
	 * Create nullable type.
	 *
	 * @param TypeInterface $aType -- type
	 *
	 * @return NullableType -- nullable type
	 */
	public static function null(TypeInterface $aType): NullableType {
		return new NullableType($aType);
	}

	/**
	 * Create object type.
	 *
	 * @param string|NULL $aBase -- base class or interface
	 *
	 * @return ObjectType -- object type
	 */
	public static function object(string $aBase = null): ObjectType {
		return new ObjectType($aBase);
	}

	/**
	 * Create nullable object type.
	 *
	 * @param string|NULL $aBase -- base class or interface
	 *
	 * @return NullableType -- nullable object type
	 */
	public static function object？(string $aBase = null): NullableType {
		return new NullableType(new ObjectType($aBase));
	}

	/**
	 * Create offset type.
	 *
	 * @param string $aName -- name
	 * @param TypeInterface|NULL $aType -- type
	 *
	 * @return OffsetType -- offset type
	 */
	public static function offset(string $aName, TypeInterface $aType = null): OffsetType {
		return new OffsetType($aName, $aType);
	}

	/**
	 * Create optional offset type.
	 *
	 * @param string $aName -- name
	 * @param TypeInterface|NULL $aType -- type
	 *
	 * @return OffsetType -- optional offset type
	 */
	public static function offset✱(string $aName, TypeInterface $aType = null): OffsetType {
		return (new OffsetType($aName, $aType))->optional();
	}

	/**
	 * Create exclusive type.
	 *
	 * @param TypeInterface[] $aTypes -- types
	 *
	 * @return ExclusiveType -- exclusive type
	 */
	public static function one(TypeInterface ...$aTypes): ExclusiveType {
		return new ExclusiveType(...$aTypes);
	}

	/**
	 * Create nullable exclusive type.
	 *
	 * @param TypeInterface[] $aTypes -- types
	 *
	 * @return NullableType -- nullable exclusive type
	 */
	public static function one？(TypeInterface ...$aTypes): NullableType {
		return new NullableType(new ExclusiveType(...$aTypes));
	}

	/**
	 * Create path type.
	 *
	 * @param string $aBase -- base directory
	 *
	 * @return PathType -- path type
	 */
	public static function path(string $aBase = null): PathType {
		return new PathType($aBase);
	}

	/**
	 * Create nullable path type.
	 *
	 * @param string $aBase -- base directory
	 *
	 * @return NullableType -- nullable path type
	 */
	public static function path？(string $aBase = null): NullableType {
		return new NullableType(new PathType($aBase));
	}

	/**
	 * Create property type.
	 *
	 * @param string $aName -- name
	 * @param TypeInterface|NULL $aType -- type
	 *
	 * @return PropertyType -- property type
	 */
	public static function property(string $aName, TypeInterface $aType = null): PropertyType {
		return new PropertyType($aName, $aType);
	}

	/**
	 * Create optional property type.
	 *
	 * @param string $aName -- name
	 * @param TypeInterface|NULL $aType -- type
	 *
	 * @return PropertyType -- optional property type
	 */
	public static function property✱(string $aName, TypeInterface $aType = null): PropertyType {
		return (new PropertyType($aName, $aType))->optional();
	}

	/**
	 * Create regular expression type.
	 *
	 * @param string $aRegularExpression -- regular expression
	 *
	 * @return RegularExpressionType -- regular expression type
	 */
	public static function regexp(string $aRegularExpression): RegularExpressionType {
		return new RegularExpressionType($aRegularExpression);
	}

	/**
	 * Create nullable regular expression type.
	 *
	 * @param string $aRegularExpression -- regular expression
	 *
	 * @return NullableType -- nullable regular expression type
	 */
	public static function regexp？(string $aRegularExpression): NullableType {
		return new NullableType(new RegularExpressionType($aRegularExpression));
	}

	/**
	 * Create resource type.
	 *
	 * @return ResourceType -- resource type
	 */
	public static function resource(): ResourceType {
		return new ResourceType();
	}

	/**
	 * Create nullable resource type.
	 *
	 * @return NullableType -- nullable resource type
	 */
	public static function resource？(): NullableType {
		return new NullableType(new ResourceType());
	}

	/**
	 * Create scalar type.
	 *
	 * @return ScalarType -- scalar type
	 */
	public static function scalar(): ScalarType {
		return new ScalarType();
	}

	/**
	 * Create nullable scalar type.
	 *
	 * @return NullableType --nullable scalar type
	 */
	public static function scalar？(): NullableType {
		return new NullableType(new ScalarType());
	}

	/**
	 * Create string type.
	 *
	 * @param int|NULL $aLength -- length
	 *
	 * @return StringType -- string type
	 */
	public static function string(int $aLength = null): StringType {
		return new StringType($aLength);
	}

	/**
	 * Create nullable string type.
	 *
	 * @param int|NULL $aLength -- length
	 *
	 * @return NullableType -- nullable string type
	 */
	public static function string？(int $aLength = null): NullableType {
		return new NullableType(new StringType($aLength));
	}

	/**
	 * Create complex type.
	 *
	 * @param TypeInterface[] $aTypes -- types
	 *
	 * @return ComplexType -- complex type
	 */
	public static function struct(TypeInterface ...$aTypes): ComplexType {
		return new ComplexType(...$aTypes);
	}

	/**
	 * Create nullable complex type.
	 *
	 * @param TypeInterface[] $aTypes -- types
	 *
	 * @return NullableType -- nullable complex type
	 */
	public static function struct？(TypeInterface ...$aTypes): NullableType {
		return new NullableType(new ComplexType(...$aTypes));
	}

	/**
	 * Get custom type.
	 *
	 * @param string $aName -- name
	 *
	 * @return TypeInterface -- type
	 *
	 * @throws RuntimeException -- Type "%s" is not defined
	 */
	public static function type(string $aName): TypeInterface {
		if (!array_key_exists($aName, self::$_types)) {
			throw new RuntimeException("Type \"$aName\" is not defined");
		}
		return self::$_types[$aName];
	}

	/**
	 * Create nullable custom type.
	 *
	 * @param string $aName -- name
	 *
	 * @return NullableType -- nullable custom type
	 */
	public static function type？(string $aName): NullableType {
		return new NullableType(self::type($aName));
	}

	/**
	 * Get value type.
	 *
	 * @param mixed $aValue -- value
	 *
	 * @return string -- type
	 */
	public static function typeof($aValue): string {
		return is_object($aValue) ? 'instance of ' . get_class($aValue) : gettype($aValue);
	}

	/**
	 * Create exclusive type.
	 *
	 * @param TypeInterface[] $aTypes -- types
	 *
	 * @return ExclusiveType -- exclusive type
	 */
	public static function union(TypeInterface ...$aTypes): ExclusiveType {
		return new ExclusiveType(...$aTypes);
	}

	/**
	 * Create nullable exclusive type.
	 *
	 * @param TypeInterface[] $aTypes -- types
	 *
	 * @return NullableType -- nullable exclusive type
	 */
	public static function union？(TypeInterface ...$aTypes): NullableType {
		return new NullableType(new ExclusiveType(...$aTypes));
	}

	/**
	 * Get validation success.
	 *
	 * @return ValidationSuccess -- validation success
	 */
	public static function valid(): ValidationSuccess {
		return ValidationSuccess::getInstance();
	}

	/**
	 * Create vector type.
	 *
	 * @param int|NULL $aLength -- length
	 *
	 * @return VectorType -- vector type
	 */
	public static function vector(int $aLength = null): VectorType {
		return new VectorType($aLength);
	}

	/**
	 * Create nullable vector type.
	 *
	 * @param int|NULL $aLength -- length
	 *
	 * @return NullableType -- nullable vector type
	 */
	public static function vector？(int $aLength = null): NullableType {
		return new NullableType(new VectorType($aLength));
	}
}