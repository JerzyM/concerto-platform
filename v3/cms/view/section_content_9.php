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

//$vals[0] - csid

$vals = $_POST['value'];
$section = null;
if (array_key_exists('oid', $_POST) && $_POST['oid'] != 0)
{
    $section = TestSection::from_mysql_id($_POST['oid']);
    $vals = $section->get_values();
}
$section = CustomSection::from_mysql_id($vals[0]);
$parameters = $section->get_parameter_CustomSectionVariables();
$returns = $section->get_return_CustomSectionVariables();
?>

<div class="ui-widget-header" align="center">
    <table>
        <tr>
            <td><span class="spanIcon ui-icon ui-icon-help tooltip" title="<?= htmlspecialchars($section->description, ENT_QUOTES) ?>"></span></td>
            <td><?= $section->name . " ( " . $section->get_system_data() . " )" ?></td>
        </tr>
    </table>
</div>
<br/>

<input type="hidden" class="controlValue<?= $_POST['counter'] ?>" value="<?= $vals[0] ?>" />
<?php
$j = 1;
if (count($parameters) > 0)
{
    ?>
    <b><?= Language::string(106) ?>:</b>
    <div class="ui-widget-content ui-state-focus">
        <div>
            <table>
                <?php
                for ($i = 0; $i < count($parameters); $i++)
                {
                    ?>
                    <tr>
                        <td><span class="spanIcon ui-icon ui-icon-help tooltip" title="<?= htmlspecialchars($parameters[$i]->description, ENT_QUOTES) ?>"></span></td>
                        <td><?= $parameters[$i]->name ?></td>
                        <td><b><?= Language::string(279) ?></b> <input type="text" class="controlValue<?= $_POST['counter'] ?> ui-widget-content ui-corner-all comboboxVars" value="<?= htmlspecialchars(isset($vals[$j]) ? $vals[$j] : "", ENT_QUOTES) ?>" /></td>
                    </tr>
                    <?php
                    $j++;
                }
                ?>
            </table>
        </div>
    </div>
    <br/>
    <?php
}

if (count($returns) > 0)
{
    ?>
    <b><?= Language::string(113) ?>:</b>
    <div class="ui-widget-content ui-state-focus">
        <div>
            <table>
                <?php
                for ($i = 0; $i < count($returns); $i++)
                {
                    ?>
                    <tr>
                        <td><span class="spanIcon ui-icon ui-icon-help tooltip" title="<?= htmlspecialchars($returns[$i]->description, ENT_QUOTES) ?>"></span></td>
                        <td><?= $returns[$i]->name ?></td>
                        <td><?= Language::string(279) ?> <input onchange="Test.uiSetVarNameChanged($(this))" type="text" class="ui-state-focus comboboxSetVars comboboxVars controlValue<?= $_POST['counter'] ?> ui-widget-content ui-corner-all" value="<?= htmlspecialchars(isset($vals[$j]) ? $vals[$j] : "", ENT_QUOTES) ?>" /></td>
                        <td>
                            <b><?= Language::string(277) ?></b> <select class="controlValue<?= $_POST['counter'] ?> ui-widget-content ui-corner-all">
                                <option value="0" <?= ($vals[$j + 1] == 0 ? "selected" : "") ?>><?= Language::string(275) ?></option>
                                <option value="1" <?= ($vals[$j + 1] == 1 ? "selected" : "") ?>><?= Language::string(18) ?></option>
                                <option value="2" <?= ($vals[$j + 1] == 2 ? "selected" : "") ?>><?= Language::string(276) ?></option>
                            </select>
                        </td>
                        <td>
                            <b><?= Language::string(279) ?></b> <select class="controlValue<?= $_POST['counter'] ?> ui-widget-content ui-corner-all">
                                <option value="0" <?= ($vals[$j + 2] == 0 ? "selected" : "") ?>><?= Language::string(280) ?></option>
                                <option value="1" <?= ($vals[$j + 2] == 1 ? "selected" : "") ?>><?= Language::string(281) ?></option>
                                <option value="2" <?= ($vals[$j + 2] == 2 ? "selected" : "") ?>><?= Language::string(282) ?></option>
                            </select>
                        </td>
                    </tr>
                    <?php
                    $j = $j + 3;
                }
                ?>
            </table>
        </div>
    </div>
    <?php
}
?>