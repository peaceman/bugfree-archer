angular.module('edmShopItems')
	.factory('MusicPlugins', [
		'Restangular',
		function (Restangular) {
			var allMusicPlugins = Restangular.all('music-plugins');

			return {
				all: allMusicPlugins
			};
		}
	])
	.factory('MusicPluginsSelectList', [
		'$q', 'MusicPlugins',
		function ($q, MusicPlugins) {
			var deferred = $q.defer();

			MusicPlugins.all.getList().then(function (musicPlugins) {
				deferred.resolve(musicPlugins);
			});

			return deferred.promise;
		}
	]);