<?php defined('SYSPATH') OR die('No direct access allowed.');

class Sewer_Controller extends Template_Controller {
  
  // Let's set our template and tell it to render after the action is done.
  public $template = 'layouts/front';
  public $auto_render = true;
  
  // CONSTRUCT!
  public function __construct()
  {
    parent::__construct();
    
    $this->session = Session::instance();
  }
  
  public function index($url = 'sewer_department')
  {
    // Grab the Town Alerts
	  $alert = ORM::factory('alert')->find();
	  
	  // Grab links for submenu.
	  $page_list = ORM::factory('page')->select('title, url')->where('section', 'sewer')->orderby('order', 'ASC')->find_all();
	  
	  // Try to grab our page.
	  $page = ORM::factory('page')->where( array('section' => 'sewer', 'url' => $url) )->find();
	  
	  if($page->loaded == true)
	  {
	    // Setup our view.
  		$view = new View('sewer');
  		$view->page = $page;
  		$view->page_list = $page_list;

  		// Set the title and send our view to the template.
  		$this->template->page_title = $page->title;
  		$this->template->alert = $alert;
  		$this->template->content = $view;
	  }
	  else
	  {
	    // Then get our main court page.
	    $page = ORM::factory('page')->where( array('section' => 'sewer', 'url' => 'sewer_department') )->find();
	    
	    // Setup our view.
  		$view = new View('sewer');
  		$view->page = $page;
  		$view->page_list = $page_list;

  		// Set the title and send our view to the template.
  		$this->template->page_title = $page->title;
  		$this->template->alert = $alert;
  		$this->template->content = $view;
	  }
  }

} // End Court Controller