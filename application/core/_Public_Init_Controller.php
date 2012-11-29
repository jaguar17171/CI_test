<?php

class _Public_Init_Controller extends _Init_Controller
{
    public function __construct()
    {
        parent::__construct();
        // init the the path to the user views
        $this->layout_data['_theme'] = "public/";
        // user view specific CSS files
        //$this->layout_data['header']['files']['css'][] = base_url() . 'css/user.css';

        // already logged in?
        $this->layout_data['logged_in'] = $this->ion_auth->logged_in();
        if ($this->layout_data['logged_in'])
        {
            $identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));
            $this->layout_data['logged_in_user'] = $identity;
            $this->layout_data['is_admin'] = $this->ion_auth->is_admin();
        }
        log_message('debug', '_Public_Init_Controller Class Initialized');
    }


}

?>