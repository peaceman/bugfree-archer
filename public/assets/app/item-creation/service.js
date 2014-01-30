angular.module('edmShopItems')
    .factory('ItemCreationServiceFunctions', [
        '$rootScope', '$state',
        function ($rootScope, $state) {
            $rootScope.$on('$stateChangeError', 
function(event, toState, toParams, fromState, fromParams, error){ console.log(arguments); });
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
                    console.log('refreshed steps to display', this.stepsToDisplay);
                },
                activateStepWithRoute: function (route) {
                    var stepIndex = _.findIndex(this.stepsToDisplay, {route: route});
                    if (stepIndex === -1) return;

                    this.activateStepWithIndex(stepIndex);
                },
                activateStepWithIndex: function (stepIndex) {
                    if (this.currentStepIndex == stepIndex) return;
                    this.deactivateCurrentStep();

                    var step = this.stepsToDisplay[stepIndex];
                    step.isActive = true;
                    this.currentStepIndex = stepIndex;
                },
                deactivateCurrentStep: function () {
                    if (_.isUndefined(this.currentStepIndex)) return;

                    var step = this.stepsToDisplay[this.currentStepIndex];
                    step.isActive = false;
                },
                getCurrentStep: function () {
                    if (_.isUndefined(this.currentStepIndex)) return;

                    return this.stepsToDisplay[this.currentStepIndex];
                },
                gotoNextStep: function () {
                    if (_.isUndefined(this.currentStepIndex) || this.stepsToDisplay.length === 0)
                        return;

                    this.activateStepWithIndex(this.currentStepIndex + 1);
                    var newRoute = this.getCurrentStep().route;

                    console.log('gotoNextStep transitions to', newRoute);
                    var statePromise = $state.transitionTo(newRoute);
                    statePromise.then(function () {
                        console.log('state promise', arguments);
                    })
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

                service.resolverFunctions.push(service.refreshStepsToDisplay);
                service.watchFunctions.push(service.refreshStepsToDisplayAfterTargetItemTypeChange);
                service.initialize();

                return service;
            };
            
            return buildService();
        }
    ])