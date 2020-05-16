<?php
#BEGIN_LICENSE
#-------------------------------------------------------------------------
# Module: CGExtensions (c) 2008-2018 by Robert Campbell
#         (calguy1000@cmsmadesimple.org)
#  An addon module for CMS Made Simple to provide useful functions
#  and commonly used gui capabilities to other modules.
#
#-------------------------------------------------------------------------
# CMSMS - CMS Made Simple is (c) 2005 by Ted Kulp (wishy@cmsmadesimple.org)
# Visit the CMSMS Homepage at: http://www.cmsmadesimple.org
#
#-------------------------------------------------------------------------
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# However, as a special exception to the GPL, this software is distributed
# as an addon module to CMS Made Simple.  You may not use this software
# in any Non GPL version of CMS Made simple, or in any version of CMS
# Made simple that does not indicate clearly and obviously in its admin
# section that the site was built with CMS Made simple.
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
#END_LICENSE
namespace CGExtensions\Email;

/**
 * A class for defining an email address and other associated FEU or admin information.
 *
 * @package CGExtensions\Email
 * @category Utilities
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2018 by Robert Campbell
 */

/**
 * A class for defining an email address and other associated FEU or admin information.
 * This class is used internally by the SimpleEmailProcessor object and various callbacks.
 *
 * @package CGExtensions
 */
class EmailDestination
{
    /**
     * An email address.
     *
     * @var string $addr
     */
    public $addr;

    /**
     * This property can be used to retrieve personal information for an FEU
     * to associate with the email.
     *
     * @var int $feu_uid
     */
    public $feu_uid;

    /**
     * This property can be used to retrieve additional information for an FEU group
     * to associate with the email.
     *
     * @var int $feu_gid
     */
    public $feu_gid;

    /**
     * This property can be used to retrieve personal information for an admin user
     * to associate with the email.
     *
     * @var int $admin_uid
     */
    public $admin_uid;

    /**
     * This property can be used to retrieve additional information for an admin group
     * to associate with the email.
     *
     * @var int $admin_gid
     */
    public $admin_gid;

    /**
     * @ignore
     */
    public function __get( $key )
    {
        throw new \InvalidArgumentException("$key is not a gettable member of ".__CLASS__);
    }

    /**
     * @ignore
     */
    public function __set( $key, $val )
    {
        throw new \InvalidArgumentException("$key is not a settable member of ".__CLASS__);
    }
} // class