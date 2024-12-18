<?php
namespace SIM\BANKING;
use SIM;


add_filter('sim_before_saving_formdata', __NAMESPACE__.'\beforeSavingFormData', 10, 2);
function beforeSavingFormData($formResults, $object){
	if($object->formData->name != 'user_generics'){
		return $formResults;
	}

	$enabled	= false;

    $currentSetting = get_user_meta($object->userId, 'online_statements', true);
	if(is_array($currentSetting) && !empty($currentSetting)){
		$enabled	= true;
	}

	// was not enabled, send e-mail
	SIM\cleanUpNestedArray($formResults['online_statements']);
	if(!empty($formResults['online_statements']) && !$enabled){
		$user		= get_user_by('ID', $object->userId);
		$email    	= new EnableBanking($user);
		$email->filterMail();

		$address	= SIM\getModuleOption(MODULE_SLUG, 'email');
		
		wp_mail( $address, $email->subject, $email->message);
	}elseif(empty($formResults['online_statements']) && $enabled){
		$user		= get_user_by('ID', $object->userId);
		$email    	= new DisableBanking($user);
		$email->filterMail();
		
		$address	= SIM\getModuleOption(MODULE_SLUG, 'email');
		
		wp_mail( $address, $email->subject, $email->message);
	}
	
	return $formResults;
}