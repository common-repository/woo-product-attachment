<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

$attachment_id=filter_input(INPUT_GET,'attachment_id',FILTER_SANITIZE_SPECIAL_CHARS);
       
if (isset($attachment_id)) {
   $attID = $attachment_id;
   $theFile = wp_get_attachment_url($attID);
   if (!$theFile) {
       return;
   }
   $upload_dir = wp_upload_dir();
   //clean the fileurl
   $file_url = stripslashes(trim($theFile));
   //get filename
    $files_arr = explode("/uploads",$file_url);
    set_time_limit(0); // disable the time limit for this script
    $attachment_path = $upload_dir['basedir'] . $files_arr[1]; // change the path to fit your websites document structure

    if ( is_multisite() ) {
        $site = get_current_blog_id();
        if( !empty( $site ) ){
            if ( !is_main_site( $site ) ) {
                $files_arr = explode( "sites/" . $site, $file_url );
                $attachment_path = $upload_dir['basedir'] . $files_arr[1]; // change the path to fit your websites document structure   
            }
        }
    }

   $fullPath = $attachment_path;
   $pdf_download_mode = '';
   if(get_option('wcpoa_is_viewable')){
        $wcpoa_is_viewable = get_option( 'wcpoa_is_viewable' );
        if ( 'yes' === $wcpoa_is_viewable ) {
            $pdf_download_mode = "inline";
        } else {
            $pdf_download_mode = "attachment";
        }
   } else{
        $pdf_download_mode = "attachment";
   }

   global $wp_filesystem;
   require_once ( ABSPATH . '/wp-admin/includes/file.php' );
   WP_Filesystem();
   if ( $wp_filesystem->exists( $fullPath ) ) {
       $fsize = filesize($fullPath);
       $path_parts = pathinfo($fullPath);
       $ext = strtolower($path_parts["extension"]);
       switch ($ext) {

            ///Image Mime Types
            case 'jpg':
                $mimetype = "image/jpg";
                break;
            case 'jpeg':
                $mimetype = "image/jpeg";
                break;
            case 'gif':
                $mimetype = "image/gif";
                break;
            case 'png':
                $mimetype = "image/png";
                break;
            case 'bm':
                $mimetype = "image/bmp";
                break;
            case 'bmp':
                $mimetype = "image/bmp";
                break;
            case 'art':
                $mimetype = "image/x-jg";
                break;
            case 'dwg':
                $mimetype = "image/x-dwg";
                break;
            case 'dxf':
                $mimetype = "image/x-dwg";
                break;
            case 'flo':
                $mimetype = "image/florian";
                break;
            case 'fpx':
                $mimetype = "image/vnd.fpx";
                break;
            case 'g3':
                $mimetype = "image/g3fax";
                break;
            case 'ief':
                $mimetype = "image/ief";
                break;
            case 'jfif':
                $mimetype = "image/pjpeg";
                break;
            case 'jfif-tbnl':
                $mimetype = "image/jpeg";
                break;
            case 'jpe':
                $mimetype = "image/pjpeg";
                break;
            case 'jps':
                $mimetype = "image/x-jps";
                break;
            case 'jut':
                $mimetype = "image/jutvision";
                break;
            case 'mcf':
                $mimetype = "image/vasa";
                break;
            case 'nap':
                $mimetype = "image/naplps";
                break;
            case 'naplps':
                $mimetype = "image/naplps";
                break;
            case 'nif':
                $mimetype = "image/x-niff";
                break;
            case 'niff':
                $mimetype = "image/x-niff";
                break;
            case 'cod':
                $mimetype = "image/cis-cod";
                break;
            case 'ief':
                $mimetype = "image/ief";
                break;
            case 'svg':
                $mimetype = "image/svg+xml";
                break;
            case 'tif':
                $mimetype = "image/tiff";
                break;
            case 'tiff':
                $mimetype = "image/tiff";
                break;
            case 'ras':
                $mimetype = "image/x-cmu-raster";
                break;
            case 'cmx':
                $mimetype = "image/x-cmx";
                break;
            case 'ico':
                $mimetype = "image/x-icon";
                break;
            case 'pnm':
                $mimetype = "image/x-portable-anymap";
                break;
            case 'pbm':
                $mimetype = "image/x-portable-bitmap";
                break;
            case 'pgm':
                $mimetype = "image/x-portable-graymap";
                break;
            case 'ppm':
                $mimetype = "image/x-portable-pixmap";
                break;
            case 'rgb':
                $mimetype = "image/x-rgb";
                break;
            case 'xbm':
                $mimetype = "image/x-xbitmap";
                break;
            case 'xpm':
                $mimetype = "image/x-xpixmap";
                break;
            case 'xwd':
                $mimetype = "image/x-xwindowdump";
                break;
            case 'rgb':
                $mimetype = "image/x-rgb";
                break;
            case 'xbm':
                $mimetype = "image/x-xbitmap";
                break;
            case "wbmp":
                $mimetype = "image/vnd.wap.wbmp";
                break;
          
            //Files MIME Types
            
            case 'css':
                $mimetype = "text/css";
                break;
            case 'htm':
                $mimetype = "text/html";
                break;
            case 'html':
                $mimetype = "text/html";
                break;
            case 'stm':
                $mimetype = "text/html";
                break;
            case 'c':
                $mimetype = "text/plain";
                break;
            case 'h':
                $mimetype = "text/plain";
                break;
            case 'txt':
                $mimetype = "text/plain";
                break;
            case 'rtx':
                $mimetype = "text/richtext";
                break;
            case 'htc':
                $mimetype = "text/x-component";
                break;
            case 'vcf':
                $mimetype = "text/x-vcard";
                break;
           
           
            //Applications MIME Types
            
            case 'doc':
                $mimetype = "application/msword";
                break;
            case 'xls':
                $mimetype = "application/vnd.ms-excel";
                break;
            case 'ppt':
                $mimetype = "application/vnd.ms-powerpoint";
                break;
            case 'pps':
                $mimetype = "application/vnd.ms-powerpoint";
                break;
            case 'pot':
                $mimetype = "application/vnd.ms-powerpoint";
                break;
          
            case "ogg":
                $mimetype = "application/ogg";
                break;
            case "pls":
                $mimetype = "application/pls+xml";
                break;
            case "asf":
                $mimetype = "application/vnd.ms-asf";
                break;
            case "wmlc":
                $mimetype = "application/vnd.wap.wmlc";
                break;
            case 'dot':
                $mimetype = "application/msword";
                break;
            case 'class':
                $mimetype = "application/octet-stream";
                break;
            case 'exe':
                $mimetype = "application/octet-stream";
                break;
            case 'pdf':
                $mimetype = "application/pdf";
                break;
            case 'rtf':
                $mimetype = "application/rtf";
                break;
            case 'xla':
                $mimetype = "application/vnd.ms-excel";
                break;
            case 'xlc':
                $mimetype = "application/vnd.ms-excel";
                break;
            case 'xlm':
                $mimetype = "application/vnd.ms-excel";
                break;
           
            case 'msg':
                $mimetype = "application/vnd.ms-outlook";
                break;
            case 'mpp':
                $mimetype = "application/vnd.ms-project";
                break;
            case 'cdf':
                $mimetype = "application/x-cdf";
                break;
            case 'tgz':
                $mimetype = "application/x-compressed";
                break;
            case 'dir':
                $mimetype = "application/x-director";
                break;
            case 'dvi':
                $mimetype = "application/x-dvi";
                break;
            case 'gz':
                $mimetype = "application/x-gzip";
                break;
            case 'js':
                $mimetype = "application/x-javascript";
                break;
            case 'mdb':
                $mimetype = "application/x-msaccess";
                break;
            case 'dll':
                $mimetype = "application/x-msdownload";
                break;
            case 'wri':
                $mimetype = "application/x-mswrite";
                break;
            case 'cdf':
                $mimetype = "application/x-netcdf";
                break;
            case 'swf':
                $mimetype = "application/x-shockwave-flash";
                break;
            case 'tar':
                $mimetype = "application/x-tar";
                break;
            case 'man':
                $mimetype = "application/x-troff-man";
                break;
            case 'zip':
                $mimetype = "application/zip";
                break;
            case 'xlsx':
                $mimetype = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
                break;
            case 'pptx':
                $mimetype = "application/vnd.openxmlformats-officedocument.presentationml.presentation";
                break;
            case 'docx':
                $mimetype = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
                break;
            case 'xltx':
                $mimetype = "application/vnd.openxmlformats-officedocument.spreadsheetml.template";
                break;
            case 'potx':
                $mimetype = "application/vnd.openxmlformats-officedocument.presentationml.template";
                break;
            case 'ppsx':
                $mimetype = "application/vnd.openxmlformats-officedocument.presentationml.slideshow";
                break;
            case 'sldx':
                $mimetype = "application/vnd.openxmlformats-officedocument.presentationml.slide";
                break;
          
            ///Audio and Video Files
            
            case 'mp3':
                $mimetype = "audio/mpeg";
                break;
            case 'wav':
                $mimetype = "audio/x-wav";
                break;
            case 'au':
                $mimetype = "audio/basic";
                break;
            case 'snd':
                $mimetype = "audio/basic";
                break;
            case 'm3u':
                $mimetype = "audio/x-mpegurl";
                break;
            case 'ra':
                $mimetype = "audio/x-pn-realaudio";
                break;
            case 'mp2':
                $mimetype = "video/mpeg";
                break;
            case 'mov':
                $mimetype = "video/quicktime";
                break;
            case 'qt':
                $mimetype = "video/quicktime";
                break;
            case 'mp4':
                $mimetype = "video/mp4";
                break;
            case 'm4a':
                $mimetype = "audio/mp4";
                break;
            case 'mp4a':
                $mimetype = "audio/mp4";
                break;
            case 'm4p':
                $mimetype = "audio/mp4";
                break;
            case 'm3a':
                $mimetype = "audio/mpeg";
                break;
            case 'm2a':
                $mimetype = "audio/mpeg";
                break;
            case 'mp2a':
                $mimetype = "audio/mpeg";
                break;
            case 'mp2':
                $mimetype = "audio/mpeg";
                break;
            case 'mpga':
                $mimetype = "audio/mpeg";
                break;
            case '3gp':
                $mimetype = "video/3gpp";
                break;
            case '3g2':
                $mimetype = "video/3gpp2";
                break;
            case 'mp4v':
                $mimetype = "video/mp4";
                break;
            case 'mpg4':
                $mimetype = "video/mp4";
                break;
            case 'm2v':
                $mimetype = "video/mpeg";
                break;
            case 'm1v':
                $mimetype = "video/mpeg";
                break;
            case 'mpe':
                $mimetype = "video/mpeg";
                break;
            case 'avi':
                $mimetype = "video/x-msvideo";
                break;
            case 'midi':
                $mimetype = "audio/midi";
                break;
            case 'mid':
                $mimetype = "audio/mid";
                break;
            case 'amr':
                $mimetype = "audio/amr";
                break;

            default:
                $mimetype = "application/octet-stream";

       }
       
        header('Content-Description: File Transfer');
        header('Content-Type: '.$mimetype);
        header("Content-Disposition: ".$pdf_download_mode."; filename=\"" . $path_parts["basename"] . "\"");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Pragma: no-cache');
        header("Content-Length: $fsize");
        $chunk = 1 * (1024 * 1024);
        $handle = fopen($fullPath,"rb"); //phpcs:ignore

        while(!feof($handle))
        {
          print(fread($handle, $chunk)); //phpcs:ignore
          ob_flush();
          flush();
        }
        fclose($handle);
        
   }
    exit;
}
