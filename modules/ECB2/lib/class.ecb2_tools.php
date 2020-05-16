<?php
#
# ECB2 - Extended Content Blocks 2
#
# maintained by Chris Taylor, <chris@binnovative.co.uk>, since 2016
#
#-------------------------------------------------------------------------
#
# A fork of module: Extended Content Blocks (ECB)
# Original Author: Zdeno Kuzmany (zdeno@kuzmany.biz) / kuzmany.biz  / twitter.com/kuzmany
#
#-------------------------------------------------------------------------
#
# CMS - CMS Made Simple is (c) 2009 by Ted Kulp (wishy@cmsmadesimple.org)
# This project's homepage is: http://www.cmsmadesimple.org
# The module's homepage is: http://dev.cmsmadesimple.org/projects/skeleton/
#
#-------------------------------------------------------------------------
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
# Or read it online: http://www.gnu.org/licenses/licenses.html#GPL
#
#-------------------------------------------------------------------------


class ecb2_tools {

    private $block_name = '';
    private $value = '';
    private $adding = false;
    private $options = array();
    private $alias = '';
    private $field = '';
    private $txt = '';
    private $is_color_picker_lib_load = false;
    private $is_datepicker_lib_load = false;

    /**
     *
     * @param stribng $blockName
     * @param string $value
     * @param array $params
     * @param boolean $adding
     */
    public function __construct($blockName, $value, $params, $adding) {

        $this->block_name = $blockName;
        $this->alias = munge_string_to_url($blockName, true);
        $this->value = $value;
        $this->adding = $adding;

        $this->get_extra_options($params);

        if ( isset($this->options["default_value"]) && isSet($params["default_value"]) ) {
            $this->default = $params["default_value"];
            if ( $adding==true && empty($value)==true )
                $this->value = $params["default_value"];
        }

    }

    /**
     *  get content block
     * @return string
     */
    public function get_content_block_input() {
        // $description = ($this->options['description']=='') ? '' : $this->options['description'].'<br>';
        if ( !empty($this->options['description']) )
            $this->options['description'] .= '<br>';
        $function = 'get_' . $this->field;
        //return $description.$this->$function();
        return $this->$function();
    }

    /**
     *
     * @return string
     */
    private function get_textarea() {
        $tmp = '<textarea name="%s" rows="%d" cols="%d">%s</textarea>';
        return $this->options['description'].sprintf($tmp, $this->block_name, $this->options["rows"], $this->options["cols"], $this->value);
    }

    /**
     *
     * @return string
     */
    private function get_pages() {
        $contentops = cmsms()->GetContentOperations();
        return $this->options['description'].$contentops->CreateHierarchyDropdown('', $this->value, $this->block_name, 1, 1);
    }

    /**
     *
     * @return string
     */
    private function get_editor() {
        $mod = cms_utils::get_module('ECB2');
        return $this->options['description'].$mod->CreateTextArea(true, '', $this->value, $this->block_name, '', '', '', '', $this->options["cols"], $this->options["rows"]);
    }

    /**
     *
     * @return string
     */
    private function get_input() {
        //$tmp = ($this->options['description']=='') ? '' : $this->options['description'].'<br>';
        $tmp = '<input type="text" name="%s" size="%d" maxlength="%d" value="%s"/>';
        return $this->options['description'].sprintf($tmp, $this->block_name, $this->options["size"], $this->options["max_length"], $this->value);
    }

    /**
     *
     * @return string
     */
    private function get_timepicker() {
        $tmp = $this->get_datepicker_lib();
        $tmp .= '<input type="text" class="timepicker"  name="%s" size="%d" maxlength="%d" value="%s"/>';
        return $this->options['description'].sprintf($tmp, $this->block_name, $this->options["size"], $this->options["max_length"], $this->value);
    }

    /**
     *
     * @return string
     */
    private function get_datepicker() {
        $tmp = $this->get_datepicker_lib();
        $tmp .= '<input type="text" class="datepicker"  name="%s" size="%d" maxlength="%d" value="%s"/>';
        return $this->options['description'].sprintf($tmp, $this->block_name, $this->options["size"], $this->options["max_length"], $this->value);
    }

