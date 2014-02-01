angular.module('edmShopItems')
    .config(['RestangularProvider', function (RestangularProvider) {
        RestangularProvider.setBaseUrl('/api');
    }])
    .config(['$stateProvider', '$urlRouterProvider', function ($stateProvider, $urlRouterProvider) {
        $urlRouterProvider.otherwise('/general');

        $stateProvider
            .state('root', {
                abstract: true,
                views: {
                    'progress-sidebar': {
                        controller: 'ProgressCtrl',
                        templateUrl: '/templates/progress-sidebar.html',
                    }
                }
            })
            .state('general', {
                parent: 'root',
                url: '/general',
                views: {
                    '@': {
                        controller: 'GeneralCtrl',
                        templateUrl: '/templates/general.html',
                        resolve: {
                            'ShopCategoriesSelectList': 'ShopCategoriesSelectList'
                        }
                    }
                }
            })
            .state('project-file', {
                parent: 'root',
                url: '/project-file',
                views: {
                    '@': {
                        controller: 'ProjectFileCtrl',
                        templateUrl: '/templates/project-file.html',
                        resolve: {
                            'MusicGenresSelectList': 'MusicGenresSelectList',
                            'MusicPluginsSelectList': 'MusicPluginsSelectList',
                            'MusicProgramsSelectList': 'MusicProgramsSelectList'
                        }
                    }
                }
            });
    }])
    .config([
        'ItemCreationStepsProvider',
        function (ItemCreationStepsProvider) {
            ItemCreationStepsProvider.setSteps([
                {
                    heading: 'General information',
                    text: 'moar dafuq information',
                    route: 'general',
                },
                {
                    heading: 'Project file information',
                    text: 'moar dafuq project file information',
                    route: 'project-file',
                    requiredTargetItemTypes: ['project-files.templates', 'project-files.presets']
                },
                {
                    heading: 'File upload area',
                    text: 'upload your files',
                    route: 'upload-file',
                },
                {
                    heading: 'Overview and submit',
                    text: 'moar dafuq overview with ultimate information',
                    route: 'overview',
                }                
            ]);
        }
    ])