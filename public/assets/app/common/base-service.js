angular.module('edmShopItems')
    .factory('BaseService', [
        '$timeout',
        function ($timeout) {
            return {
                // properties
                resolverFunctions: [],
                watchFunctions: [],
                // functions
                callResolverFunctions: function () {
                    this.callListOfFunctions(this.resolverFunctions);
                },
                callWatchFunctions: function () {
                    this.callListOfFunctions(this.watchFunctions);
                },
                callListOfFunctions: function (listOfFunctions) {
                    _.each(listOfFunctions, function (func) {
                        func();
                    });
                },
                initialize: function () {
                    this.callResolverFunctions();
                    $timeout(this.callWatchFunctions);
                }
            };
        }
    ]);