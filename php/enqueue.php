<?php
namespace SIM\BANKING;
use SIM;

//load js and css
add_action( 'wp_enqueue_scripts', function(){
    wp_register_style('sim_account_statements_style', SIM\pathToUrl(MODULE_PATH.'css/banking.min.css'), array(), MODULE_VERSION);
	wp_register_script('sim_account_statements_script', SIM\pathToUrl(MODULE_PATH.'js/account_statements.min.js'), array(), MODULE_VERSION, true);
});