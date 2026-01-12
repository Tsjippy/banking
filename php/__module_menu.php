<?php
namespace SIM\BANKING;
use SIM;

const MODULE_VERSION		= '8.1.4';
DEFINE(__NAMESPACE__.'\MODULE_PATH', plugin_dir_path(__DIR__));
DEFINE(__NAMESPACE__.'\MODULE_SLUG', strtolower(basename(dirname(__DIR__))));
DEFINE(__NAMESPACE__.'\STATEMENT_FOLDER', wp_get_upload_dir()["basedir"]."/private/account_statements/");

add_filter('sim_submenu_banking_options', __NAMESPACE__.'\menuOptions', 10, 2);
function menuOptions($optionsHtml, $settings){
	ob_start();
	?>
	<label>
		<h4>E-mail address</h4>
		This will be used to send e-mail request to forward account statements to post@<?php echo str_replace('https://', '', site_url());?><br>
		<input type="email" name="email" value="<?php echo $settings['email'];?>">
	</label>

	<?php
	return $optionsHtml.ob_get_clean();
}

add_filter('sim_module_banking_after_save', __NAMESPACE__.'\afterUpdate');
function afterUpdate($options){
	SIM\ADMIN\installPlugin('postie/postie.php');

	return $options;
}