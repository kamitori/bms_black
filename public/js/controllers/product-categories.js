'use strict';

app.controller('ProductCategoryController', ['$rootScope', '$scope', '$stateParams', '$http', '$location', 'Upload', 'toaster', 'dialogs', 'uiGridConstants', 'RW', function($rootScope, $scope, $stateParams, $http, $location, Upload, toaster, dialogs, uiGridConstants, RW) {
    $rootScope.title = 'Product Category';
    $scope.template = appHelper.baseURL + '/tpl/product-categories/';

    var action = $stateParams.action.toLowerCase(),
        formTitle = $rootScope.title;

    formTitle = 'Category';
    $scope.button = {
        title: 'List category',
        link: '#/app/product-categories/list'
    };
    switch (action) {
        case 'list':
            $scope.button = {
                title: 'Add Product Category',
                link: '#/app/product-categories/add'
            };
            $scope.template += 'all.html';
            break;
        case 'add':
            $scope.template += 'one.html';
            formTitle = 'Add Product Category';
            break;
        case 'edit':
            $scope.template += 'one.html';
            formTitle = 'Edit Product Category';
            break;
        default:
            $scope.template += 'all.html';
            break;
    }
    

    if (['add', 'edit'].indexOf(action) != -1) {
        $scope.form = {
            fields: [{
                type: 'hidden',
                name: 'id_category'
            }, {
                type: 'image-upload',
                label: 'Image',
                name: 'image',
                value: 'http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image',
                inputAttr: 'ngf-validate="{size: {max: \'2MB\', min: \'10B\'} }" name="file"',
                inputStyle: 'max-width: 200px;',
                validate: {
                    patternMessage: 'Image must be valid!',
                }
            }, {
                type: 'text',
                label: 'Alt',
                name: 'alt'
            }, {
                type: 'text-editor',
                label: 'Description',
                name: 'description'
            }],
            title: formTitle
        };

        if (action === 'edit') {
            var id = $stateParams.id;
            $http.get(appHelper.adminURL('product-categories/edit/' + id))
                .success(function(result) {
                    console.log(result);
                    if (result.error === 0) {
                        $scope.form = appHelper.populateForm($scope.form, result.data);
                    }
                    $scope.form.allLoaded = true;
                }).error(function(result) {
                    dialogs.confirm(result.message, 'Do you want to create a new one?', {
                            size: 'md'
                        })
                        .result.then(function() {
                            $location.path('/app/product-categories/add');
                        }, function() {
                            $location.path('/app/product-categories/list');
                        });
                });
        } else {
            $http.get(appHelper.adminURL('product-categories/get-options'))
                    .success(function(result) {
                        if (result.error === 0) {
                            $scope.form.fields[2].options = result.data;
                        }
                        $scope.form.allLoaded = true;
                    });
        }

        $scope.save = function() {
            // if (categoryForm.$valid) {
            var file,
                data = {};
            for (var i in $scope.form.fields) {
                if ($scope.form.fields[i].name == 'image') {
                    /*if (!$scope.form.fields[i].file.$valid && $scope.form.fields[i].file.$error) {
                        return false;
                    }*/
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
                url: appHelper.adminURL('product-categories/update'),
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
                    $location.path('/app/product-categories/edit/' + result.data.id);
                }
            });
        };
    } else if (action === 'list') {
        $scope.gridOptions = RW.gridOptions($scope, {
            gridName: 'gridOptions', //*required
            rowHeight: 100,
            enableCellEditOnFocus: true,
            columns: [{
                    name: 'ID',
                    field: 'id',
                    visible: false
                },
                {
                    name: 'Name',
                    field: 'name',
                    cellTemplate: '<a href="#/app/product-categories/edit/{{ row.entity.id }}" class="namelink">{{ row.entity.name }}</a>',
                    filter: {
                        placeholder: 'Search by Name'
                    }
                }, {
                    name: 'Banner',
                    field: 'image',
                    enableSorting: false,
                    enableFiltering: false,
                    cellClass: 'text-center',
                    cellTemplate: '<img ngf-src="row.entity.image || \'http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image\'" ng-if="row.entity.image" style="height: 100px;" src="{{ row.entity.image }}" class="image-thumbs"/>'
                }, {
                    name: 'Alt',
                    field: 'alt',
                    enableSorting: false,
                    enableFiltering: false
                },{ 
                    name: 'Action', 
                    field:'',
                    cellTemplate: '<a href="#/app/product-categories/edit/{{ row.entity.id }}" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></a> <a class="btn btn-xs btn-danger" title="Clear banner" ng-click="grid.appScope.delete_banner({{ row.entity.id }})"><i class="fa fa-remove"></i></a>',
                    enableSorting: false,
                    enableFiltering: false,
                    cellClass: 'text-center',
                    width:80
                }
            ],
            list: appHelper.adminURL('product-categories/list'),
            // edit: '#/app/product-categories/edit',
            // delete: appHelper.adminURL('product-categories/delete-banner'),
            // deleteConfirm: 'Are you sure you want to delete banner of this category?',
             //optional
        });
        $scope.gridOptions.appScopeProvider = $scope;
        $scope.delete_banner = function(id){
                $http.get(appHelper.adminURL('product-categories/delete-banner/' + id))
                .success(function(result) {
                    console.log(result);
                    if (result.error === 0) {
                         toaster.pop('success', 'Message', result.message);
                        $http.get(appHelper.adminURL('product-categories/list'))
                                .success(function(result2){
                                    $scope.gridOptions.data = result2.data;
                                })
                        $scope.gridApi.core.refresh();
                    }else{
                         toaster.pop('error', 'Error', result.message);
                    }
                })
        }

        $scope.getCurrentFocus = function(){
          var rowCol = $scope.gridApi.cellNav.getFocusedCell();
          if(rowCol !== null) {
              $scope.currentFocused = 'Row Id:' + rowCol.row.entity.id + ' col:' + rowCol.col.colDef.name;
          }
        }
    }
}]);

