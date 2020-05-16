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
 * Defines an object for loading and saving email objects from file.
 *
 * @package CGExtensions\Email
 * @category Utilities
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2018 by Robert Campbell
 */

/**
 * This class can load and save email objects to the filesystem in a specialized format.
 * It will also search in the module directory, and in the /asssets/emails directory for emails.
 *
 * @package CGExtensions\Email
 * @category Utilities
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2018 by Robert Campbell
 */
class FileEmailStorage implements iEmailStorage
{
    /**
     * @ignore
     */
    private $_mod;

    /**
     * Constructor
     *
     * @param \CGExtensions $mod A reference to a module derived from CGExtensions.
     */
    public function __construct( \CGExtensions $mod )
    {
        $this->_mod = $mod;
    }

    /**
     * Load an email object from a file.
     *
     * @param string $name the object name
     * @return Email|null
     */
    public function load( $name )
    {
        $orig_name = $name = trim( $name );
        if( !$name ) throw new \InvalidArgumentException( 'Invalid name passed to '.__METHOD__);
        if( !endswith( $name, '.eml' ) ) $name .= '.eml';

        $config = \cms_utils::get_config();
        $dirs = [ $config['assets_path'].'/emails', $this->_mod->GetModulePath().'/emails' ];
        $filename = null;
        foreach( $dirs as $dir ) {
            $fn = $dir."/$name";
            if( is_file( $fn ) ) {
                $filename = $fn;
                break;
            }
        }
        if( !$filename ) return;

        // file is divided into 3 sections separated by a line beginning and ending with at lesast 4 = characters.
        $part = 0;
        $ini = $subj = $body = null;
        $fh = fopen( $filename, 'r' );
        while( !feof( $fh ) ) {
            $line = fgets( $fh );
            $line = trim($line);
            if( startswith( $line, '====') && endswith( $line, '====') ) {
                // new part.
                $part++;
                continue;
            }
            switch( $part ) {
            case 0: // ini
                $ini .= $line."\n";
                break;
            case 1: // subject template
                $subj .= $line."\n";
                break;
            case 2: // body template
                $body .= $line."\n";
                break;
            }
        }

        if( !$ini ) throw new \RuntimeException('Invalid eml file data: No INI Section');
        $body = trim($body);
        $subj = trim($subj);
        if( $subj && !$body ) $body = $subj; // no subject supplied
        if( !$body ) throw new \RuntimeException('Invalid eml file data: No valid body section');

        $data = parse_ini_string( $ini );
        if( !$data || !is_array($data) || !count($data) ) throw new \RuntimeException('Invalid eml file data: invalid IMI secion');
        $data['name'] = $orig_name;

        if( $subj ) $data['subj_tpl'] = $subj;
        $data['body_tpl'] = $body;
        $email = Email::from_array( $data );
        return $email;
    }

    /**
     * Save an email object to a file.
     *
     * Will ONLY save email files to the /assets/emails directory.
     *
     * @param Email $eml The object to save.
     */
    public function save( Email $eml )
    {
        die('incomplete at '.__FILE__.'::'.__LINE__);
    }
} // class