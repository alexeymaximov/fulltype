<?php

declare(strict_types=1);

use ArsMnemonica\Fulltype\fulltype as t;

require_once __DIR__ . '/../../vendor/autoload.php';

function test($a) {
	return t::assert($a, t::int());
}

test((object)[]);