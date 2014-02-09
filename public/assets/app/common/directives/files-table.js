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
					scope.isCollapsed = _.isUndefined(scope.defaultCollapse) ? false : scope.defaultCollapse;
				}
			};
		}
	])
	.directive('imagePreview', [
		function () {
			return {
				restrict: 'E',
				scope: {
					file: '='
				},
				replace: true,
				template: '<img ng-if="isImageFile(file)" src="{{ file.download_url }}" class="img-rounded" style="width: 100%;">',
				link: function (scope, element, attrs) {
					var imageMimeTypePattern = new RegExp('^image/');
					scope.isImageFile = function isImageFile(file) {
						return imageMimeTypePattern.test(file.mime_type);
					};
				}
			}
		}
	]);
