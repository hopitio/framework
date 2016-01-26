sb2.directive('ngUserPicker', function ($apply) {
    function link(scope, elem) {
        scope.currentDep = 0;
        scope.users = [];
        scope.deps = [];
        scope.ajax = {};

        elem.openModel = function (config) {
            var not = config.not || [];
            scope.currentDep = config.department || 0;
            scope.onSubmit = config.onSubmit;
            scope.onCancel = config.onCancel;
        };

        scope.$watch('currentDep', function (newVal) {
            if (typeof newVal == 'undefined')
                return;

            if (scope.ajax.loadDep)
                scope.ajax.loadDep.abort();

            scope.ajax.loadDep = $.ajax({
                'url': CONFIG.siteUrl + '/rest/department/' + scope.currentDep,
                'dataType': 'json'
            }).done(function (resp) {
                scope.ajax.loadDep = null;
                $apply(function () {
                    scope.users = resp.users;
                    scope.deps = resp.deps;
                });
            });
        });
    }

    return {
        'scope': {
        },
        'link': link,
        'templateUrl': CONFIG.siteUrl + '/themes/sb2/components/UserPicker/UserPicker.php'
    };
});