angular.module('edmShopItems')
    .factory('DefaultStep', [
        '$localStorage', 'USER_ID',
        function ($localStorage, USER_ID) {
            return {
                isActive: false,
                heading: undefined,
                text: undefined,
                route: undefined,
                requiredTargetItemTypes: [],
                state: 'open',
                inputData: {},
                generateLocalStorageKey: function generateLocalStorageKey() {
                    var key = 'u' + USER_ID + '_step-' + this.route;
                    return key;
                },
                loadFromLocalStorage: function loadFromLocalStorage() {
                    var dataToLoad = $localStorage[this.generateLocalStorageKey()];
                    if (!dataToLoad) {
                        return;
                    }

                    this.inputData = dataToLoad.inputData;
                    this.state = dataToLoad.state;

                    console.debug('loadFromLocalStorage route:', this.route, 'dataToLoad:', dataToLoad);
                },
                storeInLocalStorage: function storeInLocalStorage() {
                    var dataToStore = {
                        inputData: this.inputData,
                        state: this.state
                    };

                    $localStorage[this.generateLocalStorageKey()] = dataToStore;

                    console.debug('storeInLocalStorage route:', this.route, 'dataToStore:', dataToStore);
                },
                finishStep: function finishStep(inputData) {
                    this.inputData = inputData;
                    this.state = 'done';

                    console.info('stored data in step', this.inputData, this.state);
                },
                activate: function activate() {
                    console.warn('step activate', this.route);
                    this.isActive = true;
                },
                deactivate: function deactivate() {
                    console.warn('step deactivate', this.route);
                    this.storeInLocalStorage();
                    this.isActive = false;
                },
                initialize: function initialize() {
                    console.warn('step initialize', this.route);
                    this.loadFromLocalStorage();
                }
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
                        var step = _.defaults(step, _.cloneDeep(DefaultStep));
                        _.bindAll(step);

                        step.initialize();
                        return step;
                    });
                }
            ];

            this.setSteps = function setSteps(newSteps) {
                steps = newSteps;
            };
        }
    );
