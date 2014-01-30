angular.module('edmShopItems')
    .controller('ProgressCtrl', ['$scope', 'ItemCreationService', function ($scope, ItemCreationService) {
        $scope.service = ItemCreationService;
    }])
    .controller('GeneralCtrl', [
        'ShopCategoriesSelectList', 'ItemCreationService', '$scope', '$state',
        function (ShopCategoriesSelectList, ItemCreationService, $scope, $state) {
            ItemCreationService.activateStepWithRoute($state.current.name);
            var currentStep = ItemCreationService.getCurrentStep();

            $scope.staticData = {
                shopCategories: ShopCategoriesSelectList
            };

            $scope.inputData = {
                shop_category_id: undefined,
                title: undefined,
                price: undefined
            };

            $scope.canSave = function canSave() {
                return $scope.generalForm.$dirty && $scope.generalForm.$valid;
            };

            $scope.save = function save() {
                currentStep.inputData = $scope.inputData;
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
        '$scope', '$state', 'ItemCreationService', 'MusicGenresSelectList',
        function ($scope, $state, ItemCreationService, MusicGenresSelectList) {
            ItemCreationService.activateStepWithRoute($state.current.name);
            var currentStep = ItemCreationService.getCurrentStep();

            $scope.staticData = {
                musicGenres: MusicGenresSelectList
            };

            $scope.inputData = {
                music_genre_id: undefined,
                bpm: undefined,
                description: undefined
            };

            $scope.canSave = function canSave() {
                return $scope.projectFileForm.$dirty && $scope.projectFileForm.$valid;
            };

            $scope.save = function save() {
                currentStep.inputData = $scope.inputData;
                ItemCreationService.gotoNextStep();
            };
        }
    ]);