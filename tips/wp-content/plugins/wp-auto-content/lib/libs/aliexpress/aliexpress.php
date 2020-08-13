<?php
//https://github.com/clchangnet/aliexapi
if (!function_exists('aliexpress_affiliate_check_settings_complete')) {

  function aliexpress_affiliate_check_settings_complete(){

    global $aliexpress_affiliate_options;
    $output = false;
    if (
      ! empty($aliexpress_affiliate_options['api_key'])
      && ! empty($aliexpress_affiliate_options['tracking_id'])
      && ! empty($aliexpress_affiliate_options['digital_signature'])
      && ! empty($aliexpress_affiliate_options['default_category_id'])
      ) {
      $output = true;
    }
    return $output;

  }

}


if (!function_exists('aliexpress_affiliate_generate_transient_key')) {

  function aliexpress_affiliate_generate_transient_key($args=array()){

    $output = 'aea';

    if (isset($args['category']) && ! empty($args['category']) ) {
      $output .= '_'.esc_attr($args['category']);
    }

    if (isset($args['keyword']) && ! empty($args['keyword']) ) {
      $keyword_slug = sanitize_title($args['keyword']);
      $keyword = substr($keyword_slug, 0 , 15);

      $output .= '_'.$keyword;
    }

    return $output;

  }

}



if (!function_exists('aliexpress_affiliate_get_ali_products')) {

  function aliexpress_affiliate_get_ali_products($args=array()){

    global $aliexpress_affiliate_options;

    $output = array();

    $url = 'http://gw.api.alibaba.com/openapi/param2/1/portals.open/api.listPromotionProduct/' . $aliexpress_affiliate_options['api_key'];
    $data = 'access_token=' . $aliexpress_affiliate_options['digital_signature'];
    if (isset($args['category']) && ! empty($args['category']) ) {
      $data .= '&categoryId='. esc_attr($args['category']) ;
    }
    if (isset($args['keyword']) && ! empty($args['keyword']) ) {
      $data .= '&keywords='. esc_attr($args['keyword']) ;
    }
    $data .= '&trackingId='. esc_attr($aliexpress_affiliate_options['tracking_id']) ;

    $ch = curl_init();
    $timeout = 5;
    //curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $re = curl_exec($ch);
    $res = json_decode($re);

    if ( '20010000' != $res->errorCode ) {
      return $output;
    }

    if (isset($res->result->items) && ! empty($res->result->items)) {
      $output = $res->result->items;
    }

    return $output;

  }

}

// if (!function_exists('aliexpress_affiliate_check_params')) {

//   function aliexpress_affiliate_check_params($args){

//     global $aliexpress_affiliate_options;
//     $output = false;

//     return $output;
//   }

// }
