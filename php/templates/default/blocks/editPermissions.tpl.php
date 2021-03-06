<?php
/**
 * Template for editing group permissions
 *
 * Zoph is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Zoph is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with Zoph; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @author Jeroen Roos
 * @package ZophTemplates
 */

use template\template;

if (!ZOPH) {
    die("Illegal call");
}
?>
<br>
<h3><?= $tpl_edit == "album" ? translate("Group permissions") : translate("Album permissions") ?></h3>
<?= translate("Granting access to an album will also grant access to that album's " .
    "ancestors if required. Granting access to all albums will not overwrite " .
    "previously granted permissions."); ?>
<?php if ($tpl_watermark): ?>
    <br>
    <?= translate("A photo will be watermarked if the photo level is " .
        "higher than the watermark level.") ?>
<?php endif ?>
<form action="permissions.php" method="post" class="grouppermissions">
    <table class="permissions">
        <col class="col1"><col class="col2"><col class="col3"><col class="col4">
        <tr>
            <th colspan="2"><?= translate("name") ?></th>
            <th><?= translate("access level") ?></th>
            <?php if ($tpl_watermark): ?>
                <th><?= translate("watermark level") ?></th>
            <?php endif ?>
            <th><?php echo translate("writable"); ?></th>
            <?php if ($tpl_edit == "album"): ?>
                <th><?php echo translate("grant to subalbums"); ?></th>
            <?php endif ?>
        </tr>
        <tr>
            <td>
                <input type="checkbox" name="_access_level_all_checkbox" value="1">
            </td>
            <td>
                <input type="hidden" name="<?= $tpl_fixed ?>_id" value="<?= $tpl_id ?>">
                <input type="hidden" name="_action" value="update<?= $tpl_edit ?>s">
                <?php if ($tpl_edit == "album"): ?>
                    <?= translate("Grant access to all existing albums:") ?>
                <?php else: ?>
                    <?= translate("Grant access to all existing groups:") ?>
                <?php endif ?>
            </td>
            <td>
                <?= $tpl_accessLevelAll ?>
            </td>
            <?php if ($tpl_watermark): ?>
                <td>
                    <?= $tpl_wmLevelAll ?>
                </td>
            <?php endif ?>
            <td>
                <?php echo template::createYesNoPulldown("writable_all", "0") ?>
            </td>
        </tr>
        <tr>
            <td>
            </td>
            <td>
                <?php if ($tpl_edit == "album"): ?>
                    <input type="hidden" name="group_id_new" value="<?= $tpl_id ?>">
                    <?= template::createPulldown("album_id_new", "", album::getTreeSelectArray()) ?>
                <?php else: ?>
                    <input type="hidden" name="album_id_new" value="<?= $tpl_id ?>">
                    <?= template::createPulldown("group_id_new", "", group::getSelectArray()) ?>
                <?php endif ?>
            </td>
            <td>
                <?= $tpl_accessLevelNew?>
            </td>
            <?php if ($tpl_watermark): ?>
                <td>
                    <?= $tpl_wmLevelNew ?>
                </td>
            <?php endif ?>
            <td>
                <?php echo template::createYesNoPulldown("writable_new", "0") ?>
            </td>
            <?php if ($tpl_edit == "album"): ?>
                <td>
                    <?php echo template::createYesNoPulldown("subalbums_new", "0") ?>
                </td>
            <?php endif ?>
        </tr>
        <tr>
            <td colspan="4" class="permremove">
                <?php echo translate("remove") ?>
            </td>
        </tr>
        <?php foreach ($tpl_permissions as $perm): ?>
            <tr>
                <td>
                    <input type="checkbox" name="_remove_permission_<?= $tpl_edit ?>__<?= $perm->get($tpl_edit_id) ?>" value="1">
                </td>
                <td>
                    <?= $tpl_edit == "album" ? $perm->getAlbumName() : $perm->getGroupName() ?>
                </td>
                <td>
                    <input type="hidden" name="album_id__<?= $perm->get($tpl_edit_id) ?>" value="<?= $perm->get("album_id") ?>">
                    <input type="hidden" name="group_id__<?= $perm->get($tpl_edit_id) ?>" value="<?= $perm->get("group_id") ?>">
                    <?= template::createInput("access_level__" . $perm->get($tpl_edit_id), $perm->get("access_level"), 4) ?>
                </td>
                <?php if ($tpl_watermark): ?>
                    <td>
                        <?= template::createInput("watermark_level__" . $perm->get($tpl_edit_id), $perm->get("watermark_level"), 4) ?>
                     </td>
                <?php endif ?>
                <td>
                    <?= template::createYesNoPulldown("writable__" . $perm->get($tpl_edit_id), $perm->get("writable")) ?>
                </td>
                <?php if ($tpl_edit == "album"): ?>
                    <td>
                        <?= template::createYesNoPulldown("subalbums__" . $perm->get($tpl_edit_id), $perm->get("subalbums")) ?>
                    </td>
                <?php endif ?>
            </tr>
        <?php endforeach ?>
    </table>
    <input type="submit" value="<?= translate("update", 0) ?>">
</form>
