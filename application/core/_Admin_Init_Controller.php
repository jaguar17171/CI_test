<?php

class _Admin_Init_Controller extends _Init_Controller
{
    public function __construct()
    {
        parent::__construct();
        //$this->load->model('user');                 // load user model
        // init the the path to the administrating views
        $this->layout_data['_theme'] = "admin/";

        // load additional css files list
        $this->layout_data['header']['files']['css'][] = base_url() . 'css/admin.css';
        // load additional js files list

        // refresh/store the state of what CSS and JS files are loaded on the main page load, needed for the $this->render_view() while AJAX call
        $this->layout_data['header']['files_init_state'] =  $this->layout_data['header']['files'];

         // already logged in and have permissions?
        $this->layout_data['logged_in'] = $this->ion_auth->logged_in();
        if ($this->layout_data['logged_in'] AND $is_admin=$this->ion_auth->is_admin())
        {
            $identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));
            $this->layout_data['logged_in_user'] = $identity;
            $this->layout_data['is_admin'] = $is_admin;
        }
        elseif(!$this->layout_data['logged_in'])
        {
            redirect('login', 'refresh');
        }
        else
        {
            show_error('You don\'t have permission to access ' . current_url() . ' on this server.', $status_code = 403, 'Forbidden');
        }

        log_message('debug', '_Admin_init_Controller Class Initialized');
    }

}

?>