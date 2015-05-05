<?php
/**
 * ownCloud - MyCore version
 *
 * @author Patrick Paysant <ppaysant@linagora.com>
 * @copyright 2014 CNRS DSI
 * @license This file is licensed under the Affero General Public License version 3 or later. See the COPYING file.
 */

OCP\User::checkAdminUser();
OCP\JSON::callCheck();

if (!empty($_GET['act'])) {
    if ($_GET['act'] == 'is_groups_enabled') {
        $enabled = 'no';

        $appConfig = \OC::$server->getAppConfig();
        $enabled = $appConfig->getValue('mycore_version', 'version_groups_enabled', 'no');

        \OCP\JSON::success(array('data' => array('enabled' => $enabled)));
        exit();
    }
    elseif ($_GET['act'] == 'get_enabled_groups') {
        \OCP\JSON::success(array('data' => array('groups' => array())));
        exit();
    }
    elseif ($_GET['act'] == 'groupList') {

        $search = '';
        if (!empty($_GET['search'])) {
            $search = $_GET['search'];
        }

        $groups = array();

        $groupManager = \OC::$server->getGroupManager();
        $userManager = \OC::$server->getUserManager();

        $group = $groupManager->get('admin');
        $uid = \OCP\User::getUser();
        $isAdmin = $group->inGroup($userManager->get($uid));

        $groupsInfo = new \OC\Group\MetaData(\OCP\User::getUser(), $isAdmin, $groupManager);
        $groupsInfo->setSorting($groupsInfo::SORT_USERCOUNT);
        list($adminGroup, $groups) = $groupsInfo->get($search);

        \OCP\JSON::success(array('data' => array('groups' => $groups)));
        exit();
    }

}

\OC_Response::setStatus(500);
