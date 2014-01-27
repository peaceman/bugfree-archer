angular.module('edmShopItems')
    .controller('ProgressCtrl', ['$scope', 'ItemCreationService', function ($scope, ItemCreationService) {
        $scope.steps = ItemCreationService.steps;
    }])
    .controller('GeneralCtrl', ['ShopCategoriesSelectList', '$scope', function (ShopCategoriesSelectList, $scope) {
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
    }]);