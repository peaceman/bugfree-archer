angular.module('edmShopItems')
    .factory('DefaultStep', [
        function () {
            return {
                isActive: false,
                heading: undefined,
                text: undefined,
                route: undefined,
                requiredTargetItemTypes: [],
                state: 'open',
                inputData: {}
            };
        }
    ])
    .provider(
        'ItemCreationSteps',
        function () {
            var steps = [];

            this.$get = [
                'DefaultStep',
                function (DefaultStep) {
                    return _.map(steps, function (step) {
                        return _.defaults(step, DefaultStep);
                    });
                }
            ];

            this.setSteps = function setSteps(newSteps) {
                steps = newSteps;
            };
        }
    );