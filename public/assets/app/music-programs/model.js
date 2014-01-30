angular.module('edmShopItems')
	.factory('MusicPrograms', [
		'Restangular',
		function (Restangular) {
			var allMusicPrograms = Restangular.all('music-programs');

			return {
				all: allMusicPrograms
			};
		}
	])
	.factory('MusicProgramsSelectList', [
		'$q', 'MusicPrograms',
		function ($q, MusicPrograms) {
			var deferred = $q.defer();

			MusicPrograms.all.getList().then(function (musicPrograms) {
				deferred.resolve(musicPrograms);
			});

			return deferred.promise;
		}
	]);