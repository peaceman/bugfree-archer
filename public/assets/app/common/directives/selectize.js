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
    }]);