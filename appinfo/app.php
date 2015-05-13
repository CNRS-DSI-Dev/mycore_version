<?php

$userGroups = OC_Group::getUserGroups(\OCP\User::getUser());

$mycoreGroups = array();
$appConfig = \OC::$server->getAppConfig();
$configGroups = json_decode($appConfig->getValue('mycore_version', 'version_groupids_list', ''));
if (is_array($configGroups)) {
    foreach($configGroups as $group) {
        array_push($mycoreGroups, $group->id);
    }
}

$isAdminUser = false;
if( OC_User::isAdminUser(OC_User::getUser())) {
    $isAdminUser = true;
}
$intersect = array_intersect($userGroups, $mycoreGroups);

if (empty($intersect) and !$isAdminUser) {
    return;
}

\OCP\App::registerAdmin('mycore_version', 'settings-admin');

//require_once 'mycore_version/versions.php';
OC::$CLASSPATH['OCA\MyCoRe_Version\Storage'] = 'mycore_version/lib/versions.php';
OC::$CLASSPATH['OCA\MyCoRe_Version\Hooks'] = 'mycore_version/lib/hooks.php';
OC::$CLASSPATH['OCA\MyCoRe_Version\Capabilities'] = 'mycore_version/lib/capabilities.php';

OCP\Util::addscript('mycore_version', 'versions');
OCP\Util::addStyle('mycore_version', 'versions');

\OCA\MyCoRe_Version\Hooks::connectHooks();
