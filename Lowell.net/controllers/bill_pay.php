<?php defined('SYSPATH') OR die('No direct access allowed.');

class Bill_Pay_Controller extends Template_Controller {
  
  // Let's set our template and tell it to render after the action is done.
  public $template = 'layouts/front';
  public $auto_render = true;
  
  // CONSTRUCT!
  public function __construct()
  {
    parent::__construct();
    
    $this->session = Session::instance();
  }
  
  public function index($url = 'bill_pay')
  {
    // Grab the Town Alerts
	  $alert = ORM::factory('alert')->find();


    $view = new View('bill_pay');

    // Set the title and send our view to the template.
    $this->template->page_title = 'Pay your bill online';
    $this->template->alert = $alert;
    $this->template->content = $view;
  }
  
} // End Bill Pay Controller