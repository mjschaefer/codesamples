<?php defined('SYSPATH') OR die('No direct access allowed.');

class Index_Controller extends Template_Controller {
  
  // Let's set our template and tell it to render after the action is done.
  public $template = 'layouts/front';
  public $auto_render = true;
  
  // CONSTRUCT!
  public function __construct()
  {
    parent::__construct();
    
    $this->session = Session::instance();
  }
  
  // Our site's home page.
	public function index()
	{
	  // Grab the Town Alerts
	  $alert = ORM::factory('alert')->find();
	  
	  // Grab our News Posts
		$blog = ORM::factory('blog')->where('id', '4')->find();
		$posts = $blog->limit(5)->offset(0)->orderby('time', 'DESC')->posts;
		
		// Grab the four nearest events
		$calendar = ORM::factory('calendar')->where('url', 'town')->find();
		$events = $calendar->where('time >=', time())->limit(5)->offset(0)->orderby('time', 'ASC')->events;
		
		// Setup our pagination.
		$post_count = ORM::factory('post')->where('blog_id', $blog->id)->count_all();
		
		$page_config = array(
		  'base_url' => 'blog/news',
		  'uri_segment' => 'page',
		  'total_items' => $post_count,
		  'items_per_page' => 5,
		  'auto_hide' => true,
		  'style' => 'digg'
		  );
		$pagination = new Pagination($page_config);
		
		// Setup our view.
		$view = new View('index');
		$view->posts = $posts;
		$view->events = $events;
		$view->pagination = $pagination;
		
		// Set the title and send our view to the template.
		$this->template->page_title = 'Home';
		$this->template->alert = $alert;
		$this->template->content = $view;
	}
	
	public function contact_us()
	{
	  // Grab the Town Alerts
	  $alert = ORM::factory('alert')->find();
	  
	  // Set our view.
	  $view = new View('contact_us');
	  
	  // Set the title and send our view to the template.
		$this->template->page_title = 'Contact Us';
		$this->template->alert = $alert;
		$this->template->content = $view;
	}
	
	public function send_contact()
	{
	  $to = 'bryan@genericapparel.com';
	  $from = $this->input->post('email');
	  $subject = $this->input->post('subject');
	  $message = $this->input->post('name') . '/n' . $this->input->post('message');
	  
	  email::send($to, $from, $subject, $message);
	  
	  $this->session->set_flash('notice', 'Your Message has been Sent.');
	  url::redirect('/');
	}

} // End Index Controller