angular.module('edmShopItems')
    .factory('BaseService', [
        function () {
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
                        // var boundFunc = _.bind(func, this);
                        // boundFunc();
                        func();
                    });
                },
                initialize: function () {
                    this.callResolverFunctions();
                    this.callWatchFunctions();
                }
            };
        }
    ]);