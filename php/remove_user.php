<?php
namespace SIM\BANKING;
use SIM;

//Remove user page and user marker on user account deletion
add_action('delete_user', __NAMESPACE__.'\userDeleted');
function userDeleted($userId){
	$family		= new SIM\FAMILY\Family();
	$partner	= $family->getPartner($userId);

	//Only remove if there is no family
	if (!$partner){
		//Remove account statements
		$accountStatements = get_user_meta($userId, "account_statements");
		foreach($accountStatements as $data){
			foreach($data['files'] as $file){
				if(!is_array($file)){
					$file = [$file];
				}
				
				wp_delete_file($file);
			}
		}
    }

	// banking is currently enabled
    $currentSetting = get_user_meta($userId, 'online_statements', true);
	if(is_array($currentSetting) && !empty(!$currentSetting)){
		$user		= get_user_by('ID', $userId);
		$email    	= new DisableBanking($user);
		$email->filterMail();
		
		wp_mail( $user->user_email, $email->subject, $email->message);
	}
}