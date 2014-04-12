angular.module('edmShopItems')
    .factory('DefaultStep', [
        '$localStorage', 'USER_ID', 'SHOP_ITEM_ID',
        function ($localStorage, USER_ID, SHOP_ITEM_ID) {
            return {
                isActive: false,
                heading: undefined,
                text: undefined,
                route: undefined,
                requiredTargetItemTypes: [],
                state: 'open',
                inputData: {},
                generateLocalStorageKey: function generateLocalStorageKey() {
                    var key = 'u' + USER_ID + '_si' + SHOP_ITEM_ID + '_step-' + this.route;
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
                clearLocalStorage: function clearLocalStorage() {
                    var storageKey = this.generateLocalStorageKey();
                    delete $localStorage[storageKey];

                    console.warn('deleted', storageKey, 'from $localStorage');
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
                initialize: function initialize(dataToLoad) {
                    console.warn('step initialize', this.route);

                    if (dataToLoad && !_.has($localStorage, this.generateLocalStorageKey())) {
                        this.inputData = dataToLoad.inputData;
                        this.state = dataToLoad.state;
//                        console.info(dataToLoad);
                    } else {
                        this.loadFromLocalStorage();
                    }
                }
            };
        }
    ])
    .provider(
        'ItemCreationSteps',
        function () {
            var steps = [];

            this.$get = [
                'DefaultStep', 'EDIT_DATA',
                function (DefaultStep, editData) {
                    return _.map(steps, function (step) {
                        var step = _.defaults(step, _.cloneDeep(DefaultStep));
                        _.bindAll(step);

                        if (!editData) {
                            step.initialize();
                        } else {
                            step.initialize(editData[step.route]);
                        }

                        return step;
                    });
                }
            ];

            this.setSteps = function setSteps(newSteps) {
                steps = newSteps;
            };
        }
    );
