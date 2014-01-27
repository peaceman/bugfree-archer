angular.module('edmShopItems')
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
    }]);