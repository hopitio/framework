sb2.controller('userCtrl', function ($scope, $apply, $timeout, $http) {
    $scope.showFilter = getCookie('showFilter', false);
    $scope.depPk;
    $scope.department = null;
    $scope.ajax = {};
    $scope.editingDep;
    $scope.editingUser;
    $scope.modalDep;
    $scope.checkedUsers = {};
    $scope.checkedDeps = {};
    $scope.departmentPicker;

    $(window).on('hashchange', function () {
        $apply(function () {
            $scope.depPk = window.location.hash.replace('#', '').replace('/', '') || 0;
            $scope.getDep($scope.depPk);
        });
    }).trigger('hashchange');

    $scope.getDep = function (depPk) {
        if ($scope.ajax.load)
            $scope.ajax.load.abort();

        $scope.ajax.load = $.ajax({
            'url': CONFIG.siteUrl + '/rest/department/' + depPk,
            'cache': false,
            'data': {'users': 1, 'departments': 1, 'ancestors': 1},
            'dataType': 'json'
        }).done(function (res) {
            $apply(function () {
                $scope.department = res;
            });
        }).always(function () {
            $scope.ajax.load = false;
        });
    };

    $scope.toggleFilter = function () {
        $scope.showFilter = !$scope.showFilter;
    };

    $scope.$watch('showFilter', function (newVal) {
        if (typeof newVal === 'undefined')
            return;

        setCookie('showFilter', newVal);
    });

    $scope.$watch('depPk', function (newVal) {
        if (typeof newVal === 'undefined')
            return;


    });

    $scope.goUp = function () {
        if (!$scope.department.ancestors.length) {
            window.location.hash = '#/';
            return;
        }
        var parentDep = $scope.department.ancestors[$scope.department.ancestors.length - 1];
        window.location.hash = '#/' + parentDep.pk;
    };


    $scope.editDep = function (dep) {
        dep = dep || {'stt': true};
        $scope.editingDep = $.extend({}, dep);
        $scope.editingDep.parentDep = $scope.department;
        $($scope.modalDep).modal('show');
    };

    $scope.anythingChecked = function () {
        for (var i in $scope.checkedDeps)
            if ($scope.checkedDeps[i])
                return true;
        for (var i in $scope.checkedUsers)
            if ($scope.checkedUsers[i])
                return true;
        return false;
    };

    $scope.pickEditDep = function () {
        $('[ng-department-picker]')[0].openModal({
            'selected': $scope.editingDep.parentDep ? $scope.editingDep.parentDep.pk : null,
            'not': $scope.editingDep.pk,
            'submit': function (dep) {
                $apply(function () {
                    $scope.editingDep.parentDep = dep;
                });
            }
        });
    };

    $scope.clearParentDep = function () {
        $scope.editingDep.parentDep = null;
        $scope.editingDep.depFk = null;
    };

    $scope.$watchCollection('editingDep', function (newVal) {
        if (!newVal)
            return;
        if (newVal.parentDep)
            newVal.depFk = newVal.parentDep.pk;
        newVal.pk = newVal.pk || 0;
    });

    $scope.submitDep = function () {
        var url = CONFIG.siteUrl + '/rest/department/' + $scope.editingDep.pk;
        $http.put(url, $scope.editingDep).then(function () {
            $scope.getDep($scope.depPk);
            $($scope.modalDep).modal('hide');
        });
    };

    $scope.editUser = function (user) {
        user = $.extend({}, user) || {
            'stt': true
        };
        $scope.editingUser = user;
        $($scope.modalUser).modal('show');
    };

    $scope.togglePassword = function () {
        if ($scope.editingUser.changePass) {
            $scope.editingUser.changePass = false;
            $scope.editingUser.newPass = null;
            $scope.editingUser.rePass = null;
        } else {
            $scope.editingUser.changePass = true;
        }
        $timeout(function () {
            $(window).trigger('resize');
        });
    };
});

