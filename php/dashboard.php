<?php
namespace SIM\BANKING;
use SIM;

add_action('sim_user_dashboard', __NAMESPACE__.'\userDashboard', 10, 2);
function userDashboard($userId, $admin) {
    if(!$admin){
        ?>
        <div id="Account statements" style="margin-top:20px;">
            <?php
            echo showStatements($userId);
            ?>
        </div>
        <?php
    }
}