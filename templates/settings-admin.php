<?php

/**
 * ownCloud - MyCore version
 *
 * @author Patrick Paysant <ppaysant@linagora.com>
 * @copyright 2014 CNRS DSI
 * @license This file is licensed under the Affero General Public License version 3 or later. See the COPYING file.
 */

\OCP\Util::addStyle('mycore_version', 'settings-admin');

\OCP\Util::addScript('mycore_version', 'lib/angular');
\OCP\Util::addScript('mycore_version', 'lib/angucomplete-alt');
\OCP\Util::addScript('mycore_version', 'app/settings-admin');
\OCP\Util::addScript('mycore_version', 'app/services/mycore_version.services');

?>
<div id="mycore_version" class="section" ng-app="mycoreVersionApp" ng-controller="mycoreVersionController">
    <h2><?php p($l->t('My CoRe Version')); ?></h2>

    <p>
        <input type="checkbox" ng-model="mycoreversionGroupsEnabled"
            ng-true-value="yes" ng-false-value="no" ng-change="storeChoice()">
        <label for="mycoreversionGroupsEnabled"><?php p($l->t('Allow some groups members to use versionning.'));?></label>
    </p>

    <div id="mycoreversionGroups" class="indent" ng-show="mycoreversionGroupsEnabled" ng-cloak class="ng-cloak">

        <h3><?php p($l->t('List of groups')); ?><p>

        <div id="searchGroup">
            <angucomplete-alt id="groups"
                placeholder="{{ searchPlaceholder }}"
                pause="400"
                selected-object="addGroup"
                remote-url="{{ mycoreversionGroupsUrl }}"
                remote-url-data-field="data.groups"
                minlength = "1"
                title-field="name"
                clear-selected="true"></angucomplete-alt>

            <span class="utils">
                <a href="#" class="action delete" original-title="<?php p($l->t('Delete'))?>" ng-click="deleteGroup()">
                    <img src="<?php print_unescaped(image_path('core', 'actions/delete.svg')) ?>" class="svg" />
                </a>
            </span>

        </div>

        <div id="mycoreVersionGroupList">
            <span class="groupItem" ng-repeat="group in groupList | orderBy:'name'">
                <span ng-click="removeGroup(group.id)" title="<?php p($l->t('Remove this group'));?>">[X]</span> {{ group.name }}
            </span>
        </div>

    </div>
</div>
