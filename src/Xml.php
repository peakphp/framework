<?php
/**
 * Simple but very usefull xml class
 * 
 * @author   Francois Lajoie
 * @version  $Id$
 */
class Peak_Xml
{

    /**
     * Orignal file content
     *
     * @var  string
     */
    public static $file_content;

    /**
     * feed content object
     *
     * @var object
     */
    public static $feed_content;

    /**
     * Timeout for getting the xml file
     *
     * @var integer
     */
    public static $timeout = 4;

    /**
    * Get xml file content and load it as simplexml object
    * 
    * @param string $filepath
    * @param array  $replaces (see _load_and_parse())
    */
    public static function get_content($filepath,$replaces = null)
    {
        $filepath = trim($filepath);

       self::_load_and_parse($filepath,$replaces);
    }

    /**
     * Retreive an external xml with CURL and save to a temp file and load it as simplexml object
     *
     * @param string $filepath
     * @param string $tempfile
     * @param array  $replaces (see _load_and_parse())
     * 
     * @TODO remove call to Peak_Core and let the user specify the path of temp file
     */
    public static function curl_get_content($filepath,$tempfile,$replaces = null)
    {      
        $data = get_url($filepath,0,self::$timeout);
        
        $data = $data[0];
        
        $tempfile = Peak_Core::getPath('cache').'/'.$tempfile;
        
        $fp = fopen($tempfile, "w");
        if($data === false) fwrite($fp,'fail to get '.$filepath);
        else fwrite($fp,$data);
        fclose($fp);
        
        self::_load_and_parse($tempfile,$replaces);
    }

    /**
     * Create a stream context from current $timeout
     *
     * @return object
     */
    protected static function _set_timeout()
    {
        return stream_context_create(array('http' => array('header' => "Accept-language:en\r\n",
                                                                       'timeout' => self::$timeout,
                                                                       'method' => 'GET')));      
    }

    /**
     * Load and parse a local xml as simplexml object
     *
     * @param atring $filepath
     * @param array  $replaces 
     *  simplexml can't parse tag like <content:endcoded> , 
     *  so the trick is to set an array of tag name replacement
     *  ex : array('content:encoded>' => 'content>') why '>' at end?
     *  Simple, its just to be sure we replace tags name and nothing else but
     *  array('content:encoded' => 'content') should give the same result in most case
     */
    protected static function _load_and_parse($filepath,$replaces)
    {
        
        if(self::$feed_content = file_get_contents($filepath,false,self::_set_timeout()))
        {

            if(isset($replaces)) {
                foreach($replaces as $k => $v) {
                    self::$feed_content = str_replace($k,$v,self::$feed_content);
                }
            }
            if(!(self::$feed_content = @simplexml_load_string(self::$feed_content,'SimpleXMLElement', LIBXML_NOCDATA))) self::$feed_content = false;
        }
        else self::$feed_content = false;
    }
}


/**
 * FROM php.net (USEFULL WHEN ALLOW_URL_FOPEN is OFF)
 * Get url content and response headers (given a url, follows all redirections on it and returned content and response headers of final url)
 * @return    array[0]    content
 *            array[1]    array of response headers
 */ 
function get_url( $url,  $javascript_loop = 0, $timeout = 5 )
{
    $url = str_replace( "&amp;", "&", urldecode(trim($url)) );

    $cookie = tempnam ("/tmp", "CURLCOOKIE");
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookie );
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt( $ch, CURLOPT_ENCODING, "" );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
    curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
    curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
    $content = curl_exec( $ch );
    $response = curl_getinfo( $ch );
    curl_close ( $ch );

    if ($response['http_code'] == 301 || $response['http_code'] == 302) {
        ini_set("user_agent", "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");

        if ( $headers = get_headers($response['url']) ) {
            foreach( $headers as $value ) {
                if ( substr( strtolower($value), 0, 9 ) == "location:" )
                return get_url( trim( substr( $value, 9, strlen($value) ) ) );
            }
        }
    }

    if ((preg_match("/>[[:space:]]+window\.location\.replace\('(.*)'\)/i", $content, $value) || preg_match("/>[[:space:]]+window\.location\=\"(.*)\"/i", $content, $value) ) && $javascript_loop < 5) {
        return get_url( $value[1], $javascript_loop+1 );
    }
    else {
        return array( $content, $response );
    }
}