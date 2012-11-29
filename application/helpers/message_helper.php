<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Function is used to put some message to flush session, in order to display
 * it at the first available page (show_messages_from_session() is ised)
 *
 * @access  public
 * @param   string  $data
 * @param   string  $type (ok, info, warning, error)
 * @param   bool    $escape
 * @return  bool
 */
function put_message_to_session($data, $type='info', $escape=TRUE)
{
    $CI =& get_instance();
    $msg_array = $CI->session->userdata('messages');
    if (!is_array($msg_array))
        $msg_array = Array();
    array_push($msg_array, Array(
        'data'=>$data,
        'type'=>$type,
        'escape'=>$escape
        ));
    $CI->session->set_userdata('messages', $msg_array);
    return TRUE;
}

/**
 * Function returns the HTML of the stored in session messages
 *
 * @access  public
 * @param   void
 * @return  string $html
 */
function show_messages_from_session()
{
    $CI =& get_instance();
    $html = "";
    $msg_array = $CI->session->userdata('messages');
    if (is_array($msg_array))
    {
        foreach($msg_array as $msg)
        {
            $msg['type'] = 'show_' . $msg['type'] . '_message';
            if ( function_exists($msg['type']) AND is_callable($msg['type']) )
                $html .= call_user_func($msg['type'], $msg['data'], $msg['escape']);
        }
        // remove all shown messages from session
        clear_messages_from_session();
    }
    return $html;
}

function clear_messages_from_session()
{
    $CI =& get_instance();
    $CI->session->unset_userdata('messages');
}

function show_ok_message($data, $escape = TRUE)
{
    $html = '<div class="infoblock ok">'.prepare_text_for_message($data,$escape).'</div>';
    return $html;
}

function show_info_message($data, $escape = TRUE)
{
    $html = '<div class="infoblock info">'.prepare_text_for_message($data,$escape).'</div>';
    return $html;
}

function show_warning_message($data, $escape = TRUE)
{
    $html = '<div class="infoblock warning">'.prepare_text_for_message($data,$escape).'</div>';
    return $html;
}

function show_error_message($data, $escape = TRUE)
{
    $html = '<div class="infoblock error">'.prepare_text_for_message($data,$escape).'</div>';
    return $html;
}

function prepare_text_for_message($data, $escape = TRUE)
{
    $html = "";
    if (is_array($data))
    {
        foreach($data as $key=>$value)
        {
            $html .= $escape?htmlspecialchars($value):$value . "<br/>";
        }
    }
    else
    {
        $html .= $escape?htmlspecialchars($data):$data;
    }
    return $html;
}

?>