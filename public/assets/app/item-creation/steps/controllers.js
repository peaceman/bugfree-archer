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
                currentStep.inputData = $scope.inputData;
                currentStep.state = 'done';
                ItemCreationService.gotoNextStep();
            };

            $scope.$watch('inputData.shop_category_id', function (newShopCategoryId) {
                if (_.isUndefined(newShopCategoryId)) return;

                var newShopCategory = _.find(ShopCategoriesSelectList, {id: newShopCategoryId});
                ItemCreationService.targetItemType = newShopCategory.targetItemType;
            });
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
                musicPluginBanks: [],
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

                return plugin;
            };

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
                    bank = {id: bankName, name: bankName};
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

            var determineAvailablePluginBanks = function determineAvailablePluginBanks(newData, oldData) {
                if (newData === oldData) {
                    console.debug('ignore call to determineAvailablePluginBanks; data is identical');
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
            };

            // fill the bank selection field with banks in dependency of the currently selected plugins
            $scope.$watch('inputData.music_plugin_ids', determineAvailablePluginBanks, true);
            $scope.$watch('inputData.music_plugin_bank_ids', determineAvailablePluginBanks, true);

            $scope.canSave = function canSave() {
                var result = $scope.projectFileForm.$dirty && $scope.projectFileForm.$valid;
                currentStep.state = result ? 'done' : 'open';

                return result;
            };

            $scope.save = function save() {
                currentStep.inputData = $scope.inputData;
                currentStep.state = 'done';
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
                currentStep.inputData = $scope.inputData;
                currentStep.state = 'done';
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
        '$scope', '$state', 'ItemCreationService',
        function ($scope, $state, ItemCreationService) {
            if (!ItemCreationService.activateStepWithRoute($state.current.name)) {
                console.log('cancel OverviewCtrl');
                return;
            }

            $scope.allSteps = _.pluck(ItemCreationService.stepsToDisplay, 'inputData');
        }
    ]);
