<?php defined('SYSPATH') OR die('No direct access allowed.');

class Admin_Controller extends Template_Controller {
  
  // Let's set our template and tell it to render after the action is done.
  public $template = 'layouts/back';
  public $auto_render = true;
  
  // CONSTRUCT!
  public function __construct()
  {
    parent::__construct();
    
    $this->session = Session::instance();
  }
  
  public function index()
  {
    $this->_is_logged_in();
    
    $this->template->page_title = 'Index';
    $this->template->content = new View('admin');
  }
  
  public function alerts()
  {
    $this->_is_logged_in();
    
    $alert = ORM::factory('alert', 1);
    
    $view = new View('edit_alerts');
    $view->alert = $alert;
    
    $this->template->page_title = 'Change Alerts';
    $this->template->content = $view;
  }
  
  public function update_alerts()
  {
    $this->_is_logged_in();
    
    $alert = ORM::factory('alert', 1);
    
    $alert->warning = $this->input->post('warning');
    $alert->notice = $this->input->post('notice');
    
    $alert->save();
    
    $this->session->set_flash('notice', 'Alerts Updated!');
    url::redirect('admin/alerts');
  }
  
  public function clear_alerts()
  {
    $this->_is_logged_in();
    
    $alert = ORM::factory('alert', 1);
    
    $alert->warning = '';
    $alert->notice = '';
    
    $alert->save();
    
    $this->session->set_flash('notice', 'Alerts Cleared!');
    url::redirect('admin/alerts');
  }
  
  public function login()
  {
    $this->template->page_title = 'Login';
    $this->template->content = new View('login');
  }
  
  public function authorize()
  {
    $username = $this->input->post('username');
    $password = sha1($username . $this->input->post('password'));
    
    $user = ORM::factory('user')->where( array('username' => $username, 'password' => $password) )->find();
    
    if($user->loaded == true)
    {
      $data = array(
        'username' => $user->username,
        'logged_in' => true
        );
      $this->session->set($data);
      
      url::redirect('admin');
    }
    else
    {
      $this->session->set_flash('error', 'Incorrect Username or Password');
      url::redirect('admin/login');
    }
  }
  
  public function logout()
  {
    $this->session->destroy();
    url::redirect('/');
  }
  
  function _is_logged_in()
	{
	  if(!$this->session->get('logged_in'))
	  {
	    url::redirect('admin/login');
	  }
	}
  
} // End Admin Controller