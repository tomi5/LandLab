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
 * Defines an object representing a templatable email, and who it is going to be sent to in the context
 * of CMSMS.
 *
 * @package CGExtensions\Email
 * @category Utilities
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2018 by Robert Campbell
 */

/**
 * Defines an immutable object representing a templatable email, and who it is going to be sent to in the context of CMSMS.
 *
 * @since 1.59
 * @property-read string[] $to_admin_groups  A list of admin group names or ids that this message should be sent to.
 * @property-read int[]    $to_feu_users     A list of FEU user ids that this message should be sent to.
 * @property-read string[] $to_feu_groups    A list of FEU user group names or ids that this message should be sent to.
 * @property-read bool     $to_current_admin Indicates whether this message should be sent to the current logged in administrator (admin requests only)
 * @property-read bool     $to_current_feu   Indicates whether this message should be sent to the current logged in FEU user (frontend requests only)
 * @property-read string[] $to_addr          Additional email addresses to send this message to
 * @property-read string[] $cc_addr          A list of email addresses to CC this message to.
 * @property-read string[] $bcc_addr         A list of email addresses to BCC this message to.
 * @property-read array    $data             An associative array of variables and values to assign to smarty when processing the template(s).
 * @property-read string[] $attachments      An optional list of file attachments
 * @property-read string   $subj_tpl         The smarty template for the email subject.
 * @property-read string   $body_tpl         The smarty template for the email body
 * @property-read int      $priority         A priority value between 1 and 5 where 1 is high priority, and 5 is low.  Default is 3
 * @property-read bool     $encode_subject   Enable base64 encoding of subject to allow extended characters (but not HTML entities).  Default is 0
 * @package CGExtensions\Email
 */
class Email
{
    /**
     * @ignore
     */
    private $_to_feu_users = [];
    
    /**
     * @ignore
     */
    private $_to_admin_groups = [];
    /**
     * @ignore
     */
    private $_to_feu_groups = [];

    /**
     * @ignore
     */
    private $_to_current_admin = false;

    /**
     * @ignore
     */
    private $_to_current_feu = false;

    /**
     * @ignore
     */
    private $_to_addr = [];

    /**
     * @ignore
     */
    private $_cc_addr = [];

    /**
     * @ignore
     */
    private $_bcc_addr = [];

    /**
     * @ignore
     */
    private $_subj_tpl;

    /**
     * @ignore
     */
    private $_body_tpl;

    /**
     * @ignore
     */
    private $_data = [];

    /**
     * @ignore
     */
    private $_attachments = [];

    /**
     * @ignore
     */
    private $_priority = 3;

    /**
     * @ignore
     */
    private $_encode_subject = false;

    /**
     * Given an associative array, create an email object.
     *
     * This method is only used internally.
     *
     * @internal
     * @param array $in
     * @return Email
     */
    public static function from_array( array $in )
    {
        $obj = new self;
        if( isset( $in['to_admin_groups'] ) ) {
            if( is_string( $in['to_admin_groups'] ) ) {
                $in['to_admin_groups'] = explode(',', $in['to_admin_groups'] );
            }
            foreach( $in['to_admin_groups'] as $gid ) {
                $gid = trim($gid);
                if( $gid && !in_array( $gid, $obj->_to_admin_groups ) ) $obj->_to_admin_groups[] = $gid;
            }
        }
        if( isset( $in['to_feu_groups'] ) ) {
            if( is_string( $in['to_feu_groups'] ) ) {
                $in['to_feu_groups'] = explode(',', $in['to_feu_groups'] );
            }
            foreach( $in['to_feu_groups'] as $gid ) {
                $gid = trim($gid);
                if( $gid && !in_array( $gid, $obj->_to_feu_groups ) ) $obj->_to_feu_groups[] = $gid;
            }
        }
        $obj->_to_current_admin = ( isset( $in['to_current_admin'] ) ) ? cms_to_bool( $in['to_current_admin'] ) : false;
        $obj->_to_current_feu = ( isset( $in['to_current_feu'] ) ) ? cms_to_bool( $in['to_current_feu'] ) : false;
        if( isset( $in['to_addr'] ) ) {
            if( is_string( $in['to_addr'] ) ) {
                $in['to_addr'] = explode(',', $in['to_addr'] );
            }
            foreach( $in['to_addr'] as $addr ) {
                if( is_email($addr) && !in_array( $addr, $obj->_to_addr ) ) $obj->_to_addr[] = $addr;
            }
        }
        if( isset( $in['cc_addr'] ) ) {
            if( is_string( $in['cc_addr'] ) ) {
                $in['cc_addr'] = explode(',', $in['cc_addr'] );
            }
            foreach( $in['cc_addr'] as $addr ) {
                if( is_email($addr) && !in_array( $addr, $obj->_cc_addr ) ) $obj->_cc_addr[] = $addr;
            }
        }
        if( isset( $in['bcc_addr'] ) ) {
            if( is_string( $in['bcc_addr'] ) ) {
                $in['bcc_addr'] = explode(',', $in['bcc_addr'] );
            }
            foreach( $in['bcc_addr'] as $addr ) {
                if( is_email($addr) && !in_array( $addr, $obj->_bcc_addr ) ) $obj->_bcc_addr[] = $addr;
            }
        }
        if( isset( $in['subj_tpl']) ) {
            $obj->_subj_tpl = trim( $in['subj_tpl'] );
        }
        if( isset( $in['body_tpl']) ) {
            $obj->_body_tpl = trim( $in['body_tpl'] );
        }
        if( isset( $in['priority']) ) {
            $val = (int) $in['priority'];
            $val = max(1,min(5,$val));
            $obj->_priority = $val;
        }
        $obj->_encode_subject = \cge_param::get_bool( $in, 'encode_subject' );
        return $obj;
    }