    private function get_datepicker_lib() {
        if ($this->is_datepicker_lib_load)
            return;
        $mod = cms_utils::get_module('ECB2');
        $config = cmsms()->GetConfig();
        $tmp = "
        <script language=\"javascript\" field=\"text/javascript\" src=\"" . $config["root_url"] . "/modules/" . $mod->GetName() . "/lib/js/jquery-ui-timepicker-addon.js\"></script>
        <script type=\"text/javascript\">
        $(function() {
            $('.datepicker')." . (isset($this->options["time"]) && $this->options["time"] ? 'datetimepicker' : 'datepicker' ) . " ({
                " . (isset($this->options["date_format"]) && $this->options["date_format"] ? "dateFormat: '" . $this->options["date_format"] . "'," : "") . "
                                " . (isset($this->options["time_format"]) && $this->options["time_format"] ? "timeFormat: '" . $this->options["time_format"] . "'" : "") . ",
                showOtherMonths: true,
                selectOtherMonths: true
            });
            $('.timepicker').timepicker ({
                timeFormat: '" . $this->options["time_format"] . "'
            });

        });
</script>";
        $this->is_datepicker_lib_load = true;
        return $tmp;
    }

    /**
     *
     * @return string
     */
    private function get_color_picker() {

        $txt = '';
        $first = cms_utils::get_app_data(__FUNCTION__);
        $config = cmsms()->GetConfig();
        $mod = cms_utils::get_module('ECB2');
        if ( !isset($this->value) && isset($this->default) )
            $this->value = $this->default;

        if (!$first) {
            $txt.= '<script language="javascript" field="text/javascript" src="' . $config["root_url"] . '/modules/' . $mod->GetName() . '/lib/js/mColorPicker.min.js"></script>';
            $txt.= '<script>
$.fn.mColorPicker.defaults.imageFolder = " ' . $config["root_url"] . '/modules/' . $mod->GetName() . '/lib/js/images/";
</script>';
        }

        $tmp = '<input  type="color" data-hex="true" name="%s" id="%s" size="' . $this->options["size"] . '" value="%s"/>';
        $txt .= sprintf($tmp, $this->block_name, $this->alias, $this->value);

        cms_utils::set_app_data(__FUNCTION__, 1);

        return $this->options['description'].$txt;
    }

    /**
     *
     * @return string
     */
    private function get_checkbox() {

        $mod = cms_utils::get_module('ECB2');
        return $this->options['description'].$mod->CreateInputHidden('', $this->block_name, 0) . $mod->CreateInputCheckbox('', $this->block_name, 1, $this->value);
    }

    /**
     *
     * @return string
     */
    private function get_file_selector() {

        $mod = cms_utils::get_module('ECB2');
        $config = cmsms()->GetConfig();
        // 1.  Get the directory contents
        $adddir = get_site_preference('contentimage_path');
        if ($this->options['dir'])
            $adddir = $this->options['dir'];


        $dir = cms_join_path($config['uploads_path'], $adddir);
        $filetypes = $this->options['filetypes'];
        if ($filetypes != '') {
            $filetypes = explode(',', $filetypes);
            for ($i = 0; $i < count($filetypes); $i++) {
                $filetypes[$i] = '*.' . $filetypes[$i];
            }
        }
        $excludes = $this->options['excludeprefix'];
        if ($excludes != '') {
            $excludes = explode(',', $excludes);
            for ($i = 0; $i < count($excludes); $i++) {
                $excludes[$i] = $excludes[$i] . '*';
            }
        }
        $fl = cge_dir::recursive_glob($dir, $filetypes, 'FILES', $excludes, $this->options['recurse']);

        // 2.  Remove prefix
        for ($i = 0; $i < count($fl); $i++) {
            $fl[$i] = str_replace($dir, '', $fl[$i]);
        }

        // 2.  Sort
        if (is_array($fl) && $this->options['sortfiles']) {
            sort($fl);
        }

        $opts = array();
        $url_prefix = $adddir;
        for ($i = 0; $i < count($fl); $i++) {
            $opts[$fl[$i]] = $url_prefix . $fl[$i];
        }
        $opts = array('' => '') + $opts;

        $default_content_id = ContentOperations::get_instance()->GetDefaultContent();
        $parms = array();
        $parms = $this->options;
        $parms['field'] = 'file_selector';
        $parms['block_name'] = $this->block_name;
        $parms['value'] = $this->value;
        $parms['adding'] = $this->adding;
        $parms['showtemplate'] = 'false';
        $refresh_url = $mod->Createlink('cntnt01', 'refresh', $default_content_id, '', $parms, '', true);
        $refresh = '<a href="' . $refresh_url . '&showtemplate=false" class="file_selector_refresh">
<img src="../modules/' . $mod->GetName() . '/icons/ajax-refresh-icon.gif" alt="' . $mod->Lang('refresh') . '">
</a>';

        $script = '';
        if (!cms_utils::get_app_data(__FUNCTION__)) {
            $script = "
                <script>
$(document).ready(function(){
$('.file_selector_refresh').live('click', function(){
 var url = $(this).attr('href');
 $(this).prev().css('opacity',0.3);
 $(this).parent().parent().load(url, '', function(html){
});
return false;
})

$('.file_selector_select select').change(function(){
var imgtag = $(this).parent().next();
imgtag.attr('src', imgtag.data('uploadsurl')+'/'+$(this).val());
})
})
</script>
";

            cms_utils::set_app_data(__FUNCTION__, 1);
        }

        $preview = '';
        if ($this->options['preview']) {
            if ($this->value)
                $preview = '<img style"max-width:200px;" class="file_selector_preview" data-uploadsurl="' . $config["uploads_url"] . '"   src="' . $config["uploads_url"] . '/' . $this->value . '" alt="">';
            else
                $preview = '<img style"max-width:200px;" class="file_selector_preview" alt="" data-uploadsurl="' . $config["uploads_url"] . '">';
        }




        return $this->options['description'].'<div class="file_selector_select">' . $mod->CreateInputDropdown('', $this->block_name, $opts, -1, $this->value) . $refresh . $script . '</div>' . $preview;
    }



