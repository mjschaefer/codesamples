<?php defined('SYSPATH') OR die('No direct access allowed.');

class Page_Controller extends Template_Controller {
  
  // Let's set our template and tell it to render after the action is done.
  public $template = 'layouts/front';
  public $auto_render = true;
  
  // CONSTRUCT!
  public function __construct()
  {
    parent::__construct();
    
    $this->session = Session::instance();
  }
  
  public function index($url = '')
  {
    // Grab the Town Alerts
	  $alert = ORM::factory('alert')->find();
	  
	  // Try to grab our page.
	  $page = ORM::factory('page')->where('url', $url)->find();
	  
	  if($page->loaded == true)
	  {
	    // Setup our view.
  		$view = new View('page');
  		$view->page = $page;

  		// Set the title and send our view to the template.
  		$this->template->page_title = $page->title;
  		$this->template->alert = $alert;
  		$this->template->content = $view;
	  }
	  else
	  {
	    url::redirect('/');
	  }
  }
  
} // End Page Controller