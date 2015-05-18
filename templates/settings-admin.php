<?php

/**
 * ownCloud - MyCore versions
 *
 * @author Patrick Paysant <ppaysant@linagora.com>
 * @copyright 2014 CNRS DSI
 * @license This file is licensed under the Affero General Public License version 3 or later. See the COPYING file.
 */

\OCP\Util::addStyle('mycore_versions', 'settings-admin');

\OCP\Util::addScript('mycore_versions', 'lib/angular');
\OCP\Util::addScript('mycore_versions', 'lib/angucomplete-alt');
\OCP\Util::addScript('mycore_versions', 'app/settings-admin');
\OCP\Util::addScript('mycore_versions', 'app/services/mycore_versions.services');

?>
<div id="mycore_versions" class="section" ng-app="mycoreVersionsApp" ng-controller="mycoreVersionsController">
    <h2><?php p($l->t('My CoRe Versions')); ?></h2>

    <p>
        <input type="checkbox" ng-model="mycoreversionsGroupsEnabled"
            ng-true-value="yes" ng-false-value="no" ng-change="storeChoice()">
        <label for="mycoreversionsGroupsEnabled"><?php p($l->t('Allow some groups members to use versionning.'));?></label>
    </p>

    <div id="mycoreversionsGroups" class="indent" ng-show="mycoreversionsGroupsEnabled" ng-cloak class="ng-cloak">

        <h3><?php p($l->t('List of groups')); ?><p>

        <div id="searchGroup">
            <angucomplete-alt id="groups"
                placeholder="{{ searchPlaceholder }}"
                pause="400"
                selected-object="addGroup"
                remote-url="{{ mycoreversionsGroupsUrl }}"
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

        <div id="mycoreVersionsGroupList">
            <span class="groupItem" ng-repeat="group in groupList | orderBy:'name'">
                <span ng-click="removeGroup(group.id)" title="<?php p($l->t('Remove this group'));?>">[X]</span> {{ group.name }}
            </span>
        </div>

    </div>
</div>