    /**
     *
     * @return string
     */
    private function get_sortablelist() {
        $mod = cms_utils::get_module('ECB2');
        $first = !cms_utils::get_app_data(__FUNCTION__); // is this first call of function > output lib
        $options = array();
        $tmp = array();

        if ($this->options["udt"])
            $options = UserTagOperations::get_instance()->CallUserTag($this->options["udt"], $tmp);
        $optionsarray = explode(',', $this->options["values"]);
        if (empty($optionsarray))
            return;
        foreach ($optionsarray as $option) {
            if ($option!='') {
                $key_val = explode('=', $option);
                $options[$key_val[0]] = $key_val[1];
            }
        }
        if (empty($this->options['first_value']) == false)
            $options = array($this->options['first_value'] => '') + $options;
        $selectedList = explode(',', $this->value);
        $available = $options;
        $selected = array();
        foreach ($selectedList as $item) {
            if ( array_key_exists($item, $available) ) {
                $selected[$item] = $available[$item];
                unset($available[$item]);
            }
        }
        if ($this->options["max_number"]) // max_number takes precidence if both set
            $this->options["required_number"] = "";
        cms_utils::set_app_data(__FUNCTION__, 1);   // set after function called, so lib output only once
        // smarty processing to display admin page
        $smarty = Smarty_CMS::get_instance();
        $tpl = $smarty->CreateTemplate($mod->GetTemplateResource('sortablelist_template.tpl'), null, null, $smarty);
        $tpl->assign('selectarea_prefix',$this->block_name);
        $tpl->assign('selected_str',$this->value);
        $tpl->assign('selected',$selected);
        $tpl->assign('available', $available);
        $tpl->assign('description', $this->options['description']);
        $tpl->assign('labelLeft', $this->options["label_left"]);
        $tpl->assign('labelRight', $this->options["label_right"]);
        $tpl->assign('mod',$mod);
        $tpl->assign('first', $first);
        $tpl->assign('maxNumber', $this->options["max_number"]);
        $tpl->assign('requiredNumber', $this->options["required_number"]);
        return $this->options['description'].$tpl->fetch();
    }



    /**
     *
     * @return string
     */
    private function get_text() {

        if (!$this->options["text"])
            return;
        return $this->options['description'].$this->options["text"];
    }

    /**
     *
     * @return string
     */
    private function get_hr() {
        return $this->options['description'].'<hr style="display:block; border:0 none; background:#ccc;" />';
    }

    /**
     *
     * @return string
     */
    private function get_link() {

        if (!$this->options["link"] || !$this->options["text"])
            return;

        $mod = cms_utils::get_module('ECB2');
        return $this->options['description'].'<a target="' . $this->options["target"] . '" href="' . $this->options["link"] . '">' . $this->options["text"] . '</a>';
    }

