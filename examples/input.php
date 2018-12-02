<?php

// VALID: name="The Lord of the Rings" authors[]="J. R. R. Tolkien" content[title]="The Return of the King" content[text]=... status=READ
// INVALID: name="The Lord of the Rings" authors[]="J. R. R. Tolkien" status=READ
// INVALID: name="The Lord of the Rings" authors[]="J. R. R. Tolkien" text=... content[title]="The Return of the King" content[text]=... status=READ

declare(strict_types=1);

use ArsMnemonica\Fulltype\fulltype as t;

require_once __DIR__ . '/../vendor/autoload.php';

const input_t = 'input';

t::define(input_t, t::struct(
	t::key('name', t::string()),
	t::key('authors', t::any(
		t::string(),
		t::array()->of(t::string())
	)),
	t::union(
		t::key('text', t::string()),
		t::key('content', t::struct(
			t::key('title', t::string(255)),
			t::key✱('annotation', t::string(65535)),
			t::key('text', t::string()),
			t::key✱('pages', t::cardinal？(5000))
		))
	),
	t::key('status', t::enum('WILL_READ', 'READ', 'FAVORITE_BOOK'))
));

echo "Processing input...\n";
if (PHP_SAPI === 'cli') {
	$input = [];
	parse_str(implode('&', array_slice($argv, 1)), $input);
} else {
	$input = $_GET;
}
foreach ($input as $key => $value) {
	echo "$key: " . json_encode($value) . "\n";
}
echo "Validation: " . (t::is($input, t::type(input_t)) ? 'success' : 'failed') . PHP_EOL;