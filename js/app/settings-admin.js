/**
 * ownCloud - My CoRe Version
 *
 * @author Patrick Paysant <ppaysant@linagora.com>
 * @copyright 2014 CNRS DSI
 * @license This file is licensed under the Affero General Public License version 3 or later. See the COPYING file.
 */


// Needed if this ng-app is not the first one on page
angular.element(document).ready(function() {
  angular.bootstrap(document.getElementById('mycore-version'), ['mycoreVersionApp']);
});

var mycoreVersionApp = angular.module('mycoreVersionApp', ['angucomplete-alt', 'mycore_version.services.groups']);
mycoreVersionApp.config(['$httpProvider', function($httpProvider) {
    // CSRF protection
    $httpProvider.defaults.headers.common.requesttoken = oc_requesttoken;
}]);

mycoreVersionApp.controller('mycoreVersionController', ['$scope', 'groupsService', function($scope, groupsService) {

    $scope.mycoreversionGroupsUrl = OC.generateUrl('/apps/mycore_version/ajax/groups.php?act=groupList&search=');
    $scope.searchPlaceholder = t('mycore_version', 'Search group');
    $scope.mycoreversionGroupsEnabled = 'no';

    // Will contain a list of group objects {'name':'group_name','id':'group_id'}
    $scope.groupList = [];

    /**
     * Initialisation
     */
    $scope.init = function() {
        groupsService.isGroupsEnabled()
            .success(function(data) {
                $scope.mycoreversionGroupsEnabled = data.data.enabled;
            });

        OC.AppConfig.getValue('mycore_version', 'version_groupids_list', null, function(data) {
            if (data == null) {
                $scope.groupList = [];
            }
            else {
                $scope.groupList = JSON.parse(data);
            }
        });
    };
    $scope.init();

    /**
     * Ask for param storage
     */
    $scope.storeChoice = function() {
        OC.AppConfig.setValue('mycore_version', 'version_groups_enabled', $scope.mycoreversionGroupsEnabled);
    };

    /**
     * Add a group to the list
     * @param object group (as returned by angucomplete-alt directive)
     */
    $scope.addGroup = function(group) {
        var truc = _.filter($scope.groupList, function(elt) {
            return elt.id === group.originalObject.id;
        });
        if (truc.length > 0) {
            OC.dialogs.alert(
                t('mycore_version', 'This group is already in the list'),
                t('mycore_version', 'Error creating group')
            );
            return;
        }

        $scope.groupList.push({
            'name': group.originalObject.name,
            'id': group.originalObject.id
        });
        $scope.updateGroupList();
    };

    /**
     * Remove a group from the list
     * @param string groupId
     */
    $scope.removeGroup = function(groupId) {
        $scope.groupList = _.reject($scope.groupList, function(group) {
            return group.id === groupId;
        });
        $scope.updateGroupList();
    };

    $scope.updateGroupList = function() {
        var groupListElt = $('#groupList');

        groupListElt.addClass("groupList_changed");
        OC.AppConfig.postCall('setValue',{app:'mycore_version',key:'version_groupids_list',value:angular.toJson($scope.groupList)}, function() {
            groupListElt.removeClass("groupList_changed");
            groupListElt.addClass("groupList_saved");
            groupListElt.removeClass("groupList_saved",2000);
        });
    };
}]);
