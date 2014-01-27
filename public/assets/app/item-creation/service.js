angular.module('edmShopItems')
    .factory('ItemCreationServiceFunctions', [
        '$rootScope',
        function ($rootScope) {
            return {
                filterNotDisplayableSteps: function (step) {
                    if (step.requiredTargetItemTypes.length === 0) return true;
                    return _.contains(step.requiredTargetItemTypes, this.targetItemType);
                },
                fetchStepsToDisplay: function () {
                    return _.filter(this.steps, this.filterNotDisplayableSteps);
                },
                targetItemTypeWatchClosure: function () {
                    return this.targetItemType;
                },
                refreshStepsToDisplayAfterTargetItemTypeChange: function () {
                    $rootScope.$watch(this.targetItemTypeWatchClosure, this.refreshStepsToDisplay);
                },
                refreshStepsToDisplay: function () {
                    this.stepsToDisplay = this.fetchStepsToDisplay();
                }
            };
        }
    ])
    .factory('ItemCreationService', [
        'BaseService', 'ItemCreationServiceFunctions', 'ItemCreationSteps',
        function (BaseService, ItemCreationServiceFunctions, ItemCreationSteps) {
            var defaultProperties = {
                steps: ItemCreationSteps,
                stepsToDisplay: [],
                targetItemType: undefined
            };

            var buildService = function () {
                var service = _.defaults(defaultProperties, ItemCreationServiceFunctions, BaseService);
                _.bindAll(service);

                service.watchFunctions.push(service.refreshStepsToDisplayAfterTargetItemTypeChange);
                service.initialize();

                return service;
            };
            
            return buildService();
        }
    ])