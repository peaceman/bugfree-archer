angular.module('edmShopItems')
    .factory('Utils', ['Restangular', function (Restangular) {
        var buildFieldValidationExpression = function buildFieldValidationExpression(formName, fieldName) {
            var fieldExpression = formName + '.' + fieldName;
            var invalidExpression = fieldExpression + '.$invalid';
            var dirtyExpression = fieldExpression + '.$dirty';
            return invalidExpression + ' && ' + dirtyExpression;
        };

        var restangularize = function restangularize(parent, route, models) {
            if (!_.isArray(models)) {
                return Restangular.restangularizeElement(parent, models, route);
            }

            var convertedModels = _.map(models, function (model) {
                return Restangular.restangularizeElement(parent, model, route);
            });

            return Restangular.restangularizeCollection(parent, convertedModels, route);
        };

        var setRelationFields = function setRelationFields(model) {
            if (!_.has(model, 'embeddableRelations') || _.isEmpty(model.embeddableRelations)) {
                return;
            }

            _.each(model.embeddableRelations, function (relation) {
                var relatedFieldName = 'related_' + relation.name;
                if (_.isUndefined(model[relatedFieldName]))
                    return;

                var includeParent = true;
                if (_.has(relation, 'parent')) {
                    includeParent = relation.parent;
                }

                model[relation.fieldName] = restangularize(includeParent ? model : null, relation.route, model[relatedFieldName]);
                delete model[relatedFieldName];
            });
        };

        var assignServerSideValidationErrorsFromErrorEvent = function assignServerSideValidationErrorsFromErrorEvent(errorEvent, $scope) {
            if (errorEvent.data.hasOwnProperty('errors') && errorEvent.data['errors']) {
                var errors = errorEvent.data['errors'];

                for (var fieldName in errors) {
                    if (errors[fieldName] && errors[fieldName].length != 0) {
                        $scope.serverSideValidationErrors[fieldName] = errors[fieldName][0];
                    }
                }
            }
        };

        var createModelChangedEvent = function createModelChangedEvent(action, models) {
            if (!_.isArray(models)) {
                models = [models];
            }

            return {action: action, models: models};
        };

        var isStoredModel = function isStoredModel(model) {
            return _.isObject(model) && _.has(model, 'id');
        };

        var isPromise = function isPromise(value) {
            return _.contains(_.functions(value), 'then');
        };

        var executeWithValue = function executeWithValue(value, callback) {
            if (isPromise(value)) {
                return value.then(callback);
            } else {
                return callback(value);
            }
        };

        return {
            buildFieldValidationExpression: buildFieldValidationExpression,
            restangularize: restangularize,
            setRelationFields: setRelationFields,
            assignServerSideValidationErrorsFromErrorEvent: assignServerSideValidationErrorsFromErrorEvent,
            createModelChangedEvent: createModelChangedEvent,
            isStoredModel: isStoredModel,
            executeWithValue: executeWithValue
        };
    }]);