<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends _Admin_Init_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        // prepare data for the view
        $content_data = Array();

        // set page title
        $this->layout_data['header']['title'] = 'Dashboard';
        // load the view and pass data array
        $this->layout_data['content'] = $this->load->view($this->layout_data['_theme'].'index', $content_data, TRUE);
        $this->render_view(); // render view and make the output to the browser
    }

}