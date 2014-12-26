// Declare app level module which depends on filters, and services
var app = angular.module('myApp', ['ngRoute', 'ngAnimate', 'angular-jwt', 'toaster']);

app.config(['$httpProvider', '$routeProvider', 'jwtInterceptorProvider', function Config($httpProvider, $routeProvider, jwtInterceptorProvider) {
    jwtInterceptorProvider.tokenGetter = ['Data', 'jwtHelper', function(Data, jwtHelper) {
        var idToken = localStorage.getItem('id_token');
        if ( ! idToken || jwtHelper.isTokenExpired(idToken) ) {
            // This is a promise of a JWT id_token
            return Data.getSession().then(function(response) {
                var id_token = response.id_token;
                localStorage.setItem('id_token', id_token);
                return id_token;
            });
        } else {
            return idToken;
        }
    }];

    $httpProvider.interceptors.push('jwtInterceptor');
    
    $httpProvider.interceptors.push(['$q', '$injector',
        function ( $q, $injector ) {
            return {
                response: function(response) {
                    if ( 'object' === typeof response.data && response.data.id_token )
                    {
                        localStorage.setItem('id_token', response.data.id_token);
                    }
                    return response;
                },
                responseError: function(rejection) {
                    if ( 401 === rejection.status ) {
                        var Data = $injector.get('Data');
                        return Data.getSession().then(function(response) {
                            var id_token = response.id_token;
                            localStorage.setItem('id_token', id_token);
                            var $http = $injector.get('$http');
                            rejection.config.headers['Authorization'] = 'Bearer ' + id_token;
                            return $http(rejection.config);
                        });
                    }
                    return $q.reject(rejection);                    
                }
            };
        }]);
  
    $routeProvider
        .when('/login', {
            title: 'Login',
            templateUrl: 'assets/html/login.html',
            controller: 'authCtrl'
        })
        .when('/logout', {
            title: 'Logout',
            templateUrl: 'assets/html/login.html',
            controller: 'logoutCtrl'
        })
        .when('/signup', {
            title: 'Signup',
            templateUrl: 'assets/html/signup.html',
            controller: 'authCtrl'
        })
        .when('/dashboard', {
            title: 'Dashboard',
            templateUrl: 'assets/html/dashboard.html',
            controller: 'authCtrl'
        })
        .when('/', {
            title: 'Login',
            templateUrl: 'assets/html/login.html',
            controller: 'authCtrl',
            role: '0'
        })
        .otherwise({
            redirectTo: '/login'
        });
  
}]);

app.run(['$rootScope', '$location', 'Data', function Run($rootScope, $location, Data) {
        $rootScope.$on("$routeChangeStart", function (event, next, current) {
            $rootScope.authenticated = false;
//            Data.get('session').then(function (results) {
//                if (results.uid) {
//                    $rootScope.authenticated = true;
//                    $rootScope.uid = results.uid;
//                    $rootScope.name = results.name;
//                    $rootScope.email = results.email;
//                } else {
//                    var nextUrl = next.$$route.originalPath;
//                    if (nextUrl == '/signup' || nextUrl == '/login') {
//
//                    } else {
//                        $location.path("/login");
//                    }
//                }
//            });
        });
    }]);
