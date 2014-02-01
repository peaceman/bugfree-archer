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
    .directive('selectizeTags', ['$timeout', function ($timeout) {
        return {
            restrict: 'A',
            require: '?ngModel',
            scope: {
                listOptions: '='
            },
            link: function (scope, element, attrs, ngModel) {
                var numericFilter = function numericFilter(value) {
                    var possibleIntValue = _.parseInt(value);
                    var valueIsNumeric = !_.isNaN(possibleIntValue) && _.isNumber(possibleIntValue);

                    return valueIsNumeric ? possibleIntValue : value;
                };

                var canHoldMultipleValues = _.has(attrs, 'multiple') && attrs.multiple;
                var valueSplitter = function valueSplitter(value) {
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

                    $timeout(function () {
                        console.log('set value', value);
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
                    });
                });

                element
                    .selectize({
                        valueField: 'id',
                        labelField: 'name',
                        searchField: 'name',
                        create: true,
                        options: scope.listOptions,
                    });

                var selectize = element[0].selectize;
            }
        }
    }]);