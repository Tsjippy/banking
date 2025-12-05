<?php
namespace SIM\BANKING;
use SIM;

add_action('sim_banking_module_update', __NAMESPACE__.'\moduleUpdate');
function moduleUpdate($oldVersion){
    global $wpdb;

    require_once ABSPATH . 'wp-admin/install-helper.php';
    
    SIM\printArray($oldVersion);


    if($oldVersion < '8.1.3'){
        $users  = get_users([
            'meta_key'      => 'account_statements',
            'meta_compare'  => 'EXISTS'
        ]);

        foreach($users as $user){
            $accountStatements				= (array)get_user_meta($user->ID, "account_statements", true);

            delete_user_meta($user->ID, "account_statements");

            foreach($accountStatements as $year => $months){
                foreach($months as $month => $files){
                    add_user_meta($user->ID, "account_statements", [
                        'year'	=> $year,
                        'month'	=> $month,
                        'files'	=> $files
                    ]);
                }
            }
        }
    }
}