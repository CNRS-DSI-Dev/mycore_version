/**
 * ownCloud - My CoRe Versions
 *
 * @author Patrick Paysant <ppaysant@linagora.com>
 * @copyright 2014 CNRS DSI
 * @license This file is licensed under the Affero General Public License version 3 or later. See the COPYING file.
 */


// Needed if this ng-app is not the first one on page
angular.element(document).ready(function() {
  angular.bootstrap(document.getElementById('mycore-versions'), ['mycoreVersionsApp']);
});

var mycoreVersionsApp = angular.module('mycoreVersionsApp', ['angucomplete-alt', 'mycore_versions.services.groups']);
mycoreVersionsApp.config(['$httpProvider', function($httpProvider) {
    // CSRF protection
    $httpProvider.defaults.headers.common.requesttoken = oc_requesttoken;
}]);

mycoreVersionsApp.controller('mycoreVersionsController', ['$scope', 'groupsService', function($scope, groupsService) {

    $scope.mycoreversionsGroupsUrl = OC.generateUrl('/apps/mycore_versions/ajax/groups.php?act=groupList&search=');
    $scope.searchPlaceholder = t('mycore_versions', 'Search group');
    $scope.mycoreversionsGroupsEnabled = 'no';

    // Will contain a list of group objects {'name':'group_name','id':'group_id'}
    $scope.groupList = [];

    /**
     * Initialisation
     */
    $scope.init = function() {
        groupsService.isGroupsEnabled()
            .success(function(data) {
                $scope.mycoreversionsGroupsEnabled = data.data.enabled;
            });

        OC.AppConfig.getValue('mycore_versions', 'versions_groupids_list', null, function(data) {
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
        OC.AppConfig.setValue('mycore_versions', 'versions_groups_enabled', $scope.mycoreversionsGroupsEnabled);
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
                t('mycore_versions', 'This group is already in the list'),
                t('mycore_versions', 'Error creating group')
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
        var groupListElt = $('#mycoreVersionsGroupList');

        groupListElt.addClass("groupList_changed");
        OC.AppConfig.postCall('setValue',{app:'mycore_versions',key:'versions_groupids_list',value:angular.toJson($scope.groupList)}, function() {
            groupListElt.removeClass("groupList_changed");
            groupListElt.addClass("groupList_saved");
            groupListElt.removeClass("groupList_saved",2000);
        });
    };
}]);
