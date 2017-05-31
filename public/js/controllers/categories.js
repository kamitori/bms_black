'use strict';

app.controller('CategoryController', ['$rootScope', '$scope', '$stateParams', '$http', '$location', 'Upload', 'toaster', 'dialogs', 'uiGridConstants', 'RW', function($rootScope, $scope, $stateParams, $http, $location, Upload, toaster, dialogs, uiGridConstants, RW) {
    $rootScope.title = 'Menu';
    $scope.template = appHelper.baseURL + '/tpl/categories/';

    var action = $stateParams.action.toLowerCase(),
        formTitle = $rootScope.title;

    formTitle = 'Menu';
    $scope.button = {
        title: 'List category',
        link: '#/app/categories/list'
    };
    switch (action) {
        case 'list':
            $scope.button = {
                title: 'Add category',
                link: '#/app/categories/add'
            };
            $scope.template += 'all.html';
            break;
        case 'add':
            $scope.template += 'one.html';
            formTitle = 'Add Menu';
            break;
        case 'edit':
            $scope.template += 'one.html';
            formTitle = 'Edit Menu';
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
                label: 'Name',
                name: 'name',
                inputAttr: 'ng-patern="/^[a-zA-Z0-9]{4,10}$/" required="true"',
                validate: {
                    requiredMessage: 'Name must not be empty!',
                    patternMessage: 'Name must be string!'
                }
            }, {
                type: 'select',
                label: 'Parent category',
                name: 'parent_id',
                options: []
            }, {
                type: 'number',
                label: 'Order No.',
                name: 'order_no',
                value:1
            }, {
                type: 'select',
                label: 'Position',
                name: 'position',
                options: [{value:0,text:'Hide Menu'},{value:1,text:'Top Main'},{value:2,text:'Bottom'},{value:3,text:'Top Main and Bottom'}],
                value: 1
            }, {
                type: 'textarea',
                label: 'Description',
                name: 'description',
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
                label: 'Image\'s alt',
                name: 'alt'
            }, {
                type: 'text',
                label: 'Meta title',
                name: 'meta_title'
            }, {
                type: 'text',
                label: 'Meta Desciption',
                name: 'meta_description',
                inputStyle: 'resize: vertical',
                inputAttr: 'rows="10"'
            }],
            title: formTitle
        };

        if (action === 'edit') {
            var id = $stateParams.id;
            $http.get(appHelper.adminURL('categories/edit/' + id))
                .success(function(result) {
                    console.log(result);
                    if (result.error === 0) {
                        $scope.form.fields[2].options = result.data.catlist;
                        delete(result.data.catlist);
                        $scope.form = appHelper.populateForm($scope.form, result.data);
                        console.log(result.data.catlist);
                    }
                    $scope.form.allLoaded = true;
                }).error(function(result) {
                    dialogs.confirm(result.message, 'Do you want to create a new one?', {
                            size: 'md'
                        })
                        .result.then(function() {
                            $location.path('/app/categories/add');
                        }, function() {
                            $location.path('/app/categories/list');
                        });
                });
        } else {
            $http.get(appHelper.adminURL('categories/get-options'))
                    .success(function(result) {
                        if (result.error === 0) {
                            // result.data.unshift({text:"No Parent",value:0})
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
                url: appHelper.adminURL('categories/update'),
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
                    $location.path('/app/categories/edit/' + result.data.id);
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
                    cellTemplate: '<a href="#/app/categories/edit/{{ row.entity.id }}" class="namelink">{{ row.entity.name }}</a>',
                    filter: {
                        placeholder: 'Search by Name'
                    }
                }, {
                    name: 'Preview',
                    field: 'image',
                    enableSorting: false,
                    enableFiltering: false,
                    cellClass: 'text-center',
                    cellTemplate: '<img ng-if="row.entity.image" style="height: 100px;" src="{{ row.entity.image }}" class="image-thumbs" />'
                }, {
                    name: 'Alt',
                    field: 'alt',
                    enableSorting: false,
                    enableFiltering: false,
                }, {
                    name: 'Description',
                    field: 'description',
                    filter: {
                        placeholder: 'Search'
                    }
                }, {
                    name: 'Order',
                    field: 'order_no',
                    enableFiltering: false,
                    type: 'number',
                    width: '5%'                    
                }
            ],
            list: appHelper.adminURL('categories/list'),
            edit: '#/app/categories/edit',
            delete: appHelper.adminURL('categories/delete'),
            deleteConfirm: 'Are you sure you want to delete this category?' //optional
        });
        $scope.getCurrentFocus = function(){
          var rowCol = $scope.gridApi.cellNav.getFocusedCell();
          if(rowCol !== null) {
              $scope.currentFocused = 'Row Id:' + rowCol.row.entity.id + ' col:' + rowCol.col.colDef.name;
          }
        }
    }
}]);
