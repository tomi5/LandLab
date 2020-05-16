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


define('USE_ECB2', 'Use Extended Content Blocks 2');

class ECB2 extends CGExtensions {

    public function __construct() {
        parent::__construct();
    }

    public function MinimumCMSVersion() { return '2.0'; }
    public function LazyLoadFrontend() { return TRUE;   }
    public function GetName() { return 'ECB2';  }
    public function GetFriendlyName() { return $this->Lang('friendlyname'); }
    public function GetVersion() { return '1.3.1'; }
    public function InitializeAdmin() { $this->AddImageDir('icons'); }
    public function GetHelp() { return $this->Lang('help'); }
    public function GetAuthor() { return 'Chris Taylor (twitter.com/KiwiChrisBT)'; }
    public function GetAuthorEmail() { return 'chris@binnovative.co.uk'; }
    public function GetChangeLog() { return file_get_contents(dirname(__file__) . '/changelog.inc'); }
    public function HasAdmin() { return TRUE;}
    public function GetAdminSection() { return 'extensions';}
    public function GetAdminDescription() { return $this->Lang('admindescription'); }
    public function VisibleToAdminUser() { return ($this->CheckAccess()); }
    public function CheckAccess($perm = USE_ECB2) { return $this->CheckPermission($perm); }
    public function GetDependencies() { return array('CGExtensions' => '1.38');}
    public function InstallPostMessage() { return $this->Lang('postinstall');}
    public function UninstallPostMessage() { return $this->Lang('postuninstall');}
    public function UninstallPreMessage() { return $this->Lang('really_uninstall');}

    public function InitializeFrontend() {
        $this->RegisterModulePlugin();
        $this->RestrictUnknownParams();

        $this->SetParameterType('block_name', CLEAN_STRING);
        $this->SetParameterType('value', CLEAN_STRING);
        $this->SetParameterType('adding', CLEAN_STRING);
        $this->SetParameterType('sortfiles', CLEAN_STRING);
        $this->SetParameterType('excludeprefix', CLEAN_STRING);
        $this->SetParameterType('recurse', CLEAN_STRING);
        $this->SetParameterType('filetypes', CLEAN_STRING);
        $this->SetParameterType('field', CLEAN_STRING);
        $this->SetParameterType('dir', CLEAN_STRING);
        $this->SetParameterType('preview', CLEAN_STRING);
        $this->SetParameterType('date_format', CLEAN_STRING);
        $this->SetParameterType('description', CLEAN_STRING);
        $this->SetParameterType('default_value', CLEAN_STRING);
        $this->SetParameterType('max_number', CLEAN_INT);
        $this->SetParameterType('maxnumber', CLEAN_INT);
        $this->SetParameterType('legend', CLEAN_STRING);

    }


    /**
     * @link http://www.cmsmadesimple.org/apidoc/CMS/CMSModule.html#HasCapability
     * @ignore
     */
    function HasCapability($capability, $params = array()) {
        switch ($capability) {
            case 'contentblocks':
                return TRUE;
            default:
                return FALSE;
        }
    }


    // content block
    /**
     * @link http://www.cmsmadesimple.org/APIDOC2_0/classes/CMSModule.html#method_GetContentBlockFieldInput
     */
    public function GetContentBlockFieldInput($blockName, $value, $params, $adding=false, ContentBase $content_obj) {

        $ecb2 = new ecb2_tools($blockName, $value, $params, $adding);
        return $ecb2->get_content_block_input();

    }

}

?>