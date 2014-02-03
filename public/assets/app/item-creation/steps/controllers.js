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
                return $scope.generalForm.$dirty && $scope.generalForm.$valid;
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
            ItemCreationService.activateStepWithRoute($state.current.name);
            var currentStep = ItemCreationService.getCurrentStep();
            console.debug('ProjectFileCtrl currentStep:', currentStep);
            _.defaults(currentStep.inputData, {
                music_genre_id: undefined,
                music_program_ids: undefined,
                music_plugin_ids: [],
                music_plugin_bank_ids: [],
                new_plugin_bank_dependencies: [],
                bpm: undefined,
                description: undefined
            });

            $scope.inputData = currentStep.inputData;
            $scope.staticData = {
                musicGenres: MusicGenresSelectList,
                musicPlugins: MusicPluginsSelectList,
                musicPrograms: MusicProgramsSelectList,
                musicPluginBanks: [],
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

            $scope.saveNewPluginBank = function () {
                console.log('saving new plugin bank data', this.inputData.newBank);
                var pluginId = this.inputData.newBank.music_plugin_id;
                var plugin = _.find(this.staticData.musicPlugins, {id: pluginId});
                if (_.isUndefined(plugin)) {
                    plugin = {id: pluginId, name: pluginId, banks: []};
                    this.staticData.musicPlugins.push(plugin);
                }

                var bankName = this.inputData.newBank.name;
                var bank = _.find(plugin.banks, {id: bankName});
                if (_.isUndefined(bank)) {
                    bank = {id: bankName, name: bankName};
                    plugin.banks.push(bank);
                }

                if (!_.contains(this.inputData.music_plugin_ids, plugin.id)) {
                    if (!_.isArray(this.inputData.music_plugin_ids)) this.inputData.music_plugin_ids = [];
                    this.inputData.music_plugin_ids.push(plugin.id);
                }

                if (!_.contains(this.inputData.music_plugin_bank_ids, bank.id)) {
                    if (!_.isArray(this.inputData.music_plugin_bank_ids)) this.inputData.music_plugin_bank_ids = [];
                    this.inputData.music_plugin_bank_ids.push(bank.id);
                }

                this.resetNewPluginBankForm();
            };

            $scope.resetNewPluginBankForm = function () {
                this.inputData.newBank = {};
                this.$hide();
            };

            var determineAvailablePluginBanks = function () {
                console.log('determineAvailablePluginBanks', arguments);

                var selectedPlugins = _.map($scope.inputData.music_plugin_ids, function (musicPluginId) {
                    var plugin = _.find($scope.staticData.musicPlugins, {id: musicPluginId});
                    return plugin;
                });

                var banks = _.flatten(selectedPlugins, 'banks');

                console.log('set available banks', banks);
                $scope.staticData.musicPluginBanks = banks;
            };

            // fill the bank selection field with banks in dependency of the currently selected plugins
            $scope.$watch('inputData.music_plugin_ids', determineAvailablePluginBanks, true);
            $scope.$watch('inputData.music_plugin_bank_ids', determineAvailablePluginBanks, true);

            $scope.canSave = function canSave() {
                return $scope.projectFileForm.$dirty && $scope.projectFileForm.$valid;
            };

            $scope.save = function save() {
                currentStep.inputData = $scope.inputData;
                currentStep.state = 'done';
                ItemCreationService.gotoNextStep();
            };
        }
    ])
    .controller('UploadFileCtrl', [
        '$scope', '$state', 'ItemCreationService',
        function ($scope, $state, ItemCreationService) {
            if (!ItemCreationService.activateStepWithRoute($state.current.name)) {
                return;
            }
            
            var currentStep = ItemCreationService.getCurrentStep();
            console.debug('UploadFileCtlr currentStep:', currentStep);

            _.defaults(currentStep.inputData, {
                files: []
            });

            $scope.inputData = currentStep.inputData;
            $scope.canSave = function () {
                return false;
            };
            $scope.save = function () {
                return false;
            };
        }
    ])
    .controller('OverviewCtrl', [
        '$scope', '$state', 'ItemCreationService',
        function ($scope, $state, ItemCreationService) {

        }
    ]);