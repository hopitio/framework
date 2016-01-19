sb2.controller('groupCtrl', function ($scope, $timeout, $apply) {
    $scope.groups = [];
    $scope.ajax = {};
    $scope.checked = {};
    $scope.modalEdit;
    $scope.editing;

    $scope.getChecked = function () {
        var checked = [];
        for (var i in $scope.checked)
            if ($scope.checked[i])
                checked.push(i);
        return checked;
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

    $scope.edit = function (group) {
        group = group || {'stt': true};
        $scope.editing = $.extend({}, group);

        $timeout(function () {
            $($scope.modalEdit).modal('show');
        });
    };
});

