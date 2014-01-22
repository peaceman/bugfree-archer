angular.module('edmShopItems', ['ui.router'])
    .config(['$stateProvider', '$urlRouterProvider', function ($stateProvider, $urlRouterProvider) {
        $urlRouterProvider.otherwise('/general');

        $stateProvider
            .state('general', {
                url: '/general',
                templateUrl: '/templates/general.html',
                controller: 'GeneralCtrl'
            })
    }])
    .controller('GeneralCtrl', ['$scope', function ($scope) {
        var initializeCategorySelectInput = function initializeCategorySelectInput() {
            $('#inputCategory').selectize();
        };

        initializeCategorySelectInput();
    }])
