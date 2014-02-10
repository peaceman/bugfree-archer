angular.module('edmShopItems')
    .factory('DefaultValidationMessages', [
        function () {
            return {
                required: 'This field is required',
                pattern: 'Invalid characters',
                minlength: 'Too short',
                maxlength: 'Too long'
            };
        }
    ])
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
    }]);
