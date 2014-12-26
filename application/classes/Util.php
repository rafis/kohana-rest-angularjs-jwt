<?php defined('SYSPATH') OR die();

class Util
{
    
    public static function startsWith($haystack, $needle) {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
    }
    
    public static function endsWith($haystack, $needle) {
        // search forward starting from end minus needle length characters
        return $needle === "" || strpos($haystack, $needle, strlen($haystack) - strlen($needle)) !== FALSE;
    }
 
    public static function simple_parse_str($str, array &$arr = null)
    {
        $pairs = explode('&', $str);
        foreach ( $pairs as $pair )
        {
            $pair = explode('=', $pair, 2);
            $key = $pair[0];
            $value = Arr::get($pair, 1, '');
            $arr[$key] = $value;
        }
    }
    
    /**
     * Taken from http://publicmind.in/blog/url-encoding/
     * @param string $url The URL to encode
     * @return string A string containing the encoded URL with disallowed
     *                characters converted to their percentage encodings.
     */
    public static function encode_url($url)
    {
        $reserved = array(
            ":" => '!%3A!ui',
            "/" => '!%2F!ui',
            "?" => '!%3F!ui',
            "#" => '!%23!ui',
            "[" => '!%5B!ui',
            "]" => '!%5D!ui',
            "@" => '!%40!ui',
            "!" => '!%21!ui',
            "$" => '!%24!ui',
            "&" => '!%26!ui',
            "'" => '!%27!ui',
            "(" => '!%28!ui',
            ")" => '!%29!ui',
            "*" => '!%2A!ui',
            "+" => '!%2B!ui',
            "," => '!%2C!ui',
            ";" => '!%3B!ui',
            "=" => '!%3D!ui',
            "%" => '!%25!ui',
        );

        $url = rawurlencode($url);
        $url = preg_replace(array_values($reserved), array_keys($reserved), $url);
        return $url;
    }
    
}