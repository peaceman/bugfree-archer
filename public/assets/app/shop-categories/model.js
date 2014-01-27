angular.module('edmShopItems')
    .factory('ShopCategories', [
        'Restangular', '$q',
        function (Restangular, $q) {
            var allShopCategories = Restangular.all('shop-categories');
            var deferredHierarchicalItemCategories = $q.defer();

            allShopCategories.getList().then(function (categories) {
                var rootNodes = _.filter(categories, {parent_id: null});
                var result = [];

                var processBranch = function processBranch(names, node) {
                    var localNames = _.clone(names);
                    localNames.push(node.name);

                    if (_.parseInt(node.can_contain_items)) {
                        result.push({
                            names: localNames,
                            node: node
                        })
                    }

                    var childNodes = _.filter(categories, {parent_id: node.id});
                    _.forEach(childNodes, _.wrap(localNames, processBranch));
                };

                _.forEach(rootNodes, _.wrap([], processBranch));
                deferredHierarchicalItemCategories.resolve(result);
            });

            return {
                all: allShopCategories,
                hierarchicalItemCategories: deferredHierarchicalItemCategories.promise
            };
        }
    ]);