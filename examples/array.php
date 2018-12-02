<?php

declare(strict_types=1);

use ArsMnemonica\Fulltype\fulltype as t;

require_once __DIR__ . '/../../vendor/autoload.php';

$arrayType = t::array()
	->by(t::string())
	->of(t::int());
$objectiveArrayType = t::array()->objective()
	->by(t::string())
	->of(t::int());

$keyStructType = t::struct(
	t::key('a', t::int()),
	t::key('b', t::int()),
	t::union(
		t::key('c', t::int()),
		t::key('d', t::int())
	)
);
$offsetStructType = t::struct(
	t::offset('a', t::int()),
	t::offset('b', t::int()),
	t::union(
		t::offset('c', t::int()),
		t::offset('d', t::int())
	)
);
$propertyStructType = t::struct(
	t::property('a', t::int()),
	t::property('b', t::int()),
	t::union(
		t::property('c', t::int()),
		t::property('d', t::int())
	)
);

$array = ['a' => 1, 'b' => 2, 'c' => 3];
$interface = new ArrayObject($array);
$object = (object)$array;

echo PHP_EOL;
echo "array is array: " . ($arrayType($array) ? "yes" : "no") . PHP_EOL;
echo "interface is array: " . ($arrayType($interface) ? "yes" : "no") . PHP_EOL;
echo "object is array: " . ($arrayType($object) ? "yes" : "no") . PHP_EOL;
echo PHP_EOL;
echo "array is array (objective): " . ($objectiveArrayType($array) ? "yes" : "no") . PHP_EOL;
echo "interface is array (objective): " . ($objectiveArrayType($interface) ? "yes" : "no") . PHP_EOL;
echo "object is array (objective): " . ($objectiveArrayType($object) ? "yes" : "no") . PHP_EOL;
echo PHP_EOL;
echo "array is struct (key): " . ($keyStructType($array) ? "yes" : "no") . PHP_EOL;
echo "interface is struct (key): " . ($keyStructType($interface) ? "yes" : "no") . PHP_EOL;
echo "object is struct (key): " . ($keyStructType($object) ? "yes" : "no") . PHP_EOL;
echo PHP_EOL;
echo "array is struct (offset): " . ($offsetStructType($array) ? "yes" : "no") . PHP_EOL;
echo "interface is struct (offset): " . ($offsetStructType($interface) ? "yes" : "no") . PHP_EOL;
echo "object is struct (offset): " . ($offsetStructType($object) ? "yes" : "no") . PHP_EOL;
echo PHP_EOL;
echo "array is struct (property): " . ($propertyStructType($array) ? "yes" : "no") . PHP_EOL;
echo "interface is struct (property): " . ($propertyStructType($interface) ? "yes" : "no") . PHP_EOL;
echo "object is struct (property): " . ($propertyStructType($object) ? "yes" : "no") . PHP_EOL;