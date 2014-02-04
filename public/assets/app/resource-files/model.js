angular.module('edmShopItems')
	.factory('ResourceFiles', [
		'Restangular', '$q', 
		function (Restangular, $q) {
			var allResourceFiles = Restangular.all('resource-files');

			return {
				all: allResourceFiles
			};
		}
	]);