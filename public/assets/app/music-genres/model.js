angular.module('edmShopItems')
	.factory('MusicGenres', [
		'Restangular',
		function (Restangular) {
			var allMusicGenres = Restangular.all('music-genres');

			return {
				all: allMusicGenres
			};
		}
	])
	.factory('MusicGenresSelectList', [
		'$q', 'MusicGenres',
		function ($q, MusicGenres) {
			var deferred = $q.defer();

			MusicGenres.all.getList().then(function (musicGenres) {
				deferred.resolve(musicGenres);
			});

			return deferred.promise;
		}
	]);