angular.module('edmShopItems')
    .controller('ProgressCtrl', ['$scope', 'ItemCreationService', function ($scope, ItemCreationService) {
        $scope.service = ItemCreationService;
    }])
    .controller('GeneralCtrl', [
        'ShopCategoriesSelectList', 'ItemCreationService', '$scope', '$state',
        function (ShopCategoriesSelectList, ItemCreationService, $scope, $state) {
            ItemCreationService.activateStepWithRoute($state.current.name);
            var currentStep = ItemCreationService.getCurrentStep();
            console.debug('GeneralCtrl currentStep:', currentStep);
            _.defaults(currentStep.inputData, {
                title: undefined,
                price: undefined,
                shop_category_id: undefined
            });

            $scope.staticData = {
                shopCategories: ShopCategoriesSelectList
            };

            $scope.inputData = currentStep.inputData;

            $scope.canSave = function canSave() {
                var result = $scope.generalForm.$dirty && $scope.generalForm.$valid;
                currentStep.state = result ? 'done' : 'open';

                return result;
            };

            $scope.save = function save() {
                currentStep.finishStep($scope.inputData);
                ItemCreationService.gotoNextStep();
            };
        }
    ])
    .controller('ProjectFileCtrl', [
        '$scope', '$state', 'ItemCreationService', 'MusicGenresSelectList', 'MusicPluginsSelectList', 'MusicProgramsSelectList',
        function ($scope, $state, ItemCreationService, MusicGenresSelectList, MusicPluginsSelectList, MusicProgramsSelectList) {
            if (!ItemCreationService.activateStepWithRoute($state.current.name)) {
                console.log('cancel ProjectFileCtrl');
                return;
            }

            var currentStep = ItemCreationService.getCurrentStep();
            console.debug('ProjectFileCtrl currentStep:', currentStep);
            _.defaults(currentStep.inputData, {
                music_genre_id: undefined,
                music_program_ids: undefined,
                music_plugin_ids: [],
                music_plugin_bank_ids: [],
                bpm: undefined,
                description: undefined,
                musicPluginBanks: []
            });

            $scope.inputData = currentStep.inputData;
            $scope.staticData = {
                musicGenres: MusicGenresSelectList,
                musicPlugins: MusicPluginsSelectList,
                musicPrograms: MusicProgramsSelectList,
                listConfigs: {
                    banks: {
                        create: false,
                        persist: false
                    },
                    newPluginBank: {
                        maxItems: 1
                    }
                }
            };

            var createNewPluginWithId = function createNewPluginWithId(pluginId) {
                var plugin = {id: pluginId, name: pluginId, banks: []};
                $scope.staticData.musicPlugins.push(plugin);

                console.warn('createNewPluginWithId', pluginId);
                return plugin;
            };

            _.each($scope.inputData.musicPluginBanks, function (musicPluginBank) {
                if (!_.has(musicPluginBank, 'music_plugin_id')) {
                    return;
                }

                if (!_.isString(musicPluginBank.music_plugin_id)) {
                    console.warn('music_plugin_id of newly created bank is not a string');
                }

                var musicPluginId = musicPluginBank.music_plugin_id;
                var bankPlugin = _.find($scope.staticData.musicPlugins, {id: musicPluginId});

                if (_.isUndefined(bankPlugin)) {
                    bankPlugin = createNewPluginWithId(musicPluginId);
                }

                if (_.isUndefined(_.find(bankPlugin.banks, {id: musicPluginBank.id}))) {
                    bankPlugin.banks.push(musicPluginBank);
                }
            });

            $scope.saveNewPluginBank = function () {
                console.log('saving new plugin bank data', this.inputData.newBank);
                var pluginId = this.inputData.newBank.music_plugin_id;
                var plugin = _.find(this.staticData.musicPlugins, {id: pluginId});
                if (_.isUndefined(plugin)) {
                    plugin = createNewPluginWithId(pluginId);
                }

                var bankName = this.inputData.newBank.name;
                var bank = _.find(plugin.banks, {id: bankName});
                if (_.isUndefined(bank)) {
                    bank = {id: bankName, name: bankName, music_plugin_id: plugin.id};
                    plugin.banks.push(bank);
                }

                if (!_.contains(this.inputData.music_plugin_ids, plugin.id)) {
                    if (!_.isArray(this.inputData.music_plugin_ids))
                        this.inputData.music_plugin_ids = [];

                    var newData = _.clone(this.inputData.music_plugin_ids);
                    newData.push(plugin.id);
                    this.inputData.music_plugin_ids = newData;
                }

                if (!_.contains(this.inputData.music_plugin_bank_ids, bank.id)) {
                    if (!_.isArray(this.inputData.music_plugin_bank_ids))
                        this.inputData.music_plugin_bank_ids = [];

                    var newData = _.clone(this.inputData.music_plugin_bank_ids);
                    newData.push(bank.id);
                    this.inputData.music_plugin_bank_ids = newData;
                }

                this.resetNewPluginBankForm();
            };

            $scope.resetNewPluginBankForm = function () {
                this.inputData.newBank = {};
                this.$hide();
            };

            // todo musicPluginBanks should be externalized as a service to avoid bugs, when the data is loaded from local storage
            var initializedMusicPluginBanks = false;
            var determineAvailablePluginBanks = function determineAvailablePluginBanks(newData, oldData) {
                if (newData === oldData && initializedMusicPluginBanks) {
                    console.debug('ignore call to determineAvailablePluginBanks; data is identical', newData, oldData);
                    return;
                }

                var selectedPlugins = _.map($scope.inputData.music_plugin_ids, function (musicPluginId) {
                    var plugin = _.find($scope.staticData.musicPlugins, {id: musicPluginId});
                    if (_.isUndefined(plugin)) {
                        plugin = createNewPluginWithId(musicPluginId);
                    }

                    return plugin;
                });

                var banks = _.flatten(selectedPlugins, 'banks');

                console.log('set available banks', banks);
                $scope.inputData.musicPluginBanks = banks;
                initializedMusicPluginBanks = true;
            };

            // fill the bank selection field with banks in dependency of the currently selected plugins
            $scope.$watch('inputData.music_plugin_ids', determineAvailablePluginBanks, true);
            $scope.$watch('inputData.music_plugin_bank_ids', determineAvailablePluginBanks, true);
            determineAvailablePluginBanks();

            $scope.canSave = function canSave() {
                var result = $scope.projectFileForm.$dirty && $scope.projectFileForm.$valid;
                currentStep.state = result ? 'done' : 'open';

                return result;
            };

            $scope.save = function save() {
                currentStep.finishStep($scope.inputData);
                ItemCreationService.gotoNextStep();
            };
        }
    ])
    .controller('UploadFileCtrl', [
        '$scope', '$state', 'ItemCreationService', 'ResourceFiles', 'ShopCategoriesFileConfig',
        function ($scope, $state, ItemCreationService, ResourceFiles, ShopCategoriesFileConfig) {
            if (!ItemCreationService.activateStepWithRoute($state.current.name)) {
                console.log('cancel UploadFileCtrl')
                return;
            }
            $scope.uploadFileConfig = ShopCategoriesFileConfig[ItemCreationService.targetItemType];
            $scope.changeFileUse = function changeFileUse(file, fileUseType, fileUse) {
                if (fileUse.amount === 1) {
                    var currentlyClassifiedAsType = _.find($scope.inputData.selectedFiles, {use_as: fileUseType});
                    if (_.isEmpty(currentlyClassifiedAsType)) {
                        return;
                    }

                    currentlyClassifiedAsType.use_as = undefined;
                }
            };

            var currentStep = ItemCreationService.getCurrentStep();
            console.debug('UploadFileCtrl currentStep:', currentStep);

            $scope.inputData = _.defaults(currentStep.inputData, {
                newlyUploadedFiles: [],
                selectedFiles: []
            });

            ResourceFiles.all.getList().then(function (data) {
                _.each($scope.inputData.selectedFiles, function (selectedFile) {
                    _.remove(data, {id: selectedFile.id});
                    data.unshift(selectedFile);
                });

                $scope.resourceFiles = data;
            });

            $scope.uploadProgress = 0;
            $scope.uploadFinished = function uploadFinished(flowFile, fileData) {
                var uploadedFile = JSON.parse(fileData);
                $scope.inputData.newlyUploadedFiles.push(uploadedFile);
                $scope.resourceFiles.push(uploadedFile);
            };
            $scope.updateProgress = function updateProgress(flow) {
                $scope.uploadProgress = flow.progress() * 100;
            };

            $scope.inputData = currentStep.inputData;
            $scope.canSave = function () {
                var result = _.every($scope.uploadFileConfig, function (fileUse, fileUseType) {
                    if (!fileUse.required) {
                        return true;
                    }

                    var filesToUseAsType = _.filter($scope.inputData.selectedFiles, {use_as: fileUseType});
                    return fileUse.amount === filesToUseAsType.length;
                });

                currentStep.state = result ? 'done' : 'open';

                return result;
            };

            $scope.save = function () {
                $scope.inputData.selectedFiles = _.filter($scope.inputData.selectedFiles, 'use_as');
                currentStep.finishStep($scope.inputData);
                ItemCreationService.gotoNextStep();
            };

            var addToSelectedFiles = function addToSelectedFiles(file) {
                $scope.inputData.selectedFiles.push(file);
                file.isSelected = true;
            };

            var removeFromSelectedFiles = function removeFromSelectedFiles(file) {
                _.remove($scope.inputData.selectedFiles, file);
                file.isSelected = false;
            };

            $scope.removeFromSelectedFiles = removeFromSelectedFiles;
            $scope.toggleFileSelection = function toggleFileSelection(file) {
                console.debug(file);
                file.isSelected = !file.isSelected;

                if (file.isSelected) {
                    addToSelectedFiles(file);
                } else {
                    removeFromSelectedFiles(file);
                }
            };
        }
    ])
    .controller('OverviewCtrl', [
        'SHOP_ITEMS_LISTING_URL', '$scope', '$state', 'ItemCreationService', 'ShopCategoriesSelectList', 'MusicGenresSelectList', 'MusicPluginsSelectList', 'MusicProgramsSelectList', '$http', '$timeout', 'SHOP_ITEM_ID',
        function (SHOP_ITEMS_LISTING_URL, $scope, $state, ItemCreationService, ShopCategoriesSelectList, MusicGenresSelectList, MusicPluginsSelectList, MusicProgramsSelectList, $http, $timeout, SHOP_ITEM_ID) {
            if (!ItemCreationService.activateStepWithRoute($state.current.name)) {
                console.log('cancel OverviewCtrl');
                return;
            }

            $scope.allSteps = [];
            $scope.shopCategoriesById = _.indexBy(ShopCategoriesSelectList, 'id');

            $scope.$watch(
                function () {
                    return ItemCreationService.stepsToDisplay;
                },
                function (steps) {
                    $scope.allSteps = _.filter(steps, function (step) {
                        return step.route !== 'overview';
                    });
                }
            );

            function fetchEntityName(entityList, entityId) {
                if (_.isString(entityId)) {
                    return entityId;
                }

                var entity = _.find(entityList, {id: entityId});
                if (!entity) {
                    console.error('couldnt find entity in entity list', entityList, entityId);
                }
                return entity.name;
            }

            var fetchEntityNames = function fetchEntityNames(entityList, entityIds) {
                return _.map(entityIds, _.partial(fetchEntityName, entityList));
            };
            $scope.fetchEntityNames = fetchEntityNames;

            $scope.fetchMusicGenreName = _.partial(fetchEntityName, MusicGenresSelectList);
            $scope.fetchMusicProgramNames = _.partial(fetchEntityNames, MusicProgramsSelectList);
            $scope.fetchMusicPluginNames = _.partial(fetchEntityNames, MusicPluginsSelectList);

            $scope.save = function () {
                var inputData = _.transform($scope.allSteps, function (result, step) {
                    var stepData = null;

                    switch (step.route) {
                        case 'project-file':
                            stepData = _.pick(step.inputData, [
                                'bpm', 'description', 'music_genre_id'
                            ]);

                            var convertIdOrNameToObject = function (idOrName) {
                                return {
                                    id_or_name: idOrName,
                                    additional_data: {}
                                };
                            };

                            stepData.music_plugin_banks = _.map(step.inputData.music_plugin_bank_ids, function (idOrName) {
                                var toReturn = convertIdOrNameToObject(idOrName);

                                if (_.isNaN(Number(idOrName))) {
                                    var pluginBankData = _.find(step.inputData.musicPluginBanks, {
                                        name: idOrName
                                    });

                                    if (pluginBankData) {
                                        toReturn.additional_data.music_plugin_id = pluginBankData.music_plugin_id;
                                    }
                                }

                                return toReturn;
                            });

                            stepData.music_plugins = _.map(step.inputData.music_plugin_ids, convertIdOrNameToObject);
                            stepData.music_programs = _.map(step.inputData.music_program_ids, convertIdOrNameToObject);
                            break;
                        default:
                            stepData = step.inputData;
                            break;
                    }

                    result[step.route] = stepData;
                }, {});

//                console.info(inputData);
                var success = function (data, status, headers, config) {
//                        console.debug(arguments);

                    _.each($scope.allSteps, function (step) {
                        step.clearLocalStorage();
                    });

                    $timeout(function () {
                        window.location.href = SHOP_ITEMS_LISTING_URL;
                    }, 250);
                };
                var failure = function (data, status, headers, config) {
                    console.error(arguments);
                };

                var requestBody = {shop_item_data: inputData};
                var promise = SHOP_ITEM_ID
                    ? $http.put('/api/shop-items/' + SHOP_ITEM_ID, requestBody)
                    : $http.post('/api/shop-items', requestBody);

                promise
                    .success(success)
                    .error(failure);
            };
        }
    ]);
