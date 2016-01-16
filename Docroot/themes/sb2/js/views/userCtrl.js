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
    $scope.groups;
    $scope.permissions;
    $scope.ajax = {};

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


    $scope.editDep = function (dep, insertAndOpen) {
        dep = dep || {'stt': true};
        dep.insertAndOpen = insertAndOpen || false;
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

    $scope.submitDep = function ($event) {
        if (!$event.target.checkValidity()) {
            return;
        }
        var url = CONFIG.siteUrl + '/rest/department/' + $scope.editingDep.pk;
        $http.put(url, $scope.editingDep).then(function () {
            $scope.getDep($scope.depPk);
            $($scope.modalDep).modal('hide');

            if ($scope.editingDep.insertAndOpen)
                $timeout(function () {
                    $scope.editDep(null, true);
                }, 500);
        });
    };

    $scope.editUser = function (user, insertAndOpen) {
        user = user ? $.extend({}, user) : {
            'stt': true,
            'changePass': true,
            'groups': [],
            'permissions' : []
        };
        user.insertAndOpen = insertAndOpen || false;
        user.parentDep = $scope.department;
        $scope.editingUser = user;
        $($scope.modalUser).modal('show');

        $http.get(CONFIG.siteUrl + '/rest/group').then(function (resp) {
            $scope.groups = resp.data;
        });
        $http.get(CONFIG.siteUrl + '/rest/basePermission').then(function (resp) {
            $scope.permissions = [];
            for (var i in resp.data) {
                var group = {
                    'name': i,
                    'permissions': [],
                    'show': true
                };
                for (var j in resp.data[i]) {
                    group.permissions.push({
                        'name': j,
                        'value': resp.data[i][j]
                    });
                }
                $scope.permissions.push(group);
            }
        });
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

    $scope.pickUserDep = function () {
        $('[ng-department-picker]')[0].openModal({
            'selected': $scope.editingUser.parentDep ? $scope.editingUser.parentDep.pk : null,
            'submit': function (dep) {
                $apply(function () {
                    $scope.editingUser.parentDep = dep;
                });
            }
        });
    };
    $scope.$watchCollection('editingUser', function (newVal) {
        if (!newVal)
            return;
        newVal.depFk = newVal.parentDep ? newVal.parentDep.pk : 0;
        newVal.pk = newVal.pk || 0;
    });

    $scope.clearUserDep = function () {
        $scope.editingUser.parentDep = null;
    };

    $scope.submitUser = function ($event) {
        if (!$event.target.checkValidity()) {
            return;
        }
        if ($scope.editingUser.errcheckUniqueAccount) {
            $scope.userAccDom.focus();
            return;
        }

        var user = $scope.editingUser;
        user.passError = null;
        //check pass
        if (user.changePass && user.newPass && user.newPass.length < 6) {
            user.passError = 'Hãy nhập mật khẩu tối thiểu 6 ký tự.';
            $scope.newPassDom.focus();
            return;
        }
        if (user.changePass && user.newPass != user.rePass) {
            user.passError = 'Mật khẩu nhập lại không khớp.';
            $scope.rePassDom.focus();
            return;
        }


    };

    $scope.toggleGroup = function ($event) {
        var target = $event.target;
        if (target.checked && $scope.editingUser.groups.indexOf(target.value) == -1)
            $scope.editingUser.groups.push(target.value);
        else if (!target.checked) {
            var idx = $scope.editingUser.groups.indexOf(target.value);
            if (idx != -1)
                $scope.editingUser.groups.splice(idx, 1);
        }
    };

    $scope.togglePermission = function ($event) {
        var target = $event.target;
        if (target.checked && $scope.editingUser.permissions.indexOf(target.value) == -1)
            $scope.editingUser.permissions.push(target.value);
        else if (!target.checked) {
            var idx = $scope.editingUser.permissions.indexOf(target.value);
            if (idx != -1)
                $scope.editingUser.permissions.splice(idx, 1);
        }
    };

    $scope.$watch('editingUser.account', function (newVal) {
        if (!newVal)
            return;

        var url = CONFIG.siteUrl + '/rest/user/checkUniqueAccount';
        var data = {
            'pk': $scope.editingUser.pk,
            'account': $scope.editingUser.account
        };

        $scope.ajax.checkUniqueAccount = true;
        $scope.editingUser.errcheckUniqueAccount = false;

        $timeout(function () {
            $http.post(url, data).then(function (res) {
                $scope.ajax.checkUniqueAccount = false;
                $scope.editingUser.errcheckUniqueAccount = !res.data;
            });
        }, 500);

    });
});

