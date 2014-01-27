angular.module('edmShopItems')
    .controller('ProgressCtrl', ['$scope', 'ItemCreationService', function ($scope, ItemCreationService) {
        $scope.service = ItemCreationService;
    }])
    .controller('GeneralCtrl', [
        'ShopCategoriesSelectList', 'ItemCreationService', '$scope', 
        function (ShopCategoriesSelectList, ItemCreationService, $scope) {
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

            $scope.$watch('inputData.shop_category_id', function (newShopCategoryId) {
                if (_.isUndefined(newShopCategoryId)) return;
                
                var newShopCategory = _.find(ShopCategoriesSelectList, {id: newShopCategoryId});
                ItemCreationService.targetItemType = newShopCategory.targetItemType;
            });
        }
    ]);