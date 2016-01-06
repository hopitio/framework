(function () {
    var module = angular.module('RestSdk', []);
    var siteUrl = CONFIG.siteUrl;

    function Promise() {
        this.callbacks = {
            'done': new Function(),
            'fail': new Function(),
            'always': new Function()
        };

        this.done = function (fn) {
            this.callbacks.done = fn;
            return this;
        };

        this.fail = function (fn) {
            this.callbacks.fail = fn;
            return this;
        };

        this.always = function (fn) {
            this.callbacks.always = fn;
            return this;
        };
    }

    module.factory('$userSvc', ['$rootScope', function ($rootScope) {
            function ngApply(fn) {
                setTimeout(function () {
                    $rootScope.$apply(fn);
                });
            }

            var exports = {};

            exports.getDep = function (id) {
                var promise = new Promise();

                $.ajax({
                    'type': 'GET',
                    'url': siteUrl + '/rest/department/' + id
                }).done(function (data) {
                    ngApply(function () {
                        promise.done();
                    });
                });

                return promise;
            };
        }]);
})();

