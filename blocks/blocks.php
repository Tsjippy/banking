<?php
namespace SIM\BANKING;
use SIM;

add_action('init', __NAMESPACE__.'\blockInit');
function blockInit() {
	register_block_type(
		__DIR__ . '/../blocks/statements/build',
		array(
			'render_callback' => __NAMESPACE__.'\showStatements',
		)
	);
}