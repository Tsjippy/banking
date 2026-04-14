<?php
namespace SIM\BANKING;
use SIM;

add_filter('postie_post_before', __NAMESPACE__.'\processAccountStatements');
function processAccountStatements($post){

	$user	= get_userdata($post['post_author']);

	if(!$user){
		return false;
	}

	SIM\printArray($user);

	//Find the attachment url
	$attachments 		= get_attached_media("", $post['ID']);

	SIM\printArray($attachments);

	$returnAttachments 	= [];

	//Loop over all attachments
	foreach($attachments as $attachment){

		$filePath = get_attached_file($attachment->ID);

		if(file_exists($filePath)){
			$csvFileName			= str_replace('.pdf', '.csv', basename($filePath));

			//Read the contents of the attachment
			$result					= SIM\PDFTOEXCEL\readPdf($filePath,  wp_upload_dir()['basedir']."/$csvFileName");

			$returnAttachments[]	= $result;
		}
	}

	//remove the attachment as it should be private
	wp_delete_attachment($attachment->ID, true);

	wp_mail($user->user_email, "CSVs", "Hi {$user->display_name},<br><br>Your account statements are processed, find them attached to this e-mail.", '', $returnAttachments);

	return null;
}