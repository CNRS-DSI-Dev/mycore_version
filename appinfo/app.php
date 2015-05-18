<?php

$userGroups = OC_Group::getUserGroups(\OCP\User::getUser());

$mycoreGroups = array();
$appConfig = \OC::$server->getAppConfig();
$configGroups = json_decode($appConfig->getValue('mycore_versions', 'versions_groupids_list', ''));
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

\OCP\App::registerAdmin('mycore_versions', 'settings-admin');

//require_once 'mycore_versions/versions.php';
OC::$CLASSPATH['OCA\MyCoRe_Versions\Storage'] = 'mycore_versions/lib/versions.php';
OC::$CLASSPATH['OCA\MyCoRe_Versions\Hooks'] = 'mycore_versions/lib/hooks.php';
OC::$CLASSPATH['OCA\MyCoRe_Versions\Capabilities'] = 'mycore_versions/lib/capabilities.php';

OCP\Util::addscript('mycore_versions', 'versions');
OCP\Util::addStyle('mycore_versions', 'versions');

\OCA\MyCoRe_Versions\Hooks::connectHooks();
