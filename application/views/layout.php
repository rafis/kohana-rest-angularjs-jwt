<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Kohana + REST + AngularJS + JSON Web Token</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="shortcut icon" href="/favicon.ico" />

	<!-- The Styles -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="//cdnjs.cloudflare.com/ajax/libs/angularjs-toaster/0.4.4/toaster.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body ng-cloak="">

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="row">
          <div class="navbar-header col-md-8">
            <button type="button" class="navbar-toggle" toggle="collapse" target=".navbar-ex1-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" rel="home" title="AngularJS Authentication App">AngularJS Authentication App</a>
          </div>
          <div class="navbar-header col-md-2">
            <a class="navbar-brand" rel="home" title="AngularJS Authentication Tutorial" href="javascript:void(0)">Tutorial</a>
          </div>
           <div class="navbar-header col-md-2">
            <a class="navbar-brand" rel="home" title="Download" href="javascript:void(0)">Download</a>
          </div>
        </div>
      </div>
    </div>
    <div >
      <div class="container" style="margin-top: 20px;">
        <div data-ng-view="" id="ng-view" class="slide-animation"></div>
      </div>
    </body>
    <toaster-container toaster-options="{'time-out': 3000}"></toaster-container>
    
	<!-- The Scripts -->
    <script>
        // include a third-party async loader library
        /*!
          * $script.js JS loader & dependency manager
          * https://github.com/ded/script.js
          * (c) Dustin Diaz 2014 | License MIT
          */
        (function(e,t){typeof module!="undefined"&&module.exports?module.exports=t():typeof define=="function"&&define.amd?define(t):this[e]=t()})("$script",function(){function p(e,t){for(var n=0,i=e.length;n<i;++n)if(!t(e[n]))return r;return 1}function d(e,t){p(e,function(e){return!t(e)})}function v(e,t,n){function g(e){return e.call?e():u[e]}function y(){if(!--h){u[o]=1,s&&s();for(var e in f)p(e.split("|"),g)&&!d(f[e],g)&&(f[e]=[])}}e=e[i]?e:[e];var r=t&&t.call,s=r?t:n,o=r?e.join(""):t,h=e.length;return setTimeout(function(){d(e,function t(e,n){if(e===null)return y();e=!n&&e.indexOf(".js")===-1&&!/^https?:\/\//.test(e)&&c?c+e+".js":e;if(l[e])return o&&(a[o]=1),l[e]==2?y():setTimeout(function(){t(e,!0)},0);l[e]=1,o&&(a[o]=1),m(e,y)})},0),v}function m(n,r){var i=e.createElement("script"),u;i.onload=i.onerror=i[o]=function(){if(i[s]&&!/^c|loade/.test(i[s])||u)return;i.onload=i[o]=null,u=1,l[n]=2,r()},i.async=1,i.src=h?n+(n.indexOf("?")===-1?"?":"&")+h:n,t.insertBefore(i,t.lastChild)}var e=document,t=e.getElementsByTagName("head")[0],n="string",r=!1,i="push",s="readyState",o="onreadystatechange",u={},a={},f={},l={},c,h;return v.get=m,v.order=function(e,t,n){(function r(i){i=e.shift(),e.length?v(i,r):v(i,t,n)})()},v.path=function(e){c=e},v.urlArgs=function(e){h=e},v.ready=function(e,t,n){e=e[i]?e:[e];var r=[];return!d(e,function(e){u[e]||r[i](e)})&&p(e,function(e){return u[e]})?t():!function(e){f[e]=f[e]||[],f[e][i](t),n&&n(r)}(e.join("|")),v},v.done=function(e){v([null],e)},v})

        // load all of the dependencies asynchronously.
        $script([
            '//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js',
            '//ajax.googleapis.com/ajax/libs/angularjs/1.3.8/angular.min.js',
        ], function() {
            $script([
                '//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js',
                '//ajax.googleapis.com/ajax/libs/angularjs/1.3.8/angular-route.min.js',
                '//ajax.googleapis.com/ajax/libs/angularjs/1.3.8/angular-cookies.min.js',
                '//ajax.googleapis.com/ajax/libs/angularjs/1.3.8/angular-sanitize.min.js',
                '//ajax.googleapis.com/ajax/libs/angularjs/1.3.8/angular-animate.min.js',
                '//cdnjs.cloudflare.com/ajax/libs/angularjs-toaster/0.4.4/toaster.min.js',
                '/assets/js/app.js',
            ], function() {
                // when all is done, execute bootstrap angular application
                angular.bootstrap(document, ['myApp'], {
                    strictDi: true
                });
            });
        });
    </script>
</body>
</html>