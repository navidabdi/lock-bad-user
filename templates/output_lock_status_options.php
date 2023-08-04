<h3>Lock User Account</h3>
<table class="form-table">
    <tr>
        <th><label for="account_status">Account status</label></th>
        <td>
            <select name="account_status" id="account_status">
                <option value="unlocked" <?php selected(!$locking_data) ?> >Unlocked</option>
                <option value="locked" <?php selected($locking_data) ?> >Locked</option>
            </select>
        </td>
    </tr>
</table>
