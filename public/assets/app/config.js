angular.module('edmShopItems')
    .config(['RestangularProvider', function (RestangularProvider) {
        RestangularProvider.setBaseUrl('/api');
    }])
    .config(['$stateProvider', '$urlRouterProvider', function ($stateProvider, $urlRouterProvider) {
        $urlRouterProvider.otherwise('/general');

        $stateProvider
            .state('general', {
                url: '/general',
                views: {
                    '@': {
                        controller: 'GeneralCtrl',
                        templateUrl: '/templates/general.html',
                        resolve: {
                            'ShopCategoriesSelectList': ['$q', 'ShopCategories', function ($q, ShopCategories) {
                                var deferred = $q.defer();

                                ShopCategories.hierarchicalItemCategories.then(function (shopCategories) {
                                    deferred.resolve(_.map(shopCategories, function (shopCategory) {
                                        return {
                                            id: shopCategory.node.id,
                                            name: shopCategory.names.join(' -> ')
                                        };
                                    }));
                                });

                                return deferred.promise;
                            }]
                        }
                    },
                    'progress-sidebar': {
                        controller: 'ProgressCtrl',
                        templateUrl: '/templates/progress-sidebar.html'
                    }
                }
            });
    }])
    .config([
        'ItemCreationStepsProvider',
        function (ItemCreationStepsProvider) {
            ItemCreationStepsProvider.setSteps([
                {
                    isActive: true,
                    heading: 'General information',
                    text: 'moar dafuq information',
                    route: 'general',
                },
                {
                    isActive: false,
                    heading: 'Project file information',
                    text: 'moar dafuq project file information',
                    route: 'project-file',
                    requiredTargetItemTypes: ['project-file.template', 'project-file.preset']
                },
                {
                    isActive: false,
                    heading: 'File upload area',
                    text: 'upload your files',
                    route: 'upload-file',
                },
                {
                    isActive: false,
                    heading: 'Overview and submit',
                    text: 'moar dafuq overview with ultimate information',
                    route: 'overview',
                }                
            ]);
        }
    ])