<?php
namespace CGExtensions;

/**
 * looks for files ONLY in the module path(s).  Ignores module_custom
 */
class FileTemplateResource extends \CMS_Fixed_Resource_Custom
{
    protected function fetch($name,&$source,&$mtime)
    {
        // FORMAT: {include file='cg_modfile:ModuleName;TemplateName.tpl'}
        $module_name = $tpl_name = null;
        $parts = explode(';',$name);
        if( count($parts) < 2 ) return;
        $module_name = trim($parts[0]);
        $tpl_name = trim($parts[1]);
        if( !$module_name || !$tpl_name ) return;

        $mod = \cms_utils::get_module( $module_name );
        if( !$mod ) return;

        $path = $mod->GetModulePath().'/templates';
        $filename = $path.'/'.$tpl_name;
        if( !is_file($filename) ) return;

        $source = file_get_contents( $filename );
        $mtime  = filemtime( $filename );
    }
} // end of class
