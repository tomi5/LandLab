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
 * Defines a protocol for processors of Email objects.
 *
 * @package CGExtensions
 * @category Utilities
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2018 by Robert Campbell
 */

/**
 * An interface to define a protocol for processors of email objects.
 *
 * @package CGExtensions
 */
interface EmailProcessor
{
    /**
     * Optionally set a callback to be used before each message is sent.
     *
     * The callback function should accept two parameters.  An Email object, and an EmailDest object.  It should return an email object.
     *
     * @param callable $c The callback
     * @return void
     */
    public function before_send( callable $c );

    /**
     * Optionally set a callback to be used after each message is successfuly sent.
     *
     * The callback function should accept two parameters.  An Email object, and an EmailDest object.  It should return an email object.
     *
     * @param callable $c The callback
     * @return void
     */
    public function after_send( callable $c );

    /**
     * Optionally set a callback to be used when an error occurs.
     *
     * The callback function should accept two parameters.  An EmailDest object, and an error string.
     *
     * @param callable $c The callback
     * @return void
     */
    public function on_error( callable $c );

    /**
     * The callback for sending the current email object.
     * It is the responsibility of the class to accept an Email object in its constructor, or otherwise.
     *
     * @return void
     */
    public function send();
} // interface