    /**
     * @ignore
     */
    public function __get( $key )
    {
        switch( $key ) {
        case 'to_admin_groups':
            return $this->_to_admin_groups;

        case 'to_feu_users':
            return $this->_to_feu_users;
            
        case 'to_feu_groups':
            return $this->_to_feu_groups;

        case 'to_current_admin':
            return (bool) $this->_to_current_admin;

        case 'to_current_feu':
            return (bool) $this->_to_current_feu;

        case 'to_addr':
            return $this->_to_addr;

        case 'cc_addr':
            return $this->_cc_addr;

        case 'bcc_addr':
            return $this->_bcc_addr;

        case 'data':
            return $this->_data;

        case 'attachments':
            return $this->_attachments;

        case 'subj_tpl':
            return $this->_subj_tpl;

        case 'body_tpl':
            return $this->_body_tpl;

        case 'priority':
            return $this->_priority;

        case 'encode_subject':
            return $this->_encode_subject;
            
        default:
            throw new \InvalidArgumentException("$key is not a gettable member of ".__CLASS__);
        }
    }

    /**
     * @ignore
     */
    public function __set( $key, $val )
    {
        throw new \InvalidArgumentException("$key is not a settable member of ".__CLASS__);
    }

    /**
     * Test if this email object has some addresses defined.
     * Note:  does not test if the addresses are duplicate or valid.
     *
     * @return bool
     */
    public function has_addresses()
    {
        if( ! empty( $this->_to_admin_groups) ) return true;
        if( ! empty( $this->_to_feu_users ) ) return true;
        if( ! empty( $this->_to_feu_groups ) ) return true;
        if( $this->_to_current_admin ) return true;
        if( $this->_to_current_feu ) return true;
        if( !empty( $this->_to_addr ) ) return true;
    }

    /**
     * Clear all known addresses.
     *
     * @return Email
     */
    public function clear_addresses()
    {
        // leave attachments, data, and templates in place... but clear all address information.
        $obj = clone $this;
        $obj->_to_admin_groups = [];
        $obj->_to_feu_users = [];
        $obj->_to_feu_groups = [];
        $obj->_to_current_admin = false;
        $obj->_to_current_feu = false;
        $obj->_to_addr = [];
        $obj->_cc_addr = [];
        $obj->_bcc_addr = [];
        return $obj;
    }

    /**
     * Adjust the subject template
     *
     * @param string $tpl The smarty template used to generate the email subject.
     * @return Email
     */
    public function with_subject_template( $tpl )
    {
        $obj = clone $this;
        $this->_subj_tpl = trim($tpl);
        return $obj;
    }

    /**
     * Adjust the body template
     *
     * @param string $tpl The smarty template used to generate the email body.  Cannot be empty.
     * @return Email
     */
    public function with_body_template( $tpl )
    {
        $tpl = trim($tpl);
        if( !$tpl ) throw new \LogicException('Cannot use an empty string as a body template');

        $obj = clone $this;
        $this->_body_tpl = $tpl;
        return $obj;
    }

    /**
     * Add a key and value to the data
     *
     * @param string $key
     * @param mixed $val
     * @return Email
     */
    public function add_data( $key, $val )
    {
        $key = trim( $key );
        if( !$key ) return $this;

        $obj = clone $this;
        $obj->_data[$key] = $val;
        return $obj;
    }

