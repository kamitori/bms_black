'use strict';

app.controller('SettingsController', ['$rootScope', '$scope', '$stateParams', '$http', '$location', 'Upload', 'toaster', 'dialogs', 'uiGridConstants', 'RW', function($rootScope, $scope, $stateParams, $http, $location, Upload, toaster, dialogs, uiGridConstants, RW) {
    $rootScope.title = 'Admin';
    $scope.template = appHelper.baseURL + '/tpl/settings/';

    var action = $stateParams.action.toLowerCase(),
        formTitle = $rootScope.title;

    formTitle = 'Settings';

    $http.get(appHelper.adminURL('settings/list/'))
        .success(function(result) {            
            var arr = {};
            $.each(result.data, function (index, item) {                
              $("#"+index).val(item);
            });
        }).error(function(result) {                
    });
    $scope.maxtime = function(){
        if( parseInt($("#to_hours").val(),10) >23) $("#to_hours").val(23);
        if( parseInt($("#to_hours").val(),10) <13) $("#to_hours").val(13);
    };
    $scope.mintime = function(){
        if( parseInt($("#from_hours").val(),10) >12) $("#from_hours").val(12);
        if( parseInt($("#from_hours").val(),10) <8) $("#from_hours").val(8);
    };
    $scope.submit = function() {            
            // if (categoryForm.$valid) {
            var file,
                data = {};
            $('.fields').each(function(){
                data[$(this).attr('name')] = $(this).val();
            })
            $scope.upload(file, data);
            // }
        };

        $scope.upload = function(file, data) {
            var config = {
                url: appHelper.adminURL('settings/update'),
                fields: data
            };
            if (file && file.blobUrl) {
                config.file = file;
            }
            Upload.upload(config).success(function(result, status, headers, config) {
                if (result.error === 1) {
                    toaster.pop('error', 'Error', result.messages.join('<br />'));
                } else if (result.error === 0) {                    
                    toaster.pop('success', 'Message', result.message);                    
                }
            });
        };

    
}]);
