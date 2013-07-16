<?php defined('SYSPATH') OR die('No direct access allowed.');

class Treasurers_Controller extends Template_Controller {
  
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
    
    $pages = ORM::factory('page')->where('section', 'treasurer')->orderby('title', 'DESC')->find_all();
    
    $view = new View('treasurer_page_list');
    $view->pages = $pages;
    
    $this->template->page_title = "Treasurer Pages &amp; Reports";
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
  
  public function add_page()
  {
    $this->_is_logged_in();
        
    $view = new View('add_treasurer_page');
    
    $this->template->page_title = 'Add New Page';
    $this->template->content = $view;
  }
  
  public function create_page()
  {
    $this->_is_logged_in();
    
    $page = ORM::factory('page');
    
    $page->title = $this->input->post('title');
    $page->url = url::title($page->title, '_');
    $page->section = 'treasurer';
    $page->body = $this->input->post('body');
    
    $page->save();
    
    $this->session->set_flash('notice', 'New Page Created!');
    url::redirect('admin/treasurers');
  }
  
  public function edit_page($url)
  {
    $this->_is_logged_in();
        
    $page = ORM::factory('page')->where('url', $url)->find();
    
    $view = new View('edit_treasurer_page');
    $view->page = $page;
    
    $this->template->page_title = 'Edit Page';
    $this->template->content = $view;
  }
  
  public function update_page()
  {
    $this->_is_logged_in();
        
    $page = ORM::factory('page', $this->input->post('id'));
    
    $page->title = $this->input->post('title');
    $page->url = url::title($page->title, '_');
    $page->body = $this->input->post('body');
    
    $page->save();
    
    $this->session->set_flash('notice', 'Page Edited!');
    url::redirect('admin/treasurers');
  }
  
  public function delete_page($url)
  {
    $this->_is_logged_in();
    
    $page = ORM::factory('page')->where('url', $url)->delete();
    
    $this->session->set_flash('notice', 'Page Deleted!');
    url::redirect('admin/treasurers');
  }
  
  /*
  public function minutes()
  {
    $this->_is_logged_in();
    
    $minutes = ORM::factory('report')->select('id, date')->where( array('section' => 'treasurer', 'type' => 'minutes') )->orderby('date', 'DESC')->find_all();
    
    $view = new View('treasurer_minutes_list');
    $view->minutes = $minutes;
    
    $this->template->page_title = 'Treasurer Minutes';
    $this->template->content = $view;
  }
  
  public function add_minutes()
  {
    $this->_is_logged_in();
        
    $view = new View('add_treasurer_minutes');
    
    $this->template->page_title = 'New Minutes';
    $this->template->content = $view;
  }
  
  public function create_minutes()
  {
    $this->_is_logged_in();
    
    $minutes = ORM::factory('report');
    
    $minutes->month = $this->input->post('month');
    $minutes->day = $this->input->post('day');
    $minutes->year = $this->input->post('year');
    $minutes->date = strtotime($minutes->month . "/" . $minutes->day . "/" . $minutes->year);
    $minutes->section = 'treasurer';
    $minutes->type = 'minutes';
    $minutes->body = $this->input->post('body');
    
    $minutes->save();
    
    $this->session->set_flash('notice', 'New Minutes Created!');
    url::redirect('admin/treasurers/minutes');
  }
  
  public function edit_minutes($id)
  {
    $this->_is_logged_in();
        
    $minutes = ORM::factory('report')->where('id', $id)->find();
    
    $view = new View('edit_treasurer_minutes');
    $view->minutes = $minutes;
    
    $this->template->page_title = 'Edit Minutes';
    $this->template->content = $view;
  }
  
  public function update_minutes()
  {
    $this->_is_logged_in();
        
    $minutes = ORM::factory('report', $this->input->post('id'));
    
    $minutes->month = $this->input->post('month');
    $minutes->day = $this->input->post('day');
    $minutes->year = $this->input->post('year');
    $minutes->date = strtotime($minutes->month . "/" . $minutes->day . "/" . $minutes->year);
    $minutes->body = $this->input->post('body');
    
    $minutes->save();
    
    $this->session->set_flash('notice', 'Minutes Edited!');
    url::redirect('admin/treasurers/minutes');
  }
  
  public function delete_minutes($id)
  {
    $this->_is_logged_in();
    
    $minutes = ORM::factory('report')->delete($id);
    
    $this->session->set_flash('notice', 'Minutes Deleted!');
    url::redirect('admin/treasurers/minutes');
  }
  
  public function agendas()
  {
    $this->_is_logged_in();
    
    $agendas = ORM::factory('report')->select('id, date')->where( array('section' => 'treasurer', 'type' => 'agendas') )->orderby('date', 'DESC')->find_all();
    
    $view = new View('treasurer_agendas_list');
    $view->agendas = $agendas;
    
    $this->template->page_title = 'Treasurer Agendas';
    $this->template->content = $view;
  }
  
  public function add_agenda()
  {
    $this->_is_logged_in();
        
    $view = new View('add_treasurer_agenda');
    
    $this->template->page_title = 'New Agenda';
    $this->template->content = $view;
  }
  
  public function create_agenda()
  {
    $this->_is_logged_in();
    
    $agenda = ORM::factory('report');
    
    $agenda->month = $this->input->post('month');
    $agenda->day = $this->input->post('day');
    $agenda->year = $this->input->post('year');
    $agenda->date = strtotime($agenda->month . "/" . $agenda->day . "/" . $agenda->year);
    $agenda->section = 'treasurer';
    $agenda->type = 'agendas';
    $agenda->body = $this->input->post('body');
    
    $agenda->save();
    
    $this->session->set_flash('notice', 'New Agenda Created!');
    url::redirect('admin/treasurers/agendas');
  }
  
  public function edit_agenda($id)
  {
    $this->_is_logged_in();
        
    $agenda = ORM::factory('report')->where('id', $id)->find();
    
    $view = new View('edit_treasurer_agenda');
    $view->agenda = $agenda;
    
    $this->template->page_title = 'Edit Agenda';
    $this->template->content = $view;
  }
  
  public function update_agenda()
  {
    $this->_is_logged_in();
        
    $agenda = ORM::factory('report', $this->input->post('id'));
    
    $agenda->month = $this->input->post('month');
    $agenda->day = $this->input->post('day');
    $agenda->year = $this->input->post('year');
    $agenda->date = strtotime($agenda->month . "/" . $agenda->day . "/" . $agenda->year);
    $agenda->body = $this->input->post('body');
    
    $agenda->save();
    
    $this->session->set_flash('notice', 'Agenda Edited!');
    url::redirect('admin/treasurers/agendas');
  }
  
  public function delete_agenda($id)
  {
    $this->_is_logged_in();
    
    $minutes = ORM::factory('report')->delete($id);
    
    $this->session->set_flash('notice', 'Agenda Deleted!');
    url::redirect('admin/treasurers/agendas');
  }*/
  
  function _is_logged_in()
	{
	  if(!$this->session->get('logged_in'))
	  {
	    url::redirect('admin/login');
	  }
	}
  
} // End Treasurers Controller