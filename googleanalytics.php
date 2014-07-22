<?php

/**
 *  Google Analytics
 *
 *   Google's Mobile Analytics was deprecated.
 *   Rewriting it as per the guidelines here: http://goo.gl/ii3Bvl  
 */

class Ga {
    public static function hit($account_id,$switch = false) {

    // Your UA code
    // UA-********-*
    $GA_ACCOUNT = $account_id;

    /**
     *  unique identifier for the current user if HTTP_X_MXIT_USERID_R is not found
     *  http://goo.gl/EeHnoo
     */

    $UID = "";


    /**
     * Switch
     *
     * true
     *
     *  Uses `file_get_contents`
     *  Make sure your server has `allow_url_fopen` turned on.
     *
     * false
     *
     *  Uses `exec` & `cURL`
     *  Make sure your server has `php_curl` installed and on.
     */
    $BLOCKING_REQUEST = $switch;


    // The current page URL
    $PAGE = $_SERVER["REQUEST_URI"];

    // GA's URL to POST to
    $GA_URL = "http://www.google-analytics.com/collect";


    // MXit specific overwrites
    $UID = isset($_SERVER["HTTP_X_MXIT_USERID_R"]) ? md5($_SERVER["HTTP_X_MXIT_USERID_R"]) : uniqid();
    $UA  = isset($_SERVER["HTTP_X_DEVICE_USER_AGENT"]) ? $_SERVER["HTTP_X_DEVICE_USER_AGENT"] : $_SERVER['HTTP_USER_AGENT'];
    $MXIT_PIXELS = isset($_SERVER["HTTP_UA_PIXELS"]) ? $_SERVER["HTTP_UA_PIXELS"] : '';
    $DR = (isset($_SERVER["HTTP_REFERER"])) ? $_SERVER["HTTP_REFERER"] : "-";
    $ip = "127.0.0.1";
    if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
                $ip = array_shift(explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']));
            else
                $ip = $_SERVER["REMOTE_ADDR"];


    if ($BLOCKING_REQUEST) {

      $data = array(
        'payload_data' => '',
        'v'     => '1',
        't'     => 'pageview',
        'dp'    => $PAGE,
        'tid'   => $GA_ACCOUNT,
        'dr'    => $DR,
        'cid'   => $UID,
        'uid'   => $UID,
        'ua'    => $UA,
        'uip'   => $ip,
        'sr'    => $MXIT_PIXELS
      );

      $options = array(
          'http' => array(
              'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
              'method'  => 'POST',
              'content' => http_build_query($data),
          ),
      );

      $context  = stream_context_create($options);

      // Hit!
      echo file_get_contents($GA_URL, false, $context);


    } else {

      $url = "$GA_URL?payload_data&v=1&t=pageview&uip=$ip&dp=" .
              urlencode($PAGE) . "&tid=$GA_ACCOUNT&dr=" .
              urlencode($DR)   . "&cid=$UID&uid=$UID&ua=" .
              urlencode($UA)   . "&sr=$MXIT_PIXELS";

      $cmd = "curl '" . $url . "' > /dev/null 2>&1 &";

      // Hit!
      exec($cmd, $output, $exit);

    }
  }
}
?>