    /**
     *
     * @return string
     */
    private function get_module_link() {

        $mod = '';
        if ($this->options["mod"])
            $mod = cms_utils::get_module($this->options["mod"]);

        $userid = get_userid();
        $userops = cmsms()->GetUserOperations();
        $adminuser = $userops->UserInGroup($userid, 1);

        $tmp = '<input id="mt_' . $this->block_name . '" ' . ( $adminuser ? 'type="text"' : 'type="hidden"') . ' name="' . $this->block_name . '" value="' . ($this->value ? $this->value : $this->options["default_value"]) . '"  size="%d" maxlength="%d" />';
        $tmp = sprintf($tmp, $this->options["size"], $this->options["max_length"]);
        // original CT edit
        return $this->options['description'].(is_object($mod) ? $mod->CreateLink('', 'defaultadmin', '', $this->options["text"], array(), '', false, 0, 'target="' . $this->options["target"] . '"') : $this->options["text"]); // . '<br />' . $tmp; // CT comment out
    }



    /**
     * DEPRECATED
     * @return string
     */
    private function get_dropdown_from_module() {
        if (!$this->options["mod"])
            return;

        $mod = cms_utils::get_module('ECB2');
        $data = $mod->ProcessTemplateFromData('{' . $this->options["mod"] . '}');

        $options = array();
        $optionsarray = explode(',', $data);
        if (empty($optionsarray))
            return;

        foreach ($optionsarray as $option) {
            $key_val = explode('=', $option);
            $options[$key_val[0]] = $key_val[1];
        }
        if (empty($this->options['default_value']) == false)
            $options = array($this->options['default_value'] => '') + $options;

        return $this->options['description'].$mod->CreateInputDropdown('', $this->block_name, $options, -1, $this->value);
    }

    /**
     *
     * @return string
     */
    private function get_dropdown() {
        if (!$this->options["values"])
            return;
        $mod = cms_utils::get_module('ECB2');
        $smarty = cmsms()->GetSmarty();

        $options = array();

        $optionsarray = explode(',', $this->options["values"]);
        if (empty($optionsarray))
            return;

        foreach ($optionsarray as $option) {
            $key_val = explode('=', $option);
            $options[$key_val[0]] = $key_val[1];
        }
        if (empty($this->options['first_value']) == false)
            $options = array($this->options['first_value'] => '') + $options;

        if ($this->options['multiple']) {
            $selecteditems = explode(',', $this->value);
            $script = '';
            if (!cms_utils::get_app_data(__FUNCTION__)) {
                $script = "
                <script>
$(document).ready(function(){
$('.ecb_mutliple_select select').change(function(){
$(this).prev().val(($(this).val().join(',')));
})
})
</script>
";
                cms_utils::set_app_data(__FUNCTION__, 1);
            }

            return $this->options['description'].$script . '<div class="ecb_mutliple_select">' . $mod->CreateInputHidden('', $this->block_name, $this->value) . $mod->CreateInputSelectList('', $this->block_name . '_tmp', $options, $selecteditems, $this->options["size"]) . '</div>';
        } else {

            return $this->options['description'].$mod->CreateInputDropdown('', $this->block_name, $options, -1, $this->value);
        }
    }

    /**
     *
     * @return string
     */
    private function get_dropdown_from_udt() {
        if (!$this->options["udt"])
            return;
        $mod = cms_utils::get_module('ECB2');
        $smarty = cmsms()->GetSmarty();

        if ( UserTagOperations::get_instance()->UserTagExists($this->options["udt"]) ) {
            $tmp = array();
            $options = UserTagOperations::get_instance()->CallUserTag($this->options["udt"], $tmp);
        } else {
            $err = $mod->Lang('udt_error', $this->options["udt"] );
            $options = array();
        }

        if (empty($this->options['first_value']) == false)
            $options = array($this->options['first_value'] => '') + $options;

        if (!empty($err)) {
            return '<div class="pagewarning">'.$err.'</div>';

        } elseif ($this->options['multiple']) {
            $selecteditems = explode(',', $this->value);
            $script = '';
            if (!cms_utils::get_app_data(__FUNCTION__)) {
                $script = "
                <script>
$(document).ready(function(){
$('.ecb_mutliple_select_udt select').change(function(){
$(this).prev().val(($(this).val().join(',')));
})
})
</script>
";
                cms_utils::set_app_data(__FUNCTION__, 1);
            }

            return $this->options['description'].$script . '<div class="ecb_mutliple_select_udt">' . $mod->CreateInputHidden('', $this->block_name, $this->value) . $mod->CreateInputSelectList('', $this->block_name . '_tmp', $options, $selecteditems, $this->options["size"]) . '</div>';
        } else {

            return $this->options['description'].$mod->CreateInputDropdown('', $this->block_name, $options, -1, $this->value);
        }
    }

