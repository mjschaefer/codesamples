<?php defined('SYSPATH') OR die('No direct access allowed.');

class Departments_Controller extends Template_Controller {
  
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
        
    $view = new View('department_list');
    
    $this->template->page_title = "Departments";
    $this->template->content = $view;
  }
  
  public function list_pages($section)
  {
    $this->_is_logged_in();
    
    $pages = ORM::factory('page')->where('section', $section)->orderby('order', 'ASC')->find_all();
    
    $view = new View('department_page_list');
    $view->section = $section;
    $view->pages = $pages;
  
    $this->template->page_title = ucwords( str_replace('_', ' ', $section) ) . " Dept. Pages";
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
  
  public function add_page($section)
  {
    $this->_is_logged_in();
        
    $view = new View('add_department_page');
    $view->section = $section;
    
    $this->template->page_title = 'Add New Page';
    $this->template->content = $view;
  }
  
  public function create_page()
  {
    $this->_is_logged_in();
    
    $page = ORM::factory('page');
    
    $page->title = $this->input->post('title');
    $page->url = url::title($page->title, '_');
    $page->section = $this->input->post('section');
    $page->body = $this->input->post('body');
    
    $page->save();
    
    $this->session->set_flash('notice', 'New Page Created!');
    url::redirect('admin/departments/list_pages/' . $page->section);
  }
  
  public function edit_page($section, $url)
  {
    $this->_is_logged_in();
        
    $page = ORM::factory('page')->where(array('url' => $url, 'section' => $section))->find();
    
    $view = new View('edit_department_page');
    $view->page = $page;
    $view->section = $section;
    
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
    url::redirect('admin/departments/list_pages/' . $page->section);
  }
  
  public function delete_page($section, $url)
  {
    $this->_is_logged_in();
    
    $page = ORM::factory('page')->where(array('url' => $url, 'section' => $section))->find();
    $page->delete();
    
    $this->session->set_flash('notice', 'Page Deleted!');
    url::redirect('admin/departments/list_pages/' . $section);
  }
  
  public function minutes($section)
  {
    $this->_is_logged_in();
    
    $minutes = ORM::factory('report')->select('id, date')->where( array('section' => $section, 'type' => 'minutes') )->orderby('date', 'DESC')->find_all();
    
    $view = new View('dept_minutes_list');
    $view->section = $section;
    $view->minutes = $minutes;
    
    $this->template->page_title = ucwords($section) . 'Dept. Minutes';
    $this->template->content = $view;
  }
  
  public function add_minutes($section)
  {
    $this->_is_logged_in();
        
    $view = new View('add_dept_minutes');
    $view->section = $section;
    
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
    $minutes->section = $this->input->post('section');
    $minutes->type = 'minutes';
    $minutes->body = $this->input->post('body');
    
    $minutes->save();
    
    $this->session->set_flash('notice', 'New Minutes Created!');
    url::redirect('admin/departments/' . $minutes->section . '/minutes');
  }
  
  public function edit_minutes($section, $id)
  {
    $this->_is_logged_in();
        
    $minutes = ORM::factory('report')->where('id', $id)->find();
    
    $view = new View('edit_dept_minutes');
    $view->minutes = $minutes;
    $view->section = $section;
    
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
    url::redirect('admin/departments/' . $minutes->section . '/minutes');
  }
  
  public function delete_minutes($section, $id)
  {
    $this->_is_logged_in();
    
    $minutes = ORM::factory('report')->delete($id);
    
    $this->session->set_flash('notice', 'Minutes Deleted!');
    url::redirect('admin/departments/' . $section . '/minutes');
  }
  
  public function agendas($section)
  {
    $this->_is_logged_in();
    
    $agendas = ORM::factory('report')->select('id, date')->where( array('section' => $section, 'type' => 'agendas') )->orderby('date', 'DESC')->find_all();
    
    $view = new View('dept_agendas_list');
    $view->section = $section;
    $view->agendas = $agendas;
    
    $this->template->page_title = ucwords($section) . 'Dept. Agendas';
    $this->template->content = $view;
  }
  
  public function add_agenda($section)
  {
    $this->_is_logged_in();
        
    $view = new View('add_dept_agenda');
    $view->section = $section;
    
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
    $agenda->section = $this->input->post('section');
    $agenda->type = 'agendas';
    $agenda->body = $this->input->post('body');
    
    $agenda->save();
    
    $this->session->set_flash('notice', 'New Agenda Created!');
    url::redirect('admin/departments/' . $agenda->section . '/agendas');
  }
  
  public function edit_agenda($section, $id)
  {
    $this->_is_logged_in();
        
    $agenda = ORM::factory('report')->where('id', $id)->find();
    
    $view = new View('edit_dept_agenda');
    $view->agenda = $agenda;
    $view->section = $section;
    
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
    url::redirect('admin/departments/' . $agenda->section . '/agendas');
  }
  
  public function delete_agenda($section, $id)
  {
    $this->_is_logged_in();
    
    $minutes = ORM::factory('report')->delete($id);
    
    $this->session->set_flash('notice', 'Agenda Deleted!');
    url::redirect('admin/departments/' . $section . '/agendas');
  }
  
  function _is_logged_in()
	{
	  if(!$this->session->get('logged_in'))
	  {
	    url::redirect('admin/login');
	  }
	}
  
} // End Courts Controller