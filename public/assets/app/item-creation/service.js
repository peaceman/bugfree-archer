angular.module('edmShopItems')
    .factory('ItemCreationServiceFunctions', [
        '$rootScope', '$state',
        function ($rootScope, $state) {

            return {
                fetchStepByRoute: function (route) {
                    return _.find(this.steps, {route: route});
                },
                canActivateStep: function (step) {
                    console.debug('canActivateStep', step);
                    console.log(this.currentStepIndex);
                    if (!this.stepRequirementsAreMet(step)) return false;

                    var stepIndex = _.findIndex(this.stepsToDisplay, step);
                    if (_.isUndefined(this.currentStepIndex) && stepIndex === 0) return true;
                    if (this.currentStepIndex === stepIndex) return true;

                    var length = this.stepsToDisplay.length;
                    for (var i = 0; i < length; i++) {
                        if (i >= stepIndex) {
                            break;
                        }

                        var currentStep = this.stepsToDisplay[i];
                        if (currentStep.state !== 'done') {
                            return false;
                        }
                    }

                    return true;
                },
                fetchNextActivatableStep: function (currentStep) {
                    console.debug('fetchNextActivatableStep');
                    if (_.isUndefined(this.currentStepIndex)) {
                        console.log('current step index is undefined, start with first step');
                        return this.stepsToDisplay[0];
                    }

                    var amountOfDisplayableSteps = this.stepsToDisplay.length;
                    for (var i = this.currentStepIndex; i < amountOfDisplayableSteps; i++) {
                        var nextStep = this.stepsToDisplay[i];
                        if (_.isObject(currentStep) && nextStep == currentStep) {
                            continue;
                        }

                        if (this.canActivateStep(nextStep)) {
                            return nextStep;
                        }
                    }

                    console.log('no more steps available');
                    return undefined;
                },
                stepRequirementsAreMet: function (step) {
                    return this.filterNotDisplayableSteps(step);
                },
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
                    var routeStep = this.fetchStepByRoute(route);
                    if (routeStep == this.getCurrentStep()) return;

                    var stepToActivate = undefined;
                    if (!this.canActivateStep(routeStep)) {
                        console.log("can't activate step", routeStep);
                        var nextActivatableStep = this.fetchNextActivatableStep();
                        if (_.isUndefined(nextActivatableStep)) {
                            return;
                        } else {
                            stepToActivate = nextActivatableStep;
                        }
                    } else {
                        stepToActivate = routeStep;
                    }

                    this.activateStep(stepToActivate);
                    return stepToActivate == routeStep;
                },
                activateStep: function (step) {
                    var stepIndex = _.findIndex(this.stepsToDisplay, step);

                    console.log('activate step', step, 'found at index', stepIndex);
                    if (stepIndex === -1) {
                        return;
                    }

                    this.activateStepWithIndex(stepIndex);
                },
                ensureRouteOfCurrentStepIsActive: function () {
                    console.debug('ensureRouteOfCurrentStepIsActive', $state.current.name, this.getCurrentStep().route);

                    if ($state.current.name !== this.getCurrentStep().route) {
                        $state.transitionTo(this.getCurrentStep().route);
                    }
                },
                activateStepWithIndex: function (stepIndex) {
                    if (this.currentStepIndex == stepIndex) {
                        console.info('activateStepWithIndex: nothing to do');
                        this.ensureRouteOfCurrentStepIsActive();
                        return;
                    }
                    this.deactivateCurrentStep();

                    var step = this.stepsToDisplay[stepIndex];
                    step.isActive = true;
                    this.currentStepIndex = stepIndex;
                    this.ensureRouteOfCurrentStepIsActive();
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
                    if (this.stepsToDisplay.length === 0) {
                        console.log('gotoNextStep: stepsToDisplay is empty');
                        return;
                    }

                    var nextStep = this.fetchNextActivatableStep(this.getCurrentStep());
                    if (_.isUndefined(nextStep)) {
                        return;
                    }

                    this.activateStep(nextStep);
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