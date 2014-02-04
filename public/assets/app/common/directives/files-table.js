angular.module('edmShopItems')
	.directive('filesTable', [
		function () {
			var ensureIsSelectedAttributeExistsOnAllObjects = function ensureIsSelectedAttributeExistsOnAllObjects(objects) {
				_.each(objects, function (object) {
					if (_.has(object, 'isSelected')) return;
					object.isSelected = false;
				});
			};			

			return {
				restrict: 'E',
				scope: {
					files: '=',
					panelTitle: '@',
					defaultCollapse: '='
				},
				replace: true,
				templateUrl: '/templates/files-table.html',
				link: function (scope, element, attrs) {
					ensureIsSelectedAttributeExistsOnAllObjects(scope.files);
					scope.toggleFileSelection = scope.$parent.toggleFileSelection;
					console.log(scope.defaultCollapse);
					scope.isCollapsed = _.isUndefined(scope.defaultCollapse) ? false : scope.defaultCollapse;
					scope.search = {};
				}
			};
		}
	]);