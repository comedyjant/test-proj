<?php 

namespace App\Helpers;

class Helper{

    public static function duration_format($duration, $showSeconds = false) 
    {
        $hours = intval(floor($duration / 3600));
        $minutes = intval(floor(($duration / 60) % 60));
        $seconds = $duration % 60;

        $format = [];
            if($hours > 0) {
            $format[] = 'G\h';
        }

        if(($showSeconds && $seconds > 0) || $minutes > 0) {
            $format[] = 'i\m';
        }

        if($showSeconds && $seconds > 0) {
            $format[] = 's\s';
        }
        
        $format = implode($format, ' ');
        return gmdate($format, $duration);
    }

    /**
     * Automatically applies "p" and "br" markup to text.
     */
    public static function auto_p($str, $p = TRUE, $br = TRUE)
    {
        // Trim whitespace
        if (($str = trim($str)) === '')
            return '';

        // Standardize newlines
        $str = str_replace(array("\r\n", "\r"), "\n", $str);

        // Trim whitespace on each line
        $str = preg_replace('~^[ \t]+~m', '', $str);
        $str = preg_replace('~[ \t]+$~m', '', $str);

        // The following regexes only need to be executed if the string contains html
        if ($html_found = (strpos($str, '<') !== FALSE))
        {
            // Elements that should not be surrounded by p tags
            $no_p = '(?:p|div|h[1-6r]|ul|ol|li|blockquote|d[dlt]|pre|t[dhr]|t(?:able|body|foot|head)|c(?:aption|olgroup)|form|s(?:elect|tyle)|a(?:ddress|rea)|ma(?:p|th))';

            // Put at least two linebreaks before and after $no_p elements
            $str = preg_replace('~^<'.$no_p.'[^>]*+>~im', "\n$0", $str);
            $str = preg_replace('~</'.$no_p.'\s*+>$~im', "$0\n", $str);
        }

        $tag = '<p>';

        if($p) {
            $str = '<p>'.trim($str).'</p>';
            $str = preg_replace('~\n{2,}~', "</p>\n\n<p>", $str);
        } else {
            $tag = '<span class="line-break"></span>';
            $str = preg_replace('~\n{2,}~', $tag."\n\n", $str);
        }

        // The following regexes only need to be executed if the string contains html
        if ($html_found !== FALSE)
        {
            // Remove p tags around $no_p elements
            $str = preg_replace('~'.$tag.'(?=</?'.$no_p.'[^>]*+>)~i', '', $str);
            $str = preg_replace('~(</?'.$no_p.'[^>]*+>)'.$tag.'~i', '$1', $str);
        }

        //trim trailing newlines
        $str = trim($str);

        // Convert single linebreaks to <br />
        if ($br === TRUE)
        {
            $str = preg_replace('~(?<!\n)\n(?!\n)~', "<br />\n", $str);
        }

        return $str;
    }

    public static function clear_p($str) {
        $str = preg_replace('/\<br(\s*)?\/?\>/i', "", $str);
        $str = preg_replace('/\<\/?p(\s*)?\>/i', "", $str);
        $str = preg_replace('/\<span .*?class="(.*?line-break.*?)">(.*?)<\/span>/','', $str);
        return $str;
    }

    public static function filter_tags($str){
        $allowedTags = '<p><br><span><strong><em><i><font>'.
                        '<ol><ul><li>'.
                        '<table><thead><tbody><th><tr><td>'.
                        '<blockquote><a><img><iframe><hr><h1><h2><h3><h4><h5><div><video>';
        return strip_tags($str, $allowedTags);
    }

}