angular.module('edmShopItems', ['ui.router', 'restangular'])
    .config(['RestangularProvider', function (RestangularProvider) {
        RestangularProvider.setBaseUrl('/api');
    }])
    .config(['$stateProvider', '$urlRouterProvider', function ($stateProvider, $urlRouterProvider) {
        $urlRouterProvider.otherwise('/general');

        $stateProvider
            .state('general', {
                url: '/general',
                views: {
                    '@': {
                        controller: 'GeneralCtrl',
                        templateUrl: '/templates/general.html',
                        resolve: {
                            'ShopCategoriesSelectList': ['$q', 'ShopCategories', function ($q, ShopCategories) {
                                var deferred = $q.defer();

                                ShopCategories.hierarchicalItemCategories.then(function (shopCategories) {
                                    deferred.resolve(_.map(shopCategories, function (shopCategory) {
                                        return {
                                            id: shopCategory.node.id,
                                            name: shopCategory.names.join(' -> ')
                                        };
                                    }));
                                });

                                return deferred.promise;
                            }]
                        }
                    },
                    'progress-sidebar': {
                        controller: 'ProgressCtrl',
                        templateUrl: '/templates/progress-sidebar.html'
                    }
                }
            });
    }])
    .factory('Utils', ['Restangular', function (Restangular) {
        var buildFieldValidationExpression = function buildFieldValidationExpression(formName, fieldName) {
            var fieldExpression = formName + '.' + fieldName;
            var invalidExpression = fieldExpression + '.$invalid';
            var dirtyExpression = fieldExpression + '.$dirty';
            return invalidExpression + ' && ' + dirtyExpression;
        };

        var restangularize = function restangularize(parent, route, models) {
            if (!_.isArray(models)) {
                return Restangular.restangularizeElement(parent, models, route);
            }

            var convertedModels = _.map(models, function (model) {
                return Restangular.restangularizeElement(parent, model, route);
            });

            return Restangular.restangularizeCollection(parent, convertedModels, route);
        };

        var setRelationFields = function setRelationFields(model) {
            if (!_.has(model, 'embeddableRelations') || _.isEmpty(model.embeddableRelations)) {
                return;
            }

            _.each(model.embeddableRelations, function (relation) {
                var relatedFieldName = 'related_' + relation.name;
                if (_.isUndefined(model[relatedFieldName]))
                    return;

                var includeParent = true;
                if (_.has(relation, 'parent')) {
                    includeParent = relation.parent;
                }

                model[relation.fieldName] = restangularize(includeParent ? model : null, relation.route, model[relatedFieldName]);
                delete model[relatedFieldName];
            });
        };

        var assignServerSideValidationErrorsFromErrorEvent = function assignServerSideValidationErrorsFromErrorEvent(errorEvent, $scope) {
            if (errorEvent.data.hasOwnProperty('errors') && errorEvent.data['errors']) {
                var errors = errorEvent.data['errors'];

                for (var fieldName in errors) {
                    if (errors[fieldName] && errors[fieldName].length != 0) {
                        $scope.serverSideValidationErrors[fieldName] = errors[fieldName][0];
                    }
                }
            }
        };

        var createModelChangedEvent = function createModelChangedEvent(action, models) {
            if (!_.isArray(models)) {
                models = [models];
            }

            return {action: action, models: models};
        };

        var isStoredModel = function isStoredModel(model) {
            return _.isObject(model) && _.has(model, 'id');
        };

        var isPromise = function isPromise(value) {
            return _.contains(_.functions(value), 'then');
        };

        var executeWithValue = function executeWithValue(value, callback) {
            if (isPromise(value)) {
                return value.then(callback);
            } else {
                return callback(value);
            }
        };

        return {
            buildFieldValidationExpression: buildFieldValidationExpression,
            restangularize: restangularize,
            setRelationFields: setRelationFields,
            assignServerSideValidationErrorsFromErrorEvent: assignServerSideValidationErrorsFromErrorEvent,
            createModelChangedEvent: createModelChangedEvent,
            isStoredModel: isStoredModel,
            executeWithValue: executeWithValue
        };
    }])
    .factory('ShopCategories', [
        'Restangular', '$q',
        function (Restangular, $q) {
            var allShopCategories = Restangular.all('shop-categories');
            var deferredHierarchicalItemCategories = $q.defer();

            allShopCategories.getList().then(function (categories) {
                var rootNodes = _.filter(categories, {parent_id: null});
                var result = [];

                var processBranch = function processBranch(names, node) {
                    var localNames = _.clone(names);
                    localNames.push(node.name);

                    if (_.parseInt(node.can_contain_items)) {
                        result.push({
                            names: localNames,
                            node: node
                        })
                    }

                    var childNodes = _.filter(categories, {parent_id: node.id});
                    _.forEach(childNodes, _.wrap(localNames, processBranch));
                };

                _.forEach(rootNodes, _.wrap([], processBranch));
                deferredHierarchicalItemCategories.resolve(result);
            });

            return {
                all: allShopCategories,
                hierarchicalItemCategories: deferredHierarchicalItemCategories.promise
            };
        }
    ])
    .factory('DefaultValidationMessages', [
        function () {
            return {
                required: 'This field is required',
                pattern: 'Invalid characters',
                minlength: 'Min length: {{ length }} characters',
                maxlength: 'Max length: {{ length }} characters'
            };
        }
    ])
    .factory('ItemCreationService', [
        '$rootScope',
        function ($rootScope) {
            var targetItemType = undefined;
            var steps = [
                {
                    isActive: true,
                    heading: 'General information',
                    text: 'moar dafuq information',
                    route: 'general',
                    requiredTargetItemTypes: []
                },
                {
                    isActive: false,
                    heading: 'Project file information',
                    text: 'moar dafuq project file information',
                    route: 'project-file',
                    requiredTargetItemTypes: ['project-file.template', 'project-file.preset']
                },
                {
                    isActive: false,
                    heading: 'File upload area',
                    text: 'upload your files',
                    route: 'upload-file',
                    requiredTargetItemTypes: []
                },
                {
                    isActive: false,
                    heading: 'Overview and submit',
                    text: 'moar dafuq overview with ultimate information',
                    route: 'overview',
                    requiredTargetItemTypes: []
                }
            ];
            var stepsToDisplay = [];

            var calculateStepsToDisplay = function calculateStepsToDisplay() {
                stepsToDisplay = _.filter(steps, function (step) {
                    if (step.requiredTargetItemTypes.length === 0) return true;
                    return _.contains(step.requiredTargetItemTypes, targetItemType);
                });
            };

            $rootScope.$watch(function () { return targetItemType; }, function () {
                calculateStepsToDisplay();
            });

            calculateStepsToDisplay();
            return {
                steps: stepsToDisplay
            };
        }
    ])
    .directive('fieldGroup', ['Utils', function (Utils) {
        return {
            restrict: 'E',
            require: '^form',
            replace: true,
            transclude: true,
            template: '<div class="form-group" ng-transclude></div>',
            link: function ($scope, el, attrs, formCtrl) {
                var formName = formCtrl.$name;
                var fieldName = attrs['for'];
                var watchExpression = Utils.buildFieldValidationExpression(formName, fieldName);

                $scope.$watch(watchExpression, function () {
                    var field = $scope[formName][fieldName];
                    if (_.isUndefined(field)) {
                        console.log('found undefined field @', formName, fieldName);
                        console.log('form:', $scope[formName]);
                        return;
                    }
                    if (field.$pristine) {
                        return;
                    }

                    var hasError = false;
                    var errors = field.$error;

                    for (var error in errors) {
                        if (errors.hasOwnProperty(error) && errors[error]) {
                            hasError = true;
                            break;
                        }
                    }

                    if (hasError) {
                        el.addClass('has-error');
                    } else {
                        el.removeClass('has-error');
                    }
                })
            }
        }
    }])
    .directive('selectize', ['$timeout', function ($timeout) {
        return {
            restrict: 'A',
            require: '?ngModel',
            scope: {
                listOptions: '='
            },
            link: function (scope, element, attrs, ngModel) {
                var intFilter = function intFilter(inputValue) {
                    return _.parseInt(inputValue);
                };

                ngModel.$parsers.push(intFilter);

                scope.$watch(function () { return ngModel.$modelValue; }, function (value) {
                    if (_.isUndefined(value)) return;

                    $timeout(function () {
                        selectize.setValue(value);
                    });
                });

                element
                    .selectize({
                        valueField: 'id',
                        labelField: 'name',
                        searchField: 'name',
                        options: scope.listOptions,
                        create: false
                    });

                var selectize = element[0].selectize;
            }
        };
    }])
    .directive('serverSideValidation', [function () {
        return {
            restrict: 'A',
            require: ['^ngModel', '^form'],
            link: function (scope, element, attrs, ctrls) {
                var modelCtrl = ctrls[0],
                    formCtrl = ctrls[1];

                var fieldName = modelCtrl.$name;
                var formName = formCtrl.$name;
                var watchExpression = 'serverSideValidationErrors.' + fieldName;
                scope.$watch(watchExpression, function () {
                    if (scope.serverSideValidationErrors[fieldName]) {
                        scope[formName][fieldName].$setValidity('server', false);
                    } else {
                        scope[formName][fieldName].$setValidity('server', true);
                    }
                });

                var validityCleanup = function (value) {
                    delete scope.serverSideValidationErrors[fieldName];
                    return value;
                };

                modelCtrl.$parsers.push(validityCleanup);
            }
        }
    }])
    .directive('validationMessage', ['Utils', 'DefaultValidationMessages', function (Utils, DefaultValidationMessages) {
        return {
            restrict: 'E',
            require: '^form',
            replace: true,
            template: '<span class="help-block"></span>',
            link: function ($scope, el, attrs, formCtrl) {
                var formName = formCtrl.$name;
                var fieldName = attrs['for'];

                $scope.$watch(formName + '.' + fieldName + '.$error', function () {
                    var field = $scope[formName][fieldName];
                    var show = field.$invalid && field.$dirty;
                    el.css('display', show ? '' : 'none');

                    var html = '';
                    if (show) {
                        var errors = field.$error;
                        for (var error in errors) {
                            if (errors.hasOwnProperty(error) && errors[error]) {
                                var msg = null;
                                if (attrs[error]) {
                                    msg = attrs[error];
                                } else if (_.has($scope.serverSideValidationErrors, fieldName)) {
                                    msg = $scope.serverSideValidationErrors[fieldName];
                                } else if (_.has(DefaultValidationMessages, error)) {
                                    msg = DefaultValidationMessages[error];
                                }

                                if (msg === null) {
                                    continue;
                                }

                                html += '<span>' + msg + '</span>';
                            }
                        }
                    }

                    el.html(html);
                }, true);
            }
        }
    }])
    .controller('ProgressCtrl', ['$scope', 'ItemCreationService', function ($scope, ItemCreationService) {
        $scope.steps = ItemCreationService.steps;
    }])
    .controller('GeneralCtrl', ['ShopCategoriesSelectList', '$scope', function (ShopCategoriesSelectList, $scope) {
        $scope.staticData = {
            shopCategories: ShopCategoriesSelectList
        };

        $scope.inputData = {
            shop_category_id: undefined,
            title: undefined,
            price: undefined
        };

        $scope.canSave = function canSave() {
            return $scope.generalForm.$dirty && $scope.generalForm.$valid;
        };
    }]);
