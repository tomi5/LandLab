<?php
if( !isset($gCms) ) exit;

// download an image... or a file, or dump an image to stdout
// just with obfuscation so that the filename and path are not obvious.
$params = \cge_utils::decrypt_params( $params );
$file = \cge_param::get_string( $params, 'file' );
$dl = \cge_param::get_bool( $params, 'download' );
$nocache = \cge_param::get_bool( $params, 'nocache' );
if( $file && is_file( $file ) ) {
    $mime_type = \cge_utils::get_mime_type( $file );
    if( ! startswith( $mime_type, 'image/') ) $dl = true;
    if( $dl ) \cge_utils::send_file_and_exit( $file );

    // its an image.
    // so just passthrough the image
    if( $nocache ) {
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private',false);
    }
    header('Content-type: '.$mime_type);
    header('Content-Length: ' . filesize($file));

    $chunksize = 65535;
    $handle=fopen($file,'rb');
    $contents = '';
    do {
        $data = fread($handle,$chunksize);
        if( strlen($data) == 0 ) break;
        print($data);
    } while(true);
    fclose($handle);
}
exit();