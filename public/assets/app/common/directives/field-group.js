angular.module('edmShopItems')
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
    }]);