<?php defined('SYSPATH') OR die('No direct access allowed.');

class Calendars_Controller extends Template_Controller {
  
  // Let's set our template and tell it to render after the action is done.
  public $template = 'layouts/back';
  public $auto_render = true;
  
  // CONSTRUCT!
  public function __construct()
  {
    parent::__construct();
    
    $this->session = Session::instance();
  }
  
  public function index($url = '')
  {
    $this->_is_logged_in();
    
    $calendar = ORM::factory('calendar')->where('url', $url)->find();
    
    if($calendar->loaded == true)
    {
      $events = $calendar->events;
      
      $view = new View('events');
      $view->calendar = $calendar;
      $view->events = $events;
      
      $this->template->page_title = $calendar->name . ' Events';
      $this->template->content = $view;
    }
    else
    {
      $calendars = ORM::factory('calendar')->orderby('name', 'ASC')->find_all();
      
      $view = new View('calendars');
      $view->calendars = $calendars;
      
      $this->template->page_title = 'Calendars';
      $this->template->content = $view;
    }
  }
  
  public function add()
  {
    $this->_is_logged_in();
        
    $view = new View('add_calendar');
    
    $this->template->page_title = 'Add New Calendar';
    $this->template->content = $view;
  }
  
  public function create()
  {
    $this->_is_logged_in();
    
    $calendar = ORM::factory('calendar');
    
    $calendar->name = $this->input->post('name');
    $calendar->url = url::title($calendar->name, '_');
    
    $calendar->save();
    
    $this->session->set_flash('notice', 'New Calendar Created!');
    url::redirect('admin/calendars');
  }
  
  public function edit($url)
  {
    $this->_is_logged_in();
        
    $calendar = ORM::factory('calendar')->where('url', $url)->find();
    
    $view = new View('edit_calendar');
    $view->calendar = $calendar;
    
    $this->template->page_title = 'Edit Calendar';
    $this->template->content = $view;
  }
  
  public function update()
  {
    $this->_is_logged_in();
        
    $calendar = ORM::factory('calendar', $this->input->post('id'));
    
    $calendar->name = $this->input->post('name');
    $calendar->url = url::title($calendar->name, '_');
    
    $calendar->save();
    
    $this->session->set_flash('notice', 'Calendar Edited!');
    url::redirect('admin/calendars');
  }
  
  public function delete($url)
  {
    $this->_is_logged_in();
    
    $calendar = ORM::factory('calendar')->where('url', $url)->find();
    $calendar->delete();
    
    $this->session->set_flash('notice', 'Calendar Deleted!');
    url::redirect('admin/calendars');
  }
  
  public function add_event($url)
  {
    $this->_is_logged_in();
    
    $calendar = ORM::factory('calendar')->where('url', $url)->find();
    
    $view = new View('add_event');
    $view->calendar = $calendar;
    
    $this->template->page_title = 'Add an Event';
    $this->template->content = $view;
  }
  
  public function create_event()
  {
    $this->_is_logged_in();
    
    $calendar = ORM::factory('calendar')->where('id', $this->input->post('calendar_id'))->find();
    $event = ORM::factory('event');
    
    $event->name = $this->input->post('name');
    $event->url = url::title($event->name, '_');
    $event->calendar_id = $calendar->id;
    $event->month = $this->input->post('month');
    $event->day = $this->input->post('day');
    $event->year = $this->input->post('year');
    $event->start_hour = $this->input->post('start_hour');
    $event->start_minute = $this->input->post('start_minute');
    $event->start_meridiem = $this->input->post('start_meridiem');
    $event->end_hour = $this->input->post('end_hour');
    $event->end_minute = $this->input->post('end_minute');
    $event->end_meridiem = $this->input->post('end_meridiem');
    $event->time = strtotime($event->month.'/'.$event->day.'/'.$event->year);
    $event->calendar_id = $calendar->id;
    $event->description = $this->input->post('description');
    
    $event->save();
    
    $this->session->set_flash('notice', 'Event Created!');
    url::redirect('admin/calendars/' . $calendar->url);
  }
  
  public function edit_event($calendar, $url)
  {
    $this->_is_logged_in();
    
    $calendar = ORM::factory('calendar')->where('url', $calendar)->find();
    $event = ORM::factory('event')->where(array('url' => $url, 'calendar_id' => $calendar->id))->find();
    
    $view = new View('edit_event');
    $view->event = $event;
    $view->calendar = $calendar;
    
    $this->template->page_title = 'Edit Event';
    $this->template->content = $view;
  }
  
  public function update_event()
  {
    $this->_is_logged_in();
    
    $event = ORM::factory('event')->where('id', $this->input->post('id'))->find();
    $calendar = ORM::factory('calendar')->where('id', $event->calendar_id)->find();
    
    $event->name = $this->input->post('name');
    $event->url = url::title($event->name, '_');
    $event->calendar_id = $calendar->id;
    $event->month = $this->input->post('month');
    $event->day = $this->input->post('day');
    $event->year = $this->input->post('year');
    $event->start_hour = $this->input->post('start_hour');
    $event->start_minute = $this->input->post('start_minute');
    $event->start_meridiem = $this->input->post('start_meridiem');
    $event->end_hour = $this->input->post('end_hour');
    $event->end_minute = $this->input->post('end_minute');
    $event->end_meridiem = $this->input->post('end_meridiem');
    $event->time = strtotime($event->month.'/'.$event->day.'/'.$event->year);
    $event->description = $this->input->post('description');
    
    $event->save();
    
    $this->session->set_flash('notice', 'Event Updated!');
    url::redirect('admin/calendars/' . $calendar->url);
  }
  
  public function delete_event($calendar, $url)
  {
    $this->_is_logged_in();
    
    $calendar = ORM::factory('calendar')->where('url', $calendar)->find();
    $event = ORM::factory('event')->where(array('url' => $url, 'calendar_id' => $calendar->id))->find();
    $event->delete();
    
    $this->session->set_flash('notice', 'Event Deleted!');
    url::redirect('admin/calendars/' . $calendar->url);
  }
  
  function _is_logged_in()
	{
	  if(!$this->session->get('logged_in'))
	  {
	    redirect('admin/login');
	  }
	}
  
} // End Calendars Controller