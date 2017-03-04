<?php

namespace Peak\Controller\Helper;

/**
 * Get misc infos about current request
 */
class Request
{
    /**
     * Check if request is ajax
     * Work with jQuery, not tested for other framework
     */
    public function isAjax()
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            return true;
        }
        return false;
    }
    
    /**
     * Get user ip adress
     *
     * @author Yang Yang at http://www.kavoir.com
     */
    public function getIp()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                        return $ip;
                    }
                }
            }
        }
    }

    /**
     * Get parsed $_SERVER['HTTP_ACCEPT_LANGUAGE']
     * 
     * @param  boolean $first_only return the first language only
     * @return array
     */
    public function getLanguages($first_only = false)
    {
        $result = array();

        if (array_key_exists('HTTP_ACCEPT_LANGUAGE', $_SERVER)) {

            $pattern = '/^(?P<tag>[a-zA-Z]{2,8})'.'(?:-(?P<subtag>[a-zA-Z]{2,8}))?(?:(?:;q=)'.'(?P<quantifier>\d\.\d))?$/';

            foreach(explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']) as $lang) {

                if (preg_match($pattern, $lang, $splits)) {
                    foreach ($splits as $k => $v) {
                        if (strlen($k) < 2) unset($splits[$k]);
                    }
                    $result[] = $splits;
                }

                if ($first_only) break;                
            }
        }

        return ($first_only && is_array($result)) ? $result[0] : $result;
    }
}
