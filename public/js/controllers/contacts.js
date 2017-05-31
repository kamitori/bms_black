'use strict';

app.controller('ContactsController', ['$rootScope', '$scope', '$stateParams', '$http', '$location', 'Upload', 'toaster', 'dialogs', 'uiGridConstants', 'RW', function($rootScope, $scope, $stateParams, $http, $location, Upload, toaster, dialogs, uiGridConstants, RW) {
    $rootScope.title = 'Contacts';
    $scope.template = appHelper.baseURL + '/tpl/contacts/';

    var action = $stateParams.action.toLowerCase(),
        formTitle = $rootScope.title;

    formTitle = 'Contacts';
    $scope.action = action;
    $scope.button = {
        title: 'List Contacts',
        link: '#/app/contacts/list'
    };
    $scope.button_add = {
        title: 'Add Contacts',
        link: '#/app/contacts/add'
    };
    switch (action) {
        case 'list':
            $scope.template += 'all.html';
            break;
        case 'add':
            $scope.template += 'one.html';
            formTitle = 'Add Contacts';
            break;
        case 'edit':
            $scope.template += 'one.html';
            formTitle = 'Edit Contacts';
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
                name: 'contact_name',
                inputAttr: 'ng-patern="/^[a-zA-Z0-9]{4,10}$/" required="true"',
                validate: {
                    requiredMessage: 'Name must not be empty!',
                    patternMessage: 'Name must be string!'
                }
            }, {
                type: 'text',
                label: 'Email',
                name: 'contact_email',
                inputAttr: 'ng-patern="/^[a-zA-Z0-9]{4,10}$/" required="true"',
                validate: {
                    requiredMessage: 'Email must not be empty!',
                    patternMessage: 'Email must be string!'
                }
            }, {
                type: 'text',
                label: 'Phone',
                name: 'contact_phone'
            }, {
                type: 'text',
                label: 'Company name',
                name: 'company_name'
            }, {
                type: 'textarea',
                label: 'Message',
                name: 'message'
            }, {
                type: 'select',
                label: 'Primary interest',
                name: 'primary_interest',
                options:[{value:"metting",text:'Metting'},{value:"question",text:'Questions'},{value:"startup",text:'Startup'}]
            }],
            title: formTitle
        };

        if (action === 'edit') {
            var id = $stateParams.id;
            $http.get(appHelper.adminURL('contacts/edit/' + id))
            .success(function(result) {
                if (result.error === 0) {
                    $scope.form.fields[2].options = result.data.contacts;
                    delete(result.data.contacts);
                    $scope.form = appHelper.populateForm($scope.form, result.data);
                }
                $scope.form.allLoaded = true;
            }).error(function(result) {
                dialogs.confirm(result.message, 'Do you want to create a new one?', {
                    size: 'md'
                })
                .result.then(function() {
                    $location.path('/app/contacts/add');
                }, function() {
                    $location.path('/app/contacts/list');
                });
            });
        }
        $scope.form.allLoaded = true;
        $scope.save = function() {            
            var file,data = {};
            console.log('click save')
            for (var i in $scope.form.fields) {
                data[$scope.form.fields[i].name] = $scope.form.fields[i].value;
            }
            $scope.upload(file, data);
        };
        $scope.upload = function(file, data) {            
            var config = {
                url: appHelper.adminURL('contacts/update'),
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
                    $location.path('/app/contacts/edit/' + result.data.id);
                }
            });
        };
    } else if (action === 'list') {
        $scope.gridOptions = RW.gridOptions($scope, {
            gridName: 'gridOptions', //*required
            rowHeight: 60,
            enableCellEditOnFocus: true,
            columns: [{
                    name: 'ID',
                    field: 'id',
                    visible: false
                },
                {
                    name: 'Contact name',
                    field: 'contact_name',
                    cellTemplate: '<a href="#/app/contacts/edit/{{ row.entity.id }}" class="namelink">{{ row.entity.contact_name }}</a>',
                    filter: {
                        placeholder: 'Search by Name'
                    }
                }, {
                    name: 'Contact Email',
                    field: 'contact_email',
                    filter: {
                        placeholder: 'Search'
                    }
                }, {
                    name: 'Contact phone',
                    field: 'contact_phone'
                }, {
                    name: 'Message',
                    field: 'message'
                }, {
                    name: 'Message',
                    field: 'message'
                }
            ],
            list: appHelper.adminURL('contacts/list'),
            edit: '#/app/contacts/edit',
            delete: appHelper.adminURL('contacts/delete'),
            deleteConfirm: 'Are you sure you want to delete this contacts?', //optional,
            useExternalFiltering: false,
            useExternalPagination: false,
            useExternalSorting: false,
        });
        $scope.getCurrentFocus = function(){
          var rowCol = $scope.gridApi.cellNav.getFocusedCell();
          if(rowCol !== null) {
              $scope.currentFocused = 'Row Id:' + rowCol.row.entity.id + ' col:' + rowCol.col.colDef.name;
          }
        }
    }
}]);