    /**
     *
     * @return string
     */
    private function get_dropdown_from_gbc() {
        if (!$this->options["gbc"])
            return;
        $mod = cms_utils::get_module('ECB2');
        $smarty = cmsms()->GetSmarty();

        $tmp = array();
        $options = array();
        $optionsgbc = $smarty->fetch('globalcontent:' . $this->options["gbc"]);

        $optionsarray = explode(',', $optionsgbc);
        if (empty($optionsarray))
            return;

        foreach ($optionsarray as $option) {
            $key_val = explode('=', $option);
            $options[$key_val[0]] = $key_val[1];
        }

        if (empty($this->options['first_value']) == false)
            $options = array($this->options['first_value'] => '') + $options;

        if ($this->options['multiple']) {
            $selecteditems = explode(',', $this->value);
            $script = '';
            if (!cms_utils::get_app_data(__FUNCTION__)) {
                $script = "
                <script>
$(document).ready(function(){
$('.ecb_mutliple_select_udt select').change(function(){
$(this).prev().val(($(this).val().join(',')));
})
})
</script>
";
                cms_utils::set_app_data(__FUNCTION__, 1);
            }

            return $this->options['description'].$script . '<div class="ecb_mutliple_select_udt">' . $mod->CreateInputHidden('', $this->block_name, $this->value) . $mod->CreateInputSelectList('', $this->block_name . '_tmp', $options, $selecteditems, $this->options["size"]) . '</div>';
        } else {

            return $this->options['description'].$mod->CreateInputDropdown('', $this->block_name, $options, -1, $this->value);
        }
    }

    /**
     *
     * @return string
     */
    private function get_module() {

        $modops = cmsms()->GetModuleOperations();
        $modules = $modops->GetInstalledModules();
        $modulesarray = array();
        foreach ($modules as $module) {
            $mod = cms_utils::get_module($module);
            if (is_object($mod))
                $modulesarray[$mod->GetName()] = $module;
        }

        $mod = cms_utils::get_module('ECB2');
        return $this->options['description'].$mod->CreateInputDropdown('', $this->block_name, $modulesarray, -1, $this->value);
    }



    /**
     *
     * @return string
     */
    private function get_radio() {
        if (!$this->options["values"])
            return;
        $mod = cms_utils::get_module('ECB2');
        $smarty = cmsms()->GetSmarty();

        $options = array();

        $optionsarray = explode(',', $this->options["values"]);
        if (empty($optionsarray))
            return;

        foreach ($optionsarray as $option) {
            $key_val = explode('=', $option);
            $options[$key_val[0]] = $key_val[1];
        }

        $delimiter = ($this->options["inline"]) ? '&nbsp;&nbsp;&nbsp;&nbsp;' : '<br>';

        if ( !empty($this->value) ) {
            $selectedValue = $this->value;
        } else {
            if ($this->options["default_value"]=='-1') {
                $selectedValue = reset($options);
            } else {
                $selectedValue = $options[$this->options["default_value"]];
            }
        }

        return $this->options['description'].$mod->CreateInputRadioGroup('', $this->block_name, $options, $selectedValue, '', $delimiter);
    }



    /**
     *
     * @return string
     */
    public function get_hidden() {
        $tmp = '<input type="hidden" name="%s" value="%s">';
        $input = sprintf($tmp, $this->block_name, $this->options['value']);
        return $input;
    }



    public function get_fieldset_start() {
    //**********************************************************************************************
    //
    //
    //**********************************************************************************************
        $tmp = '</p>
        </div>
            <fieldset name="%s">
            <legend>%s</legend>
            %s
        <div><p>';
        $input = sprintf($tmp, $this->block_name, $this->options['legend'], $this->options['description']);
        return $input;
    }



    public function get_fieldset_end() {
    //**********************************************************************************************
    //
    //**********************************************************************************************
        return '</p>
        </div>
        </fieldset>
        <div><p>';
    }



