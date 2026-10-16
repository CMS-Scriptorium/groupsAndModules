<?php

/**
 * @module  usersAndGroups
 * @author  Dietrich Roland Pehlke
 * @license wtfpl
 *
 */

// include secure.php to protect this file and the whole CMS!
if(!defined("SEC_FILE")){define("SEC_FILE",'/framework/secure.php' );}
if (defined('LEPTON_PATH')) {
    include LEPTON_PATH.SEC_FILE;
} else {
    $oneback = "../";
    $root = $oneback;
    $level = 1;
    while (($level < 10) && (!file_exists($root.SEC_FILE))) {
        $root .= $oneback;
        $level += 1;
    }
    if (file_exists($root.SEC_FILE)) {
        include $root.SEC_FILE;
    } else {
        trigger_error(sprintf("[ <b>%s</b> ] Can't include secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
    }
}
// end include secure.php

$module_directory   = 'groupsAndModules';
$module_name        = 'Groups and Modules';
$module_function    = 'tool';
$module_version     = '0.1.1';
$module_platform    = '7.x';
$module_author      = 'Dietrich Roland Pehlke';
$module_home        = '-';
$module_guid        = 'BBE403F9-B2B1-485E-8659-ADFD30EBC83B';
$module_license     = 'wtfpl';
$module_description = 'Private addon to edit users and groups.';