    /**
     * Add an attachment
     *
     * @param string $filename A complete path name
     * @return Email
     */
    public function add_attachment( $filename )
    {
        $filename = trim( $filename );
        if( !$filename || !is_file($filename) ) return $this;
        if( in_array( $filename, $this->_attachments ) ) return $this;

        $obj = clone $this;
        $obj->_attachments[] = $filename;
        return $obj;
    }

    /**
     * Add an admin group to the list of groups to send to.
     *
     * @param string|int gid An admin group id or name
     * @return Email
     */
    public function add_admin_group( $gid )
    {
        $gid = trim( $gid );
        if( strlen($gid) < 1 ) return $this;
        if( in_array( $gid, $this->_to_admin_groups ) ) return $this;

        $obj = clone $this;
        $obj->_to_admin_groups[] = $gid;
        return $obj;
    }

    /**
     * Add an FEU uid to the list of individual FEU users to send to.
     *
     * Note, must be a valid uid. The system will not check if this is a disabled or expired user.
     *
     * @param int $uid A valid FEU user id
     * @return Email
     */
    public function add_feu_uid( $uid )
    {
        $uid = (int) $uid;
        if( $uid < 1 ) return $this;
        if( in_array( $uid, $this->_to_feu_users ) ) return $this;

        $obj = clone $this;
        $obj->_to_feu_users[] = $uid;
        return $obj;
    }
    
    /**
     * Add an FEU group to the list of groups to send to.
     *
     * @param string|int $gid An FEU group id or name.
     * @return Email
     */
    public function add_feu_group( $gid )
    {
        $gid = trim( $gid );
        if( strlen($gid) < 1 ) return $this;
        if( in_array( $gid, $this->_to_feu_groups ) ) return $this;

        $obj = clone $this;
        $obj->_to_feu_groups[] = $gid;
        return $obj;
    }

    /**
     * Adjust the priority of the message
     *
     * @param int $val A number between 1 and 5
     * @return Email
     */
    public function with_priority( $val )
    {
        $val = (int) $val;
        $val = max(1,min(5,$val));
        $obj = clone $this;
        $obj->_priority = $val;
        return $obj;
    }

    /**
     * Adjust the encode subject flag.
     *
     * @param bool $flag
     * @return Email
     */
    public function with_encode_subject( $flag )
    {
        $flag = (bool) $flag;
        if( $this->_encode_subject == $flag ) return $this;

        $obj = clone $this;
        $obj->_encode_subject = $flag;
        return $obj;
    }

    /**
     * Adjust the current_admin flag to indicate whether or not this email should be sent to the currently logged in admin
     *
     * @param bool $flag
     * @return Email
     */
    public function with_current_admin( $flag )
    {
        $flag = (bool) $flag;
        if( $this->_to_current_admin == $flag ) return $this;

        $obj = clone $this;
        $obj->_to_current_admin = $flag;
        return $obj;
    }

    /**
     * Adjust the flag indicating whether or not this email should be sent to the currently logged in FEU user
     * for FEU requests
     *
     * @param bool $flag
     * @return Email
     */
    public function with_current_feu( $flag )
    {
        $flag = (bool) $flag;
        if( $this->_to_current_feu == $flag ) return $this;

        $obj = clone $this;
        $obj->_to_current_feu = $flag;
        return $obj;
    }

    /**
     * Add an additional email address to send this message to.
     *
     * @param string $addr
     * @return Email
     */
    public function add_address( $addr )
    {
        if( !is_email( $addr ) ) return $this;
        if( in_array( $addr, $this->_to_addr ) ) return $this;

        $obj = clone $this;
        $obj->_to_addr[] = $addr;
        return $obj;
    }

    /**
     * Add an email address to the CC list
     *
     * @param string $addr
     * @return Email
     */
    public function add_cc( $addr )
    {
        if( !is_email( $addr ) ) return $this;
        if( in_array( $addr, $this->_cc_addr ) ) return $this;

        $obj = clone $this;
        $obj->_cc_addr[] = $addr;
        return $obj;
    }

    /**
     * Add an email address to the BCC list
     *
     * @param string $addr
     * @return Email
     */
    public function add_bcc( $addr )
    {
        if( !is_email( $addr ) ) return $this;
        if( in_array( $addr, $this->_bcc_addr ) ) return $this;

        $obj = clone $this;
        $obj->_bcc_addr[] = $addr;
        return $obj;
    }

} // class
