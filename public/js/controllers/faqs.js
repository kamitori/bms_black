'use strict';

app.controller('FaqController', ['$rootScope', '$scope', '$stateParams', '$http', '$location', 'Upload', 'toaster', 'dialogs', 'uiGridConstants', 'RW', function($rootScope, $scope, $stateParams, $http, $location, Upload, toaster, dialogs, uiGridConstants, RW) {
    $rootScope.title = 'Faq';
    $scope.template = appHelper.baseURL + '/tpl/faqs/';

    var action = $stateParams.action.toLowerCase(),
        formTitle = $rootScope.title;

    formTitle = 'Faq';
    $scope.button = {
        title: 'List Faq',
        link: '#/app/Faqs/list'
    };
    switch (action) {
        case 'list':
            $scope.button = {
                title: 'Add Faq',
                link: '#/app/Faqs/add'
            };
            $scope.template += 'all.html';
            break;
        case 'add':
            $scope.template += 'one.html';
            formTitle = 'Add Faq';
            break;
        case 'edit':
            $scope.template += 'one.html';
            formTitle = 'Edit Faq';
            break;
        default:
            $scope.template += 'all.html';
            break;
    }

    if (['add', 'edit'].indexOf(action) != -1) {
        $scope.form = {
            fields: [{
                type: 'hidden',
                name: 'id'
            }, {
                type: 'text',
                label: 'Question',
                name: 'name'
            },{
                type: 'number',
                label: 'Order no',
                name: 'order_no'
            }, {
                type: 'new-editor',
                label: 'Answer',
                name: 'answer'
            },{
                type: 'select',
                label: 'Active',
                name: 'active',
                options:[{value:1,text:'Show'},{value:0,text:'Hide'}],
                value:1
            }],
            title: formTitle
        };

        if (action === 'edit') {
            var id = $stateParams.id;
            $http.get(appHelper.adminURL('Faqs/edit/' + id))
                .success(function(result) {
                    if (result.error === 0) {
                        $scope.form = appHelper.populateForm($scope.form, result.data);
                    }
                    $scope.form.allLoaded = true;
                }).error(function(result) {
                    dialogs.confirm(result.message, 'Do you want to create a new one?', {
                            size: 'md'
                        })
                        .result.then(function() {
                            $location.path('/app/Faqs/add');
                        }, function() {
                            $location.path('/app/Faqs/list');
                        });
                });
        } else {
            $scope.form.allLoaded = true;
        }

        $scope.save = function() {
            // if (categoryForm.$valid) {
            var file,
                data = {};
            for (var i in $scope.form.fields) {
                if ($scope.form.fields[i].name == 'image') {
                    if (action == 'add') {
                        if (!$scope.form.fields[i].file ) {
                            toaster.pop('error', 'Error', 'Image must not be empty!');
                            return false;
                        }
                    }
                    file = $scope.form.fields[i].file;
                } else {
                    data[$scope.form.fields[i].name] = $scope.form.fields[i].value;
                }
            }
            $scope.upload(file, data);
            // }
        };

        $scope.upload = function(file, data) {
            var config = {
                url: appHelper.adminURL('Faqs/update'),
                fields: data
            };
            if (file && file.blobUrl) {
                config.file = file;
            }
            Upload.upload(config).success(function(result, status, headers, config) {
                if (result.error === 1) {
                    toaster.pop('error', 'Error', result.messages.join('<br />'));
                } else if (result.error === 0) {
                    $scope.form.fields[0].value = result.data.id;
                    toaster.pop('success', 'Message', result.message);
                    $location.path('/app/Faqs/edit/' + result.data.id);
                }
            });
        };
    } else if (action === 'list') {
        $scope.gridOptions = RW.gridOptions($scope, {
            gridName: 'gridOptions', //*required
            rowHeight: 100,
            enableSorting: true,
            columns: [{
                    name: 'Order',
                    field: 'order_no'
                },{
                    name: 'Question',
                    field: 'name'
                }, {
                    name: 'Answer',
                    field: 'answer'
                }],
            list: appHelper.adminURL('Faqs/list'),
            edit: '#/app/Faqs/edit',
            delete: appHelper.adminURL('Faqs/delete'),
            deleteConfirm: 'Are you sure you want to delete this category?' //optional
        });
    }
}]);
