angular.module('edmShopItems')
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
                    selectize.setValue(value);
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
    .directive('selectizeTags', ['$timeout', function ($timeout) {
        return {
            restrict: 'A',
            require: '?ngModel',
            scope: {
                listOptions: '=?',
                listConfig: '=?'
            },
            link: function (scope, element, attrs, ngModel) {
                if (_.isUndefined(scope.listConfig)) {
                    scope.listConfig = {};
                }

                var numericFilter = function numericFilter(value) {
                    var possibleIntValue = _.parseInt(value);
                    var valueIsNumeric = !_.isNaN(possibleIntValue) && _.isNumber(possibleIntValue);

                    return valueIsNumeric ? possibleIntValue : value;
                };

                var canHoldMultipleValues = _.has(attrs, 'multiple') && attrs.multiple;
                var valueSplitter = function valueSplitter(value) {
                    if (_.isUndefined(value)) return;
                    return _.map(value.split(','), function (singleValue) {
                        var trimmedValue = singleValue.trim();
                        return numericFilter(trimmedValue);
                    });
                };

                if (canHoldMultipleValues) {
                    ngModel.$parsers.push(valueSplitter);
                } else {
                    ngModel.$parsers.push(numericFilter);
                }

                scope.$watch(function () { return ngModel.$modelValue; }, function (value) {
                    if (_.isUndefined(value)) return;

                        console.log('selectize set value', value);
                        value = _.isArray(value) ? value : [value];
                        // todo: add as extension to selectize
                        _.each(value, function (option) {
                            if (_.isNumber(option)) {
                                return;
                            }

                            selectize.addOption({
                                id: option,
                                name: option
                            });
                        });
                        selectize.setValue(value);
                }, true);

                // removes or adds options to the selectize object
                scope.$watch('listOptions', function (newListOptions, oldListOptions) {
                    var optionsToRemove = _.difference(oldListOptions, newListOptions);
                    _.each(_.pluck(optionsToRemove, 'id'), selectize.removeOption, selectize);

                    var optionsToAdd = _.difference(newListOptions, oldListOptions);
                    _.each(optionsToAdd, selectize.addOption, selectize);
                });

                element
                    .selectize(_.defaults(scope.listConfig, {
                        valueField: 'id',
                        labelField: 'name',
                        searchField: 'name',
                        create: true,
                        options: scope.listOptions,
                    }));

                var selectize = element[0].selectize;
            }
        }
    }]);