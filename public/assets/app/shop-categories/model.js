angular.module('edmShopItems')
    .factory('ShopCategories', [
        'Restangular', '$q',
        function (Restangular, $q) {
            var allShopCategories = Restangular.all('shop-categories');
            var deferredHierarchicalItemCategories = $q.defer();

            allShopCategories.getList().then(function (categories) {
                var rootNodes = _.filter(categories, {parent_id: null});
                var result = [];

                var processBranch = function processBranch(ancestorNodes, node) {
                    var localAncestors = _.clone(ancestorNodes);
                    localAncestors.push(node);

                    if (_.parseInt(node.can_contain_items)) {
                        result.push({
                            names: _.pluck(localAncestors, 'name'),
                            slugs: _.pluck(localAncestors, 'slug'),
                            node: node
                        })
                    }

                    var childNodes = _.filter(categories, {parent_id: node.id});
                    _.forEach(childNodes, _.wrap(localAncestors, processBranch));
                };

                _.forEach(rootNodes, _.wrap([], processBranch));
                deferredHierarchicalItemCategories.resolve(result);
            });

            return {
                all: allShopCategories,
                hierarchicalItemCategories: deferredHierarchicalItemCategories.promise
            };
        }
    ])
    .factory('ShopCategoriesSelectList', [
        '$q', 'ShopCategories',
        function ($q, ShopCategories) {
            var deferred = $q.defer();

            ShopCategories.hierarchicalItemCategories.then(function (shopCategories) {
                deferred.resolve(_.map(shopCategories, function (shopCategory) {
                    return {
                        id: shopCategory.node.id,
                        name: shopCategory.names.join(' -> '),
                        targetItemType: shopCategory.slugs.join('.')
                    };
                }));
            });

            return deferred.promise;
        }
    ]);