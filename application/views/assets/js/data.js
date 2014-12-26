app.factory('Data', ['$injector', 'toaster',
    function ($injector, toaster) { // This service connects to our REST API

        var $http = $injector.get('$http');
        var serviceBase = 'v1/';

        var obj = {};
        obj.toast = function (data) {
            toaster.pop(data.status, "", data.message, 10000, 'trustedHtml');
        }
        obj.getSession = function() {
            return $http.get(serviceBase + 'session', {
                // This makes it so that this request doesn't send the JWT
                skipAuthorization: true
            }).then(function (results) {
                return results.data;
            });
        };
        obj.get = function (q) {
            return $http.get(serviceBase + q).then(function (results) {
                return results.data;
            });
        };
        obj.post = function (q, object) {
            return $http.post(serviceBase + q, object).then(function (results) {
                return results.data;
            });
        };
        obj.put = function (q, object) {
            return $http.put(serviceBase + q, object).then(function (results) {
                return results.data;
            });
        };
        obj.delete = function (q) {
            return $http.delete(serviceBase + q).then(function (results) {
                return results.data;
            });
        };

        return obj;
}]);
