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


if (!isset($gCms))
    exit;
// CT MOD 11Feb16
// if( version_compare(CMS_VERSION,'1.99') > 0 ) {
//   return "ERROR: This module is not compatible with this version of CMSMS";
// }
$this->CreatePermission(USE_ECB2, USE_ECB2);

// put mention into the admin log - not needed anymore - core does this
// $this->Audit(0, $this->Lang('friendlyname'), $this->Lang('installed', $this->GetVersion()));
?>