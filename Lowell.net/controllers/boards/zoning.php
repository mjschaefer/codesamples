<?php defined('SYSPATH') OR die('No direct access allowed.');

class Zoning_Controller extends Template_Controller {
  
  // Let's set our template and tell it to render after the action is done.
  public $template = 'layouts/front';
  public $auto_render = true;
  
  // CONSTRUCT!
  public function __construct()
  {
    parent::__construct();
    
    $this->session = Session::instance();
  }
  
  public function index($url = 'board_of_zoning_appeals')
  {
    // Grab the Town Alerts
	  $alert = ORM::factory('alert')->find();
	  
	  // Grab links for submenu.
	  $page_list = ORM::factory('page')->select('title, url')->where('section', 'zoning')->orderby('order', 'ASC')->find_all();
	  
	  // Try to grab our page.
	  $page = ORM::factory('page')->where( array('section' => 'zoning', 'url' => $url) )->find();
	  
	  if($page->loaded == true)
	  {
	    // Setup our view.
  		$view = new View('zoning');
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
	    $page = ORM::factory('page')->where( array('section' => 'zoning', 'url' => 'board_of_zoning_appeals') )->find();
	    
	    // Setup our view.
  		$view = new View('zoning');
  		$view->page = $page;
  		$view->page_list = $page_list;

  		// Set the title and send our view to the template.
  		$this->template->page_title = $page->title;
  		$this->template->alert = $alert;
  		$this->template->content = $view;
	  }
  }
  
  public function reports($year = '', $type = '')
  {
    // Grab the Town Alerts
	  $alert = ORM::factory('alert')->find();
	  
	  // Grab links for submenu.
	  $page_list = ORM::factory('page')->select('title, url')->where('section', 'zoning')->orderby('order', 'ASC')->find_all();
	  
	  // See if we can grab a report list of the specified year
	  $reports = ORM::factory('report')->where( array('section' => 'zoning', 'year' => $year, 'type' => $type) )->orderby('date', 'DESC')->find_all();
	  
	  if( count($reports) > 0 )
	  {
	    $view = new View('zoning_reports_list');
	    $view->reports = $reports;
	    $view->page_list = $page_list;
	    $view->title = $year . ' ' . ucwords($type);
	    
	    $this->template->page_title = $year . ' ' . ucwords($type);
	    $this->template->alert = $alert;
	    $this->template->content = $view;
	  }
	  else
	  {
	    $minute_years = ORM::factory('report')->select('DISTINCT ' . 'year')->where( array('section' => 'zoning', 'type' => 'minutes') )->orderby('year', 'DESC')->find_all();
	    $agenda_years = ORM::factory('report')->select('DISTINCT ' . 'year')->where( array('section' => 'zoning', 'type' => 'agendas') )->orderby('year', 'DESC')->find_all();
	    
	    $view = new View('zoning_reports');
	    $view->minute_years = $minute_years;
	    $view->agenda_years = $agenda_years;
	    $view->page_list = $page_list;
	    
	    $this->template->page_title = 'Board of Zoning Appeals Minutes and Agendas';
	    $this->template->alert = $alert;
	    $this->template->content = $view;
	  }
  }
  
  public function minutes($month, $day, $year)
  {
    // Grab the Town Alerts
	  $alert = ORM::factory('alert')->find();
	  
	  // Grab links for submenu.
	  $page_list = ORM::factory('page')->select('title, url')->where('section', 'zoning')->orderby('order', 'ASC')->find_all();
    
    $minutes = ORM::factory('report')->where( array('section' => 'zoning', 'type' => 'minutes', 'month' => $month, 'day' => $day, 'year' => $year) )->find();
    
    if($minutes->loaded == false)
    {
      url::redirect('boards/zoning');
    }
    else
    {
      $view = new View('zoning_minutes');
      $view->minutes = $minutes;
      $view->page_list = $page_list;
      
      $this->template->page_title = date("F d, Y", $minutes->date);
      $this->template->alert = $alert;
      $this->template->content = $view;
    }
  }
  
  public function agendas($month, $day, $year)
  {
    // Grab the Town Alerts
	  $alert = ORM::factory('alert')->find();
	  
	  // Grab links for submenu.
	  $page_list = ORM::factory('page')->select('title, url')->where('section', 'zoning')->orderby('order', 'ASC')->find_all();
    
    $agenda = ORM::factory('report')->where( array('section' => 'zoning', 'type' => 'agendas', 'month' => $month, 'day' => $day, 'year' => $year) )->find();
    
    if($agenda->loaded == false)
    {
      url::redirect('boards/zoning');
    }
    else
    {
      $view = new View('zoning_agenda');
      $view->agenda = $agenda;
      $view->page_list = $page_list;
      
      $this->template->page_title = date("F d, Y", $agenda->date);
      $this->template->alert = $alert;
      $this->template->content = $view;
    }
  }
  
} // End Annexation Controller