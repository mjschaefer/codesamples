<?php defined('SYSPATH') OR die('No direct access allowed.');

class Courts_Controller extends Template_Controller {
  
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
    
    $pages = ORM::factory('page')->where('section', 'court')->orderby('title', 'DESC')->find_all();
    
    $view = new View('court_page_list');
    $view->pages = $pages;
    
    $this->template->page_title = "Court Pages";
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
        
    $view = new View('add_court_page');
    
    $this->template->page_title = 'Add New Page';
    $this->template->content = $view;
  }
  
  public function create()
  {
    $this->_is_logged_in();
    
    $page = ORM::factory('page');
    
    $page->title = $this->input->post('title');
    $page->url = url::title($page->title, '_');
    $page->section = 'court';
    $page->body = $this->input->post('body');
    
    $page->save();
    
    $this->session->set_flash('notice', 'New Page Created!');
    url::redirect('admin/courts');
  }
  
  public function edit($url)
  {
    $this->_is_logged_in();
        
    $page = ORM::factory('page')->where('url', $url)->find();
    
    $view = new View('edit_court_page');
    $view->page = $page;
    
    $this->template->page_title = 'Edit Page';
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
    
    $this->session->set_flash('notice', 'Page Edited!');
    url::redirect('admin/courts');
  }
  
  public function delete($url)
  {
    $this->_is_logged_in();
    
    $page = ORM::factory('page')->where('url', $url)->delete();
    
    $this->session->set_flash('notice', 'Page Deleted!');
    url::redirect('admin/courts');
  }
  
  function _is_logged_in()
	{
	  if(!$this->session->get('logged_in'))
	  {
	    url::redirect('admin/login');
	  }
	}
  
} // End Courts Controller