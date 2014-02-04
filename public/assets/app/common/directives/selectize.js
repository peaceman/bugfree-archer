angular.module('edmShopItems')
    .factory('SelectizeUtils', [
        '$timeout',
        function ($timeout) {
            var numberParser = function numberParser(value) {
                return _.parseInt(value);
            };

            var ensureNumberTypeFilter = function ensureNumberTypeFilter(value) {
                var possibleNumber = _.parseInt(value);
                var valueIsNumber = !_.isNaN(possibleNumber) && _.isNumber(possibleNumber);

                return valueIsNumber ? possibleNumber : value;
            };

            var addNewValuesAsOptionsToSelectize = function addNewValuesAsOptionsToSelectize(selectize, newValues) {
                console.log('addNewValuesAsOptionsToSelectize', newValues);
                _.each(newValues, function (newValue) {
                    selectize.addOption({
                        id: newValue,
                        name: newValue
                    });
                });
            };

            var selectizeValueDiffers = function selectizeValueDiffers(selectize, newValue) {
                var oldValue = ensureArray(selectize.lastSettedValue);
                newValue = ensureArray(newValue);
                var result = _.difference(oldValue, newValue).length !== 0;
                return result;
            };

            var ensureArray = function (value) {
                return _.isArray(value) ? value : [value];
            };

            var setValueOnSelectize = function setValueOnSelectize(selectize, value) {
                if (_.isUndefined(value)) return;
                if (!selectizeValueDiffers(selectize, value)) return;

                var valueArray = _.isArray(value) ? value : [value];
                var newValues = _.filter(valueArray, function (possibleNewValue) {
                    return !_.isNumber(possibleNewValue);
                });

                if (!_.isEmpty(newValues))
                    addNewValuesAsOptionsToSelectize(selectize, newValues);

                $timeout(function () {
                    selectize.setValue(value);    
                });
            };

            return {
                numberParser: numberParser,
                ensureNumberTypeFilter: ensureNumberTypeFilter,
                setValueOnSelectize: setValueOnSelectize
            };
        }
    ])
    .directive('selectize', ['$timeout', 'SelectizeUtils', function ($timeout, SelectizeUtils) {
        return {
            restrict: 'A',
            require: 'ngModel',
            scope: {
                listOptions: '='
            },
            link: function (scope, element, attrs, ngModel) {
                ngModel.$parsers.push(SelectizeUtils.numberParser);

                element
                    .selectize({
                        valueField: 'id',
                        labelField: 'name',
                        searchField: 'name',
                        options: scope.listOptions,
                        create: false
                    });

                var selectize = element[0].selectize;
                $timeout(function () {
                    SelectizeUtils.setValueOnSelectize(selectize, ngModel.$modelValue);
                });
            }
        };
    }])
    .directive('selectizeTags', ['$rootScope', '$timeout', 'SelectizeUtils', function ($rootScope, $timeout, SelectizeUtils) {
        return {
            restrict: 'A',
            require: '?ngModel',
            scope: {
                listOptions: '=',
                listConfig: '=?'
            },
            priority: 10,
            link: function (scope, element, attrs, ngModel) {
                if (_.isUndefined(scope.listConfig)) {
                    scope.listConfig = {};
                }

                var canHoldMultipleValues = _.has(attrs, 'multiple') && attrs.multiple;
                var valueSplitter = function valueSplitter(value) {
                    if (_.isUndefined(value)) return;
                    return _.map(value.split(','), function (singleValue) {
                        var trimmedValue = singleValue.trim();
                        return SelectizeUtils.ensureNumberTypeFilter(trimmedValue);
                    });
                };

                if (canHoldMultipleValues) {
                    ngModel.$parsers.push(valueSplitter);
                } else {
                    ngModel.$parsers.push(SelectizeUtils.ensureNumberTypeFilter);
                }

                ngModel.$render = function () {
                    SelectizeUtils.setValueOnSelectize(selectize, ngModel.$viewValue);
                };

                // removes or adds options to the selectize object
                scope.$watch('listOptions', function (newListOptions, oldListOptions) {
                    var optionsToRemove = _.difference(oldListOptions, newListOptions);
                    _.each(_.pluck(optionsToRemove, 'id'), selectize.removeOption, selectize);

                    var optionsToAdd = _.difference(newListOptions, oldListOptions);
                    _.each(optionsToAdd, selectize.addOption, selectize);
                });

                element
                    .selectize(_.defaults(scope.listConfig, {
                        plugins: ['drag_drop'],
                        valueField: 'id',
                        labelField: 'name',
                        searchField: 'name',
                        create: true,
                        options: scope.listOptions,
                    }));

                var selectize = element[0].selectize;
                $timeout(function () {
                    SelectizeUtils.setValueOnSelectize(selectize, ngModel.$modelValue);
                });
            }
        }
    }]);