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
                var canHoldMultipleValues = _.has(attrs, 'multiple') && attrs.multiple;
                var valueSplitter = function valueSplitter(value) {
                    return _.map(value.split(','), function (singleValue) {
                        var trimmedValue = singleValue.trim();
                        var possibleIntValue = _.parseInt(trimmedValue);
                        var valueIsNumeric = !_.isNaN(possibleIntValue) && _.isNumber(possibleIntValue);

                        return valueIsNumeric ? possibleIntValue : trimmedValue;
                    });
                };

                if (canHoldMultipleValues) {
                    ngModel.$parsers.push(valueSplitter);
                }

                scope.$watch(function () { return ngModel.$modelValue; }, function (value) {
                    if (_.isUndefined(value)) return;

                    $timeout(function () {
                        console.log('set value', value);
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