    private function get_extra_options(array $params) {

        if (!isSet($params["field"]))
            return;

        $options = array();
        $default_options = array();
        switch (strtolower($params["field"])) {
            case "color_picker":
                $default_options["size"] = 10;
                $default_options["default_value"] = '';
                $default_options["description"] = '';
                break;
            case "module_link":
                $default_options["mod"] = '';
                $default_options["text"] = '';
                $default_options["target"] = '_self';
                $default_options["default_value"] = '';
                $default_options["size"] = 30;
                $default_options["max_length"] = 255;
                $default_options["description"] = '';
                break;
            case "link":
                $default_options["text"] = '';
                $default_options["target"] = '_self';
                $default_options["link"] = '';
                $default_options["description"] = '';
                break;
            case "module":
                $default_options["default_value"] = '';
                $default_options["text"] = '';
                $default_options["link"] = '';
                $default_options["description"] = '';
                break;
            case "dropdown_from_module":
                $default_options["mod"] = '';
                $default_options["default_value"] = '';
                $default_options["first_value"] = '';
                $default_options["description"] = '';
                break;
            case "file_selector":
                $default_options["filetypes"] = '';
                $default_options["excludeprefix"] = '';
                $default_options["recurse"] = '';
                $default_options["sortfiles"] = '';
                $default_options["dir"] = '';
                $default_options["preview"] = '';
                $default_options["description"] = '';
                break;
            case "dropdown":
                $default_options["size"] = 5;
                $default_options["multiple"] = '';
                $default_options["values"] = '';
                $default_options["default_value"] = '';
                $default_options["first_value"] = '';
                $default_options["description"] = '';
                break;
            case "dropdown_from_udt":
                $default_options["size"] = 5;
                $default_options["multiple"] = '';
                $default_options["values"] = '';
                $default_options["default_value"] = '';
                $default_options["first_value"] = '';
                $default_options["udt"] = '';
                $default_options["description"] = '';
                break;
            case "dropdown_from_gbc":
                $default_options["size"] = 5;
                $default_options["multiple"] = '';
                $default_options["values"] = '';
                $default_options["default_value"] = '';
                $default_options["first_value"] = '';
                $default_options["gbc"] = '';
                $default_options["description"] = '';
                break;
            case "textarea":
                $default_options["default_value"] = '';
                $default_options["rows"] = 20;
                $default_options["cols"] = 80;
                $default_options["description"] = '';
                break;
            case "editor":
                $default_options["default_value"] = '';
                $default_options["rows"] = 20;
                $default_options["cols"] = 80;
                $default_options["description"] = '';
                break;
            case "input":
                $default_options["default_value"] = '';
                $default_options["size"] = 30;
                $default_options["max_length"] = 255;
                $default_options["description"] = '';
                break;
            case "sortablelist":
                $default_options["values"] = '';
                $default_options["first_value"] = '';
                $default_options["allowduplicates"] = false;
                $default_options["max_selected"] = -1;
                $default_options["label_left"] = '';
                $default_options["label_right"] = '';
                $default_options["udt"] = '';
                $default_options["description"] = '';
                $default_options["max_number"] = '';
                $default_options["required_number"] = '';
                break;
            case "text":
                $default_options["text"] = '';
                $default_options["execute"] = '';
                $default_options["description"] = '';
                break;
            case "pages":
                $default_options["default_value"] = '';
                $default_options["description"] = '';
                break;
            case "checkbox":
                $default_options["default_value"] = '';
                $default_options["description"] = '';
                break;
            case 'timepicker':
                $default_options["size"] = 10;
                $default_options["max_length"] = 10;
                $default_options["time_format"] = 'HH:mm';
                $default_options["description"] = '';
                break;
            case 'datepicker':
                $default_options["size"] = 20;
                $default_options["max_length"] = 20;
                $default_options["date_format"] = 'yy-mm-dd';
                $default_options["time_format"] = 'HH:mm';
                $default_options["time"] = '';
                $default_options["description"] = '';
                break;
            case 'radio':
                $default_options["multiple"] = '';
                $default_options["values"] = '';
                $default_options["default_value"] = -1;
                $default_options["inline"] = false;
                $default_options["description"] = '';
                break;
            case "hr":
                $default_options["description"] = '';
                break;
            case "image_picker":
                $default_options["description"] = '';
                break;
            case 'hidden':
                $default_options['value']='';
                break;
            case 'fieldset_start':
                $default_options['legend']='';
                $default_options["description"] = '';
                break;
        }

        $this->field = $params["field"];

        if (empty($params) == false) {
            foreach ($params as $key => $param) {
                if (isSet($default_options[$key]) && empty($param) == false)
                    $default_options[$key] = $param;
            }
        }


        $this->options = $default_options;
    }

}

?>