<?php defined('SYSPATH') OR die('No direct access allowed.');

class Calendar_Controller extends Template_Controller {
  
  // Let's set our template and tell it to render after the action is done.
  public $template = 'layouts/front';
  public $auto_render = true;
  
  // CONSTRUCT!
  public function __construct()
  {
    parent::__construct();
    
    $this->session = Session::instance();
  }
  
	public function index($url, $month = '?', $year = '?')
	{
	  $alert = ORM::factory('alert')->find();
	  
	  if($month == '?'){ $month = date('m'); }
	  if($year == '?'){ $year = date('Y'); }
	  
	  $calendar_page = ORM::factory('calendar')->where('url', $url)->find();
	  $events = $calendar_page->where( array('month' => $month, 'year' => $year) )->events;
	  
	  $calendar = new Calendar($month, $year);
	  
	  foreach($events as $event)
	  {
	    $calendar->attach($calendar->event()->condition('year', $event->year)->condition('month', $event->month)->condition('day', $event->day)->output(html::anchor(url::site('calendar/event/' . $event->id), $event->name)));
	  }
	  
	  $view = new View('calendar');
	  $view->calendar_page = $calendar_page;
	  $view->events = $events;
	  $view->calendar = $calendar->render();
	  $view->url = $url;
	  $view->month = $month;
	  $view->year = $year;
	  
	  $this->template->page_title = 'Calendar';
	  $this->template->alert = $alert;
	  $this->template->content = $view;
	}
	
	public function event($id)
	{
	  $alert = ORM::factory('alert')->find();
	  
	  $event = ORM::factory('event')->where('id', $id)->find();
	  $calendar = ORM::factory('calendar', $event->calendar_id)->find();
	  
	  $view = new View('event');
	  $view->event = $event;
	  $view->calendar = $calendar;
	  
	  $this->template->page_title = $event->name;
	  $this->template->alert = $alert;
	  $this->template->content = $view;
	}

} // End Calendar Controller