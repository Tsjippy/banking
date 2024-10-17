<?php
namespace SIM\BANKING;
use SIM;

const MODULE_VERSION		= '8.0.1';
DEFINE(__NAMESPACE__.'\MODULE_PATH', plugin_dir_path(__DIR__));
DEFINE(__NAMESPACE__.'\MODULE_SLUG', strtolower(basename(dirname(__DIR__))));
DEFINE(__NAMESPACE__.'\STATEMENT_FOLDER', wp_get_upload_dir()["basedir"]."/private/account_statements/");

add_filter('sim_submenu_options', function($optionsHtml, $moduleSlug, $settings){
	//module slug should be the same as grandparent folder name
	if($moduleSlug != MODULE_SLUG){
		return $optionsHtml;
	}

	ob_start();
	?>
	<label>
		<h4>E-mail address</h4>
		This will be used to send e-mail request to forward account statements to post@<?php echo str_replace('https://', '', site_url());?><br>
		<input type="email" name="email" value="<?php echo $settings['email'];?>">
	</label>

	<?php
	return ob_get_clean();
}, 10, 3);

add_filter('sim_module_updated', function($options, $moduleSlug){
	//module slug should be the same as grandparent folder name
	if($moduleSlug != MODULE_SLUG){
		return $options;
	}

	SIM\ADMIN\installPlugin('postie/postie.php');

	return $options;
}, 10, 2);

add_filter('sim_email_settings', function($optionsHtml, $moduleSlug, $settings){
	//module slug should be the same as grandparent folder name
	if($moduleSlug != MODULE_SLUG){
		return $optionsHtml;
	}

	ob_start();

	?>
	<label>
		Define the e-mail sent to the finance team when someone enables account statements on the website
	</label>
	<br>

	<?php
	$emails    = new EnableBanking(wp_get_current_user());
	$emails->printPlaceholders();
	?>

	<h4>E-mail when enabled</h4>
	<?php

	$emails->printInputs($settings);
	
	?>
	<br>
	<br>
	<h4>E-mail when disabled</h4>
	<?php

	$emails    = new DisableBanking(wp_get_current_user());

	$emails->printInputs($settings);

	return ob_get_clean();
}, 10, 3);