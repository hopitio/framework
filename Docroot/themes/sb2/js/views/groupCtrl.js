sb2.controller('groupCtrl', function ($scope, $timeout, $apply, $http) {
    $scope.groups = [];
    $scope.ajax = {};
    $scope.checked = {};
    $scope.modalEdit;
    $scope.editing;
    $scope.tab = 0;

    $scope.getChecked = function () {
        var checked = [];
        for (var i in $scope.checked)
            if ($scope.checked[i])
                checked.push(i);
        return checked;
    };

    $scope.setTab = function (idx) {
        $scope.tab = idx;
    };

    $scope.getGroups = function () {
        if ($scope.ajax.get)
            $scope.ajax.get.abort();

        $scope.ajax.get = $.ajax({
            url: CONFIG.siteUrl + '/rest/group',
            dataType: 'json'
        }).done(function (resp) {
            $apply(function () {
                $scope.groups = resp;
            });
        });
    };
    $scope.getGroups();

    $scope.getCheckedUsers = function () {
        var arr = [];
        if ($scope.editing && $scope.editing.checked)
            for (var i in $scope.editing.checked)
                if ($scope.editing.checked[i])
                    arr.push(i);
        return arr;
    };

    $scope.edit = function (group) {
        group = group || {'pk': 0, 'stt': true};
        $scope.editing = $.extend({}, group);
        $scope.editing.checked = {};
        $scope.ajax = {};
        $scope.tab = 0;

        $.ajax({
            'url': CONFIG.siteUrl + '/rest/group/' + group.pk + '/user',
            'dataType': 'json'
        }).done(function (resp) {
            $apply(function () {
                $scope.editing.users = resp;
            });
        });

        $timeout(function () {
            $($scope.modalEdit).modal('show');
        });
    };

    $scope.pickUser = function () {
        var notUser = [];
        for (var i in $scope.editing.users)
            notUser.push($scope.editing.users[i].pk);

        $('[ng-user-picker]')[0].openModal({
            notGroup: [$scope.editing.pk],
            notUser: notUser,
            submit: function (users) {
                $apply(function () {
                    $scope.editing.users = $scope.editing.users.concat(users);
                    console.log($scope.editing.users);
                });
            }});
    };

    $scope.removeUser = function () {
        for (var i in $scope.editing.users)
            if ($scope.editing.checked[$scope.editing.users[i].pk])
                $scope.editing.users.splice(i, 1);
        $scope.editing.checked = {};
    };

    $scope.save = function () {
        var group = $.extend({}, $scope.editing);
        group.users = [];
        for (var i in $scope.editing.users)
            group.users.push($scope.editing.users[i].pk);

        $scope.ajax.save = true;

        $http.put(CONFIG.siteUrl + '/rest/group/' + group.pk, group).then(function () {
            $scope.ajax.save = null;
            $($scope.modalEdit).modal('hide');
            $scope.getGroups();
        });
    };
});
