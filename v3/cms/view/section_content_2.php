<?php
if (!isset($ini))
{
    require_once'../../Ini.php';
    $ini = new Ini();
}
$logged_user = User::get_logged_user();
if ($logged_user == null)
{
    echo "<script>location.reload();</script>";
    die(Language::string(278));
}

$vals = $_POST['value'];
if (array_key_exists('oid', $_POST) && $_POST['oid'] != 0)
{
    $section = TestSection::from_mysql_id($_POST['oid']);
    $vals = $section->get_values();
}

// 0 - html
// 1 - params_count
// 2 - returns_count
// vars

$preview = Language::string(213);

$template = Template::from_mysql_id($vals[0]);
if ($vals[0] != 0 && $template != null)
{
    ?>
    <b><?= Language::string(106) ?>:</b>
    <div class="ui-widget-content ui-state-focus">
        <div>
            <table>
                <?php
                $preview.=" " . Language::string(214) . ":<hr/>" . $template->HTML;

                $inserts = $template->get_inserts();

                $j = 0;
                for ($i = 0; $i < count($inserts); $i++)
                {
                    $is_special = false;
                    if ($inserts[$i] == "TIME_LEFT") $is_special = true;

                    if (!$is_special)
                    {
                        $val = $inserts[$i];
                        if (isset($vals[3 + $j]) && $vals[3 + $j] != "")
                                $val = $vals[3 + $j];
                        $j++;
                    }
                    ?>
                    <tr>
                        <td><span class="spanIcon ui-icon ui-icon-help tooltip" title="<?= Language::string(215) ?>"></span></td>
                        <td><?= $inserts[$i] ?></td>
                        <td>
                            <?php
                            if (!$is_special)
                            {
                                ?>    
                                <?= Language::string(279) ?> <input type="text" class="controlValue<?= $_POST['counter'] ?>_params ui-widget-content ui-corner-all comboboxVars" value="<?= htmlspecialchars($val, ENT_QUOTES) ?>" />
                                <?php
                            }
                            else echo "&nbsp;";
                            ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td><span class="spanIcon ui-icon ui-icon-help tooltip" title="<?= Language::string(216) ?>"></span></td>
                    <td>TIME_LIMIT</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </div>
        <div class="notVisible">
            <?php
            for ($i = 0; $i < count($inserts); $i++)
            {
                ?>
                <input class="inputParameterVar" type="hidden" value="<?= $inserts[$i] ?>" />
                <?php
            }
            ?>
            <input class="inputParameterVar" type="hidden" value="TIME_LIMIT" />
        </div>
    </div>
    <br/>
    <?php
}
?>
<b>choose the HTML template:</b>
<table>
    <tr>
        <td>
            <span class="spanIcon ui-icon ui-icon-help tooltip" title="<?= htmlspecialchars($preview, ENT_QUOTES) ?>"></span>
        </td>
        <td>
            <select id="selectTemplate_<?= $_POST['counter'] ?>" class="fullWidth ui-widget-content ui-corner-all" onchange="Test.uiTemplatesChanged()">
                <option value="0">&lt;from CURRENT_TEMPLATE_ID&gt;</option>
                <?php
                $sql = $logged_user->mysql_list_rights_filter("Template", "`name` ASC");
                $z = mysql_query($sql);
                while ($r = mysql_fetch_array($z))
                {
                    $t = Template::from_mysql_id($r[0]);
                    ?>
                    <option value="<?= $t->id ?>" <?= ($vals[0] == $t->id ? "selected" : "") ?>><?= $t->name ?> ( <?= $t->get_system_data() ?> )</option>
                <?php } ?>
            </select>
        </td>
    </tr>
</table>

<?php
if ($vals[0] != 0 && $template != null)
{
    ?>

    <b><?= Language::string(113) ?>:</b>
    <div class="ui-widget-content ui-state-focus">
        <div>
            <table>
                <?php
                $outputs = $template->get_outputs();

                for ($i = 0; $i < count($outputs); $i++)
                {
                    $ret = $outputs[$i]["name"];
                    if (isset($vals[$vals[1] + 3 + $i * 3]) && $vals[$vals[1] + 3 + $i * 3] != "")
                            $ret = $vals[$vals[1] + 3 + $i * 3];
                    $vis = 2;
                    if (isset($vals[$vals[1] + 3 + $i * 3 + 1]))
                            $vis = $vals[$vals[1] + 3 + $i * 3 + 1];
                    $type = 0;
                    if (isset($vals[$vals[1] + 3 + $i * 3 + 2]))
                            $type = $vals[$vals[1] + 3 + $i * 3 + 2];
                    ?>
                    <tr>
                        <td><span class="spanIcon ui-icon ui-icon-help tooltip" title="<?= Language::string(217) ?>: <b><?= $outputs[$i]["type"] ?></b>"></span></td>
                        <td><?= $outputs[$i]["name"] ?></td>
                        <td><?= Language::string(279) ?> <input onchange="Test.uiSetVarNameChanged($(this))" type="text" class="ui-state-focus comboboxSetVars comboboxVars controlValue<?= $_POST['counter'] ?>_rets ui-widget-content ui-corner-all" value="<?= htmlspecialchars($ret, ENT_QUOTES) ?>" /></td>
                        <td>
                            <b><?= Language::string(277) ?></b> <select class="controlValue<?= $_POST['counter'] ?>_rets ui-widget-content ui-corner-all">
                                <option value="0" <?= ($vis == 0 ? "selected" : "") ?>><?= Language::string(275) ?></option>
                                <option value="1" <?= ($vis == 1 ? "selected" : "") ?>><?= Language::string(18) ?></option>
                                <option value="2" <?= ($vis == 2 ? "selected" : "") ?>><?= Language::string(276) ?></option>
                            </select>
                        </td>
                        <td>
                            <b><?= Language::string(279) ?></b> <select class="controlValue<?= $_POST['counter'] ?>_rets ui-widget-content ui-corner-all">
                                <option value="0" <?= ($type == 0 ? "selected" : "") ?>><?= Language::string(280) ?></option>
                                <option value="1" <?= ($type == 1 ? "selected" : "") ?>><?= Language::string(281) ?></option>
                                <option value="2" <?= ($type == 2 ? "selected" : "") ?>><?= Language::string(282) ?></option>
                            </select>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td><span class="spanIcon ui-icon ui-icon-help tooltip" title="<?= Language::string(283) ?>"></span></td>
                    <td>CURRENT_TEMPLATE_ID</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td><span class="spanIcon ui-icon ui-icon-help tooltip" title="<?= Language::string(283) ?>"></span></td>
                    <td>LAST_PRESSED_BUTTON_NAME</td>
                    <td>&nbsp;</td>
                </tr>
            </table> 
        </div>
        <div class="notVisible">
            <?php
            for ($i = 0; $i < count($outputs); $i++)
            {
                ?>
                <input class="inputReturnVar" type="hidden" value="<?= $outputs[$i]['name'] ?>" />
                <?php
            }
            ?>
            <input class="inputReturnVar" type="hidden" value="CURRENT_TEMPLATE_ID" />
            <input class="inputReturnVar" type="hidden" value="LAST_PRESSED_BUTTON_NAME" />
        </div>
    </div>
    <?php
}
?>