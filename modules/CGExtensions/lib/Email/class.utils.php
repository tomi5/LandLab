<?php
namespace CGExtensions\Email;

final class utils
{
    private function __construct() {}

    public static function personalize_for_admin( Email $eml, EmailDestination $dest )
    {
        // adjusts an email object, adds data given personal data for the admin user
        if( $dest->admin_uid < 1 ) return $eml;

        $ops = \UserOperations::get_instance();
        $user = $ops->LoadUserByID( $dest->admin_uid );
        if( !$user ) return $eml;

        $eml = $eml->add_data( 'admin_username', $user->username );
        $eml = $eml->add_data( 'admin_firstname', $user->firstname );
        $eml = $eml->add_data( 'admin_lastname', $user->lastname );
        $eml = $eml->add_data( 'admin_email', $user->email );
        return $eml;
    }

    public static function personalize_for_feu( Email $eml, EmailDestination $dest )
    {
        if( !$dest->feu_uid ) return $eml;
        $feu = \cms_utils::get_module('FrontEndUsers');
        if( !$feu ) return $eml;

        $user = $feu->GetUserInfo( $dest->feu_uid, TRUE );
        if( !$user || $user[0] == FALSE ) return $eml;
        $user = $user[1];
        $eml = $eml->add_data('feu_username', $user['username']);
        $eml = $eml->add_data('feu_created', $user['createdate']);
        $eml = $eml->add_data('feu_expires', $user['expires'] );
        $eml = $eml->add_data('feu_disabled', $user['disabled']);
        $eml = $eml->add_data('feu_loggedin', $user['loggedin']);
        if( isset( $user['fprops']) ) {
            foreach( $user['fprops'] as $rec ) {
                $eml = $eml->add_data( $rec['title'], $rec['data'] );
            }
        }

        return $eml;
    }
} // class
