<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends _Public_Init_Controller {

    function __construct()
    {
        parent::__construct();
    }

    //log the user in
    function login()
    {
        //validate form input
        $this->form_validation->set_rules('identity', 'Identity', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == true)
        {
            //check to see if the user is logging in
            //check for "remember me"
            $remember = (bool) $this->input->post('remember');

            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
            {
                //if the login is successful
                //redirect them back to the home page
                put_message_to_session($this->ion_auth->messages(), 'ok', false);
                redirect('/', 'refresh');
            }
            else
            {
                //if the login was un-successful
                //redirect them back to the login page
                put_message_to_session($this->ion_auth->errors(), 'error', false);
                //redirect('login', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
            }
        }

        //the user is not logging in so display the login page
        //flash data error messages and validation messages will be automatically shown on the view

        $this->data['identity'] = array('name' => 'identity',
            'id' => 'identity',
            'type' => 'text',
            'value' => $this->form_validation->set_value('identity'),
        );
        $this->data['password'] = array('name' => 'password',
            'id' => 'password',
            'type' => 'password',
        );

        // prepare data for the view
        $content_data = $this->data;
        // set page title
        $this->layout_data['header']['title'] = 'Login';
        // load the view and pass data array
        $this->layout_data['content'] = $this->load->view($this->layout_data['_theme'].'login', $content_data, TRUE);
        $this->render_view(); // render view and make the output to the browser

    }

    //log the user out
    function logout()
    {
        //log the user out
        $logout = $this->ion_auth->logout();
        //redirect them to the login page
        put_message_to_session($this->ion_auth->messages(), 'ok', false);
        redirect('login', 'refresh');
    }

    //change password
    function change_password()
    {
        $this->form_validation->set_rules('old', 'Old password', 'required');
        $this->form_validation->set_rules('new', 'New Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
        $this->form_validation->set_rules('new_confirm', 'Confirm New Password', 'required');

        if (!$this->ion_auth->logged_in())
        {
            redirect('login', 'refresh');
        }
        $user = $this->ion_auth->user()->row();
        if ($this->form_validation->run() == false)
        {
            //display the form
            //flash data error messages and validation messages will be automatically shown on the view

            $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
            $this->data['old_password'] = array(
                'name' => 'old',
                'id'   => 'old',
                'type' => 'password',
            );
            $this->data['new_password'] = array(
                'name' => 'new',
                'id'   => 'new',
                'type' => 'password',
                'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
            );
            $this->data['new_password_confirm'] = array(
                'name' => 'new_confirm',
                'id'   => 'new_confirm',
                'type' => 'password',
                'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
            );
            $this->data['user_id'] = array(
                'name'  => 'user_id',
                'id'    => 'user_id',
                'type'  => 'hidden',
                'value' => $user->id,
            );

            // prepare data for the view
            $content_data = $this->data;
            // set page title
            $this->layout_data['header']['title'] = 'Change Password';
            // load the view and pass data array
            $this->layout_data['content'] = $this->load->view($this->layout_data['_theme'].'change_password', $content_data, TRUE);
            $this->render_view(); // render view and make the output to the browser
        }
        else
        {
            $identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));
            $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));
            if ($change)
            {
                //if the password was successfully changed
                put_message_to_session($this->ion_auth->messages(), 'ok', false);
                $this->logout();
            }
            else
            {
                put_message_to_session($this->ion_auth->errors(), 'error', false);
                redirect('auth/change_password', 'refresh');
            }
        }
    }

    //forgot password
    function forgot_password()
    {
        $this->form_validation->set_rules('email', 'Email Address', 'required');
        if ($this->form_validation->run() == false)
        {
            //setup the input
            $this->data['email'] = array('name' => 'email',
                'id' => 'email',
            );
            // flash data error messages and validation messages will be automatically shown on the view
            // prepare data for the view
            $content_data = $this->data;
            // set page title
            $this->layout_data['header']['title'] = 'Forgot Password';
            // load the view and pass data array
            $this->layout_data['content'] = $this->load->view($this->layout_data['_theme'].'forgot_password', $content_data, TRUE);
            $this->render_view(); // render view and make the output to the browser
        }
        else
        {
            //run the forgotten password method to email an activation code to the user
            $forgotten = $this->ion_auth->forgotten_password($this->input->post('email'));

            if ($forgotten)
            {
                //if there were no errors
                put_message_to_session($this->ion_auth->messages(), 'ok', false);
                redirect("login", 'refresh'); //we should display a confirmation page here instead of the login page
            }
            else
            {
                put_message_to_session($this->ion_auth->errors(), 'error', false);
                redirect("auth/forgot_password", 'refresh');
            }
        }
    }

    //reset password - final step for forgotten password
    public function reset_password($code = NULL)
    {
        if (!$code)
            show_404();
        $user = $this->ion_auth->forgotten_password_check($code);
        if ($user)
        {
            //if the code is valid then display the password reset form
            $this->form_validation->set_rules('new', 'New Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', 'Confirm New Password', 'required');

            if ($this->form_validation->run() == false)
            {
                //display the form
                //flash data error messages and validation messages will be automatically shown on the view

                $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
                $this->data['new_password'] = array(
                    'name' => 'new',
                    'id'   => 'new',
                    'type' => 'password',
                    'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
                );
                $this->data['new_password_confirm'] = array(
                    'name' => 'new_confirm',
                    'id'   => 'new_confirm',
                    'type' => 'password',
                    'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
                );
                $this->data['user_id'] = array(
                    'name'  => 'user_id',
                    'id'    => 'user_id',
                    'type'  => 'hidden',
                    'value' => $user->id,
                );
                $this->data['csrf'] = $this->_get_csrf_nonce();
                $this->data['code'] = $code;

                // prepare data for the view
                $content_data = $this->data;
                // set page title
                $this->layout_data['header']['title'] = 'Reset Password';
                // load the view and pass data array
                $this->layout_data['content'] = $this->load->view($this->layout_data['_theme'].'reset_password', $content_data, TRUE);
                $this->render_view(); // render view and make the output to the browser
            }
            else
            {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
                {
                    //something fishy might be up
                    $this->ion_auth->clear_forgotten_password_code($code);
                    show_error('This form post did not pass our security checks.');
                }
                else
                {
                    // finally change the password
                    $identity = $user->{$this->config->item('identity', 'ion_auth')};

                    $change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

                    if ($change)
                    {
                        //if the password was successfully changed
                        put_message_to_session($this->ion_auth->messages(), 'ok', false);
                        //$this->logout();
                    }
                    else
                    {
                        put_message_to_session($this->ion_auth->errors(), 'error', false);
                        redirect('auth/reset_password/' . $code, 'refresh');
                    }
                }
            }
        }
        else
        {
            //if the code is invalid then send them back to the forgot password page
            put_message_to_session($this->ion_auth->errors(), 'error', false);
            redirect("auth/forgot_password", 'refresh');
        }
    }

    function register()
    {
        /* register functionality is disabled */
        //show_error('Users registration is unavailable. Please contact website administration for more details.<br/><a href="'.buildurl().'">Go to home page.</a>', $status_code = 403, 'Forbidden');
        /* END register functionality is disabled */

        if ($this->ion_auth->logged_in())
        {
            redirect(base_url(), 'refresh');
        }

        //validate form input
        $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
        $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');

        if ( $this->input->post('phone') )
        {
            $_POST['phone'] = $this->_check_and_prepare_phone_number($this->input->post('phone'));
        }

        if ($this->form_validation->run() == true)
        {
            $username = strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name'));
            $email    = $this->input->post('email');
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name')
            );
        }
        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data))
        {
            //check to see if we created the user
            //redirect them back to the login page
            put_message_to_session($this->ion_auth->messages(), 'ok', false);
            redirect("login", 'refresh');
        }
        else
        {
            //display the create user form
            //flash data error messages and validation messages will be automatically shown on the view
            if ($this->ion_auth->errors())
                put_message_to_session($this->ion_auth->errors(), 'error', false);

            $this->data['first_name'] = array(
                'name'  => 'first_name',
                'id'    => 'first_name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('first_name'),
            );
            $this->data['last_name'] = array(
                'name'  => 'last_name',
                'id'    => 'last_name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('last_name'),
            );
            $this->data['email'] = array(
                'name'  => 'email',
                'id'    => 'email',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('email'),
            );
            $this->data['password'] = array(
                'name'  => 'password',
                'id'    => 'password',
                'type'  => 'password',
                'value' => $this->form_validation->set_value('password'),
            );
            $this->data['password_confirm'] = array(
                'name'  => 'password_confirm',
                'id'    => 'password_confirm',
                'type'  => 'password',
                'value' => $this->form_validation->set_value('password_confirm'),
            );

            // prepare data for the view
            $content_data = $this->data;
            // set page title
            $this->layout_data['header']['title'] = 'Sign Up';
            // load the view and pass data array
            $this->layout_data['content'] = $this->load->view($this->layout_data['_theme'].'register', $content_data, TRUE);
            $this->render_view(); // render view and make the output to the browser
        }
    }

    function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key   = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);
        return array($key => $value);
    }

    function _valid_csrf_nonce()
    {
        if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
            $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

}
