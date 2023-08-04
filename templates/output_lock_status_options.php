<?php

declare(strict_types=1);

?>

<h3>Lock User Account</h3>
<table class="form-table">
    <tr>
        <th><label for="account_status">Account status</label></th>
        <td>
            <select name="account_status" id="account_status">
                <?php // phpcs:disable ?>
                <option value="unlocked" <?php selected(!$isUserLocked) ?> >Unlocked</option>
                <option value="locked" <?php selected($isUserLocked) ?> >Locked</option>
            </select>
        </td>
    </tr>
</table>
