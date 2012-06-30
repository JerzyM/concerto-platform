<?php
/*
  Concerto Platform - Online Adaptive Testing Platform
  Copyright (C) 2011-2012, The Psychometrics Centre, Cambridge University

  This program is free software; you can redistribute it and/or
  modify it under the terms of the GNU General Public License
  as published by the Free Software Foundation; version 2
  of the License, and not any of the later versions.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

if (!isset($ini)) {
    require_once'../../Ini.php';
    $ini = new Ini();
}
$logged_user = User::get_logged_user();
if ($logged_user == null) {
    echo "<script>location.reload();</script>";
    die(Language::string(278));
}

if (isset($oid)) {
    if (!$logged_user->is_module_writeable($class_name))
        die(Language::string(81));
    if (!$logged_user->is_object_editable($obj))
        die(Language::string(81));

    $parameters = $obj->get_parameter_TestVariables();
    $returns = $obj->get_return_TestVariables();
}
else {
    $oid = $_POST['oid'];
    $obj = Test::from_mysql_id($oid);

    $parameters = array();
    if (array_key_exists("parameters", $_POST)) {
        foreach ($_POST['parameters'] as $par) {
            array_push($parameters, json_decode($par));
        }
    }
    $returns = array();
    if (array_key_exists("returns", $_POST)) {
        foreach ($_POST['returns'] as $ret) {
            array_push($returns, json_decode($ret));
        }
    }
    $class_name = $_POST['class_name'];

    if (!$logged_user->is_module_writeable($class_name))
        die(Language::string(81));
    if (!$logged_user->is_object_editable($obj))
        die(Language::string(81));
}
?>

<script>
    $(function(){
        Methods.iniTooltips();
        Test.uiRefreshComboBoxes();
    
        $(".tooltipTestLogic").tooltip({
            content:function(){
                return "<?= Language::string(104) ?><hr/>"+$(this).next().val();
            },
            position:{ my: "left top", at: "left bottom", offset: "15 0" }
        });
    });
</script>

<table class="fullWidth">
    <tr>
        <td style="width:50%;" valign="top" align="center">
            <div class="ui-widget-content">
                <div class="ui-widget-header" align="center"><?= Language::string(106) ?>:</div>
                <div class="div<?= $class_name ?>Parameters">
                    <?php
                    if (count($parameters) > 0) {
                        ?>
                        <table class="fullWidth table<?=$class_name?>Parameters">
                            <?php
                        }
                        foreach ($parameters as $param) {
                            ?>
                            <tr>
                                <td>
                                    <span class="spanIcon tooltipTestLogic ui-icon ui-icon-document-b" onclick="Test.uiEditVariableDescription($(this).next())" title="<?= Language::string(107) ?>"></span>
                                    <textarea class="notVisible"><?= $param->description ?></textarea>
                                </td>
                                <td class="fullWidth">
                                    <input onchange="Test.uiVarNameChanged($(this))" type="text" class="ui-state-focus comboboxVars comboboxVarsParameter ui-widget-content ui-corner-all fullWidth" value="<?= htmlspecialchars($param->name, ENT_QUOTES) ?>" />
                                </td>
                            </tr>
                            <?php
                        }
                        if (count($parameters) > 0) {
                            ?>
                        </table>
                        <?php
                    } else {
                        ?>
                        <div class="ui-state-error padding margin" align="center"><?= Language::string(108) ?></div>
                        <?php
                    }
                    ?>
                    <div class="notVisible">
                        <?php
                        foreach ($parameters as $param) {
                            ?>
                            <input class="inputTestParameterVar" type="hidden" value="<?= $param->name ?>" />
                            <?php
                        }
                        ?>
                    </div>
                    <table>
                        <tr>
                            <td><span class="spanIcon ui-icon ui-icon-help tooltip" title="<?= Language::string(405) ?>"></span></td>
                            <td><span class="spanIcon tooltip ui-icon ui-icon-plus" onclick="Test.uiAddParameter()" title="<?= Language::string(109) ?>"></span></td>
                            <td><?php
                        if (count($parameters) > 0) {
                            ?><span class="spanIcon tooltip ui-icon ui-icon-minus" onclick="Test.uiRemoveParameter()" title="<?= Language::string(110) ?>"></span><?php } ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </td>
        <td style="width:50%;" valign="top" align="center">
            <div class="ui-widget-content">
                <div class="ui-widget-header" align="center"><?= Language::string(113) ?>:</div>
                <div class="div<?= $class_name ?>Returns">
                    <?php
                    if (count($returns) > 0) {
                        ?>
                        <table class="fullWidth table<?=$class_name?>Returns">
                            <?php
                        }
                        foreach ($returns as $ret) {
                            ?>
                            <tr>
                                <td>
                                    <span class="spanIcon tooltipTestLogic ui-icon ui-icon-document-b" onclick="Test.uiEditVariableDescription($(this).next())" title="<?= Language::string(107) ?>"></span>
                                    <textarea class="notVisible"><?= $ret->description ?></textarea>
                                </td>
                                <td class="fullWidth">
                                    <input onchange="Test.uiVarNameChanged($(this))" type="text" class="comboboxVars comboboxVarsReturn ui-widget-content ui-corner-all fullWidth" value="<?= htmlspecialchars($ret->name, ENT_QUOTES) ?>" />
                                </td>
                            </tr>
                            <?php
                        }
                        if (count($returns) > 0) {
                            ?>
                        </table>
                        <?php
                    } else {
                        ?>
                        <div class="ui-state-error padding margin" align="center"><?= Language::string(114) ?></div>
                        <?php
                    }
                    ?>
                    <div class="notVisible">
                        <?php
                        foreach ($returns as $ret) {
                            ?>
                            <input class="inputTestReturnVar" type="hidden" value="<?= $ret->name ?>" />
                            <?php
                        }
                        ?>
                    </div>
                    <table>
                        <tr>
                            <td><span class="spanIcon ui-icon ui-icon-help tooltip" title="<?= Language::string(406) ?>"></span></td>
                            <td><span class="spanIcon tooltip ui-icon ui-icon-plus" onclick="Test.uiAddReturn()" title="<?= Language::string(115) ?>"></span></td>
                            <td><?php
                        if (count($returns) > 0) {
                            ?><span class="spanIcon tooltip ui-icon ui-icon-minus" onclick="Test.uiRemoveReturn()" title="<?= Language::string(116) ?>"></span><?php } ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </td>
    </tr>
</table>