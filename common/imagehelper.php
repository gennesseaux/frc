<?php
	/**
	 * PNG ALPHA CHANNEL SUPPORT for imagecopymerge();
	 * by Sina Salek
	 *
	 * Bugfix by Ralph Voigt (bug which causes it
	 * to work only for $src_x = $src_y = 0.
	 * Also, inverting opacity is not necessary.)
	 * 08-JAN-2011
	 *
	 **/
    function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct){
        // creating a cut resource
        $cut = imagecreatetruecolor($src_w, $src_h);

        // copying relevant section from background to the cut resource
        imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
       
        // copying relevant section from watermark to the cut resource
        imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
       
        // insert cut resource to destination image
        imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
    }
?>
