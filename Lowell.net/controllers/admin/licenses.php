<?php defined('SYSPATH') OR die('No direct access allowed.');

class Licenses_Controller extends Template_Controller {
  
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
    
    $pages = ORM::factory('page')->where('section', 'licenses')->orderby('title', 'DESC')->find_all();
    
    $view = new View('licenses_list');
    $view->pages = $pages;
    
    $this->template->page_title = "Annual Licenses";
    $this->template->content = $view;
  }
  
  public function sort()
  {
    $sort = $this->input->post('sort');
    
    foreach($sort as $position => $id)
    {
      $page = ORM::factory('page', $id);
      $page->order = $position;
      $page->save();
    }
    
    exit;
  }
  
  public function add()
  {
    $this->_is_logged_in();
        
    $view = new View('add_license');
    
    $this->template->page_title = 'Add New License';
    $this->template->content = $view;
  }
  
  public function create()
  {
    $this->_is_logged_in();
    
    $page = ORM::factory('page');
    
    $page->title = $this->input->post('title');
    $page->url = url::title($page->title, '_');
    $page->section = 'licenses';
    $page->body = $this->input->post('body');
    
    $page->save();
    
    $this->session->set_flash('notice', 'New License Created!');
    url::redirect('admin/licenses');
  }
  
  public function edit($url)
  {
    $this->_is_logged_in();
        
    $page = ORM::factory('page')->where('url', $url)->find();
    
    $view = new View('edit_license');
    $view->page = $page;
    
    $this->template->page_title = 'Edit License';
    $this->template->content = $view;
  }
  
  public function update()
  {
    $this->_is_logged_in();
        
    $page = ORM::factory('page', $this->input->post('id'));
    
    $page->title = $this->input->post('title');
    $page->url = url::title($page->title, '_');
    $page->body = $this->input->post('body');
    
    $page->save();
    
    $this->session->set_flash('notice', 'License Edited!');
    url::redirect('admin/licenses');
  }
  
  public function delete($url)
  {
    $this->_is_logged_in();
    
    $page = ORM::factory('page')->where('url', $url)->delete();
    
    $this->session->set_flash('notice', 'License Deleted!');
    url::redirect('admin/licenses');
  }
  
  function _is_logged_in()
	{
	  if(!$this->session->get('logged_in'))
	  {
	    url::redirect('admin/login');
	  }
	}
  
} // End Courts Controller