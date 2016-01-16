<fieldset ng-repeat="group in permissions">
    <legend>{{group.name}}</legend>

    <table class='table-bordered table table-striped table-hover'>
        <tr ng-repeat="pem in group.permissions">
            <td>
                <label class="check" value="pem.name">
                    <input type="checkbox" value="{{pem.name}}" ng-checked="editingUser.permissions.indexOf(pem.name) != -1" ng-click="togglePermission($event)"/>
                    <before></before>
                    <after></after>&nbsp;
                    {{pem.value}}
                </label>
            </td>
        </tr>

    </table>
</fieldset>