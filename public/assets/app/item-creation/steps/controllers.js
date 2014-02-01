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
                music_plugin_ids: undefined,
                bpm: undefined,
                description: undefined
            })

            $scope.inputData = currentStep.inputData;
            $scope.staticData = {
                musicGenres: MusicGenresSelectList,
                musicPlugins: MusicPluginsSelectList,
                musicPrograms: MusicProgramsSelectList,
            };

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
            ItemCreationService.activateStepWithRoute($state.current.name);
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