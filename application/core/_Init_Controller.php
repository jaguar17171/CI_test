<?php
// Define Ajax Request
define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
define('SITE_TITLE', 'WebApplication');

class _Init_Controller extends CI_Controller{
    public $layout_data = Array();

    public function __construct()
    {
        parent::__construct();
        $this->init_template_vars();

        /* init ION Auth lib */
        // Load MongoDB library instead of native db driver if required
        $this->config->item('use_mongodb', 'ion_auth') ?
            $this->load->library('mongo_db') :
            $this->load->database();
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        /* END of init ION Auth lib */

        /* ENABLE PROFILER? */
        if ( ! $this->is_ajax())
        {
            if (@parse_url(base_url(),PHP_URL_HOST)=='local.dev')
            {
                $this->output->enable_profiler(TRUE);
            }
            /*$sections = array(
                //'config'  => TRUE,
                //'queries' => TRUE,
            );
            $this->output->set_profiler_sections($sections);*/
        }
        log_message('debug', '_Init_Controller Class Initialized');
    }

    /**
     * Initializes the basic template variables for rendering views
     *
     * @access  private
     * @param   void
     * @return  void
     * @author  unknown
     */
    private function init_template_vars()
    {
        $base_url = base_url();
        // what theme to use
        $this->layout_data['_theme'] = "user/";
        //basic info for the header
        $this->layout_data['header'] = Array();
        $this->layout_data['header']['title'] = "";
        $this->layout_data['header']['description'] = "Site Description - " . SITE_TITLE;
        // load the css/js files to the header
        $this->layout_data['header']['files'] = Array();
        $this->layout_data['header']['files']['css'] = Array();
        $this->layout_data['header']['files']['js'] = Array();

        // css files list
        $css = array(
                // licensed libraries
                'bootstrap.min',
                'bootstrap-responsive.min',
                // END licensed libraries
                'main'
            );
        // js files list
        // external sources for JS files and CDNs
        $js_ext = array(
                'http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js',
                'http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js'
            );
        // local JS resources (pulled from /js/)
        $js = array(
                'bootstrap.min',
                'jquery.blockUI',
                'init'
            );
        foreach ($css as $css_filename)
            $this->layout_data['header']['files']['css'][] = $base_url . 'css/' . $css_filename . '.css';
        foreach ($js_ext as $js_filename)
            $this->layout_data['header']['files']['js'][] = $js_filename;
        foreach ($js as $js_filename)
            $this->layout_data['header']['files']['js'][] = $base_url . 'js/' . $js_filename . '.js';

        // store the state of what CSS and JS files are loaded on the main page load, this is needed for the $this->render_view() while AJAX call
        $this->layout_data['header']['files_init_state'] =  $this->layout_data['header']['files'];

        // set the global JS variables in the header
        $this->layout_data['js_vars'] = Array(
            "base_url" => $base_url,
            "images_url" => $base_url . "img",
            //"arr_example" => Array("name1"=>"val1", "name2"=>"val2") // just for example
        );
        //set the data to footer
        $this->layout_data['footer'] = Array();
        $this->layout_data['footer']['copyright'] = "";

        //here will be rendered the main page content from the handling controller
        $this->layout_data['content'] = "";

        // set global constants
        $this->load->library('user_agent');
        DEFINE('IS_ROBOT', $this->agent->is_robot());
    }

    /**
     * Function checks if it was AJAX request
     *
     * @access  public
     * @param   void
     * @return  bool
     * @author  unknown
     */
    public function is_ajax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH'])&&($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') ? TRUE : FALSE;
    }

    /**
     * Renders the whole page from the views and variables passed.
     * Uses $this->load->view('_template', $this->layout_data) to render.
     * If there was AJAX query - renders not the whole page, but only main content, that is $this->layout_data['content']
     *
     * @access  public
     * @param   void
     * @return  void
     * @author  unknown
     */
    public function render_view()
    {
        $this->layout_data['header']['title'] = $this->layout_data['header']['title'] . (empty($this->layout_data['header']['title']) ? "" : " - ") . SITE_TITLE;
        if (!$this->is_ajax())  // if not ajax call - render the whole view
            $this->load->view('_template', $this->layout_data);
        else
        {
            // find new binded css and js files and load only newly added files
            $this->layout_data['header']['files']['js'] = array_diff_assoc($this->layout_data['header']['files']['js'], $this->layout_data['header']['files_init_state']['js']);
            $this->layout_data['header']['files']['css'] = array_diff_assoc($this->layout_data['header']['files']['css'], $this->layout_data['header']['files_init_state']['css']);
            if (count($this->layout_data['header']['files']['css']) OR count($this->layout_data['header']['files']['js']))
            {
                echo "<script type=\"text/javascript\">\n\r$(document).ready( function() {";
                foreach ($this->layout_data['header']['files']['css'] as $css_file)
                    echo "  loadFile(\"$css_file\", \"css\");\n\r";
                foreach ($this->layout_data['header']['files']['js'] as $js_file)
                    //echo "<script type=\"text/javascript\" src=\"$js_file\" ></script>\n\r";
                    echo "  loadFile(\"$js_file\", \"js\");\n\r";
                echo "});</script>\n\r";
            }
            echo $this->layout_data['content'];
        }
    }
}

?>