<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * base_url
 *
 * This function OVERRIDES the current
 * CodeIgniter base_url function to support
 * CDN'ized content.
 */
function base_url($uri = '')
{
   $CI =& get_instance();

   $cdn = $CI->config->item('cdn_url');
   if (!empty($cdn))
      return $cdn . $uri;

   return $CI->config->base_url($uri);
}

/*
 * is_active
 * Allows a string input that is
 * delimited with "/". If the current
 * params contain what is currently
 * being viewed, it will return true
 *
 * This function is order sensitive.
 * If the page is /view/lab/1 and you put
 * lab/view, this will return false.
 */
function is_active($input_params = "")
{
   // uri_string is a CodeIgniter function
   $uri_string = uri_string();

   // direct matching, faster than looping.
   if ($uri_string == $input_params)
      return true;

   $uri_params = preg_split("/\//", $uri_string);
   $input_params = preg_split("/\//", $input_params);

   $prev_key = -1;
   foreach ($input_params as $param)
   {
      $curr_key = array_search($param, $uri_params);

      // if it doesn't exist, return null
      if ($curr_key === FALSE)
         return false;

      // this makes us order sensitive
      if ($curr_key < $prev_key)
         return false;

      $prev_key = $curr_key;
   }

   return true;
}


if ( ! function_exists('fnAnchor')){
    function fnAnchor($uri = '', $title = '', $attributes = '', $inner_html =''){
        if(!is_active($uri)){
            return  '<li>'.anchor($uri,$title,$attributes,$inner_html).'</li>';
        }else{
            return  '<li class="active">'.anchor($uri,$title,$attributes,$inner_html).'</li>';
        }
    }
}

if ( ! function_exists('anchor'))
{
    function anchor($uri = '', $title = '', $attributes = '', $inner_html = '')
    {
        $title = (string) $title;
        $inner_html = (string) $inner_html;
        if ( ! is_array($uri))
        {
            $site_url = ( ! preg_match('!^\w+://! i', $uri)) ? site_url($uri) : $uri;
        }
        else
        {
            $site_url = site_url($uri);
        }
        if ($title == '')
        {
            $title = $site_url;
        }
        if ($attributes != '')
        {
            $attributes = _parse_attributes($attributes);
        }
        return '<a href="'.$site_url.'"'.$attributes.'>'.$inner_html.$title.'</a>';
    }
}

/*
 * get_controller()
 * get_function()
 * get_parameters()
 *
 * These functions help split out
 * the three different params in the
 * URL.
 *
 * The URL is split in such a way where
 * controller/function/parameters[/]...
 */
function get_controller()
{
   $uri_string = uri_string();

   if (empty($uri_string))
      return $route['default_controller'];

   return preg_split("/\//", $uri_string, 1);
}

function get_function()
{
   $uri_string = uri_string();

   if (empty($uri_string))
      return $route['default_controller'];

   $uri_array = preg_split("/\//", $uri_string, 2);

   if (empty($uri_array[1]))
      return 'index';

   return $uri_array[1];
}

function get_parameter($n = -1)
{
   $param = get_parameters(false);
   if ($n < 0) return $param;

   if (empty($param[$n])) return null;
   return $param[$n];
}

function get_parameters($implode = true)
{
   $uri_string = uri_string();

   if (empty($uri_string))
      return null;

   $uri_array = preg_split("/\//", $uri_string);

   if (empty($uri_string[2]))
      return null;

   $uri_array = array_slice($uri_array, 2);

   if (!$implode)
      return $uri_array;

   return implode("/", $uri_array);
}


/**
 * Builds the full URL in CodeIgniter based on parameters passed
 * (takes care of trailing slashes too)
 *
 * @access  public
 * @param   string  $relative_url - relative part of URL, could countain slashes
 * @param   mixed   $args   - any number of additional string parameters or Array(), that will be appended to the end of the relative URL
 * @return  string  $full_url without trailing slash
 */
function buildurl($relative_url = "", $args = NULL)
{
    $url = base_url();        //get the base URL
    // Force remove of a slash on the end of the URL
    $url = rtrim($url, '/');
    $relative_url = rtrim($relative_url, '/');
    // append relative url
    if (!empty($relative_url))
        $url.= '/'.$relative_url;
    // if there was some args passed - append them to the end of the url
    if ($args !== NULL AND !is_array($args))      // if $args is the single string
        $url .= '/'.urlencode($args);
    elseif ($args !== NULL AND is_array($args))   // if $args is array
    {
        foreach ($args as $arg)
            $url .= '/'.urlencode($arg);
    }
    $numargs = func_num_args();
    if ($numargs >= 2) {
        $arg_list = func_get_args();
        for ($i = 2; $i < $numargs; $i++)
        {
            $url .= '/'.urlencode($arg_list[$i]);
        }
    }
    return $url;
}

?>