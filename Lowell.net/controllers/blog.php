<?php defined('SYSPATH') OR die('No direct access allowed.');

class Blog_Controller extends Template_Controller {
  
  // Let's set our template and tell it to render after the action is done.
  public $template = 'layouts/front';
  public $auto_render = true;
  
  // CONSTRUCT!
  public function __construct()
  {
    parent::__construct();
    
    $this->session = Session::instance();
  }
  
	public function index($url = 'news')
	{
	  // Grab the Town Alerts
	  $alert = ORM::factory('alert')->find();
	  
	  // Grab our News Posts
		$blog = ORM::factory('blog')->where('url', $url)->find();
		
		if($blog->loaded == true)
		{
  		$posts = $blog->limit(5)->offset(0)->orderby('time', 'DESC')->posts;
		
  		// Setup our pagination.
  		$post_count = ORM::factory('post')->where('blog_id', $blog->id)->count_all();
		
  		$page_config = array(
  		  'uri_segment' => 'page',
  		  'total_items' => $post_count,
  		  'items_per_page' => 5,
  		  'auto_hide' => true,
  		  'style' => 'digg'
  		  );
  		$pagination = new Pagination($page_config);
		
  		// Setup our view.
  		$view = new View('blog');
  		$view->posts = $posts;
  		$view->pagination = $pagination;
		
  		// Set the title and send our view to the template.
  		$this->template->page_title = $blog->name;
  		$this->template->alert = $alert;
  		$this->template->content = $view;
  	}
  	else
  	{
  	  $blog = ORM::factory('blog')->where('url', $url)->find();
  	  $posts = $blog->limit(5)->offset(0)->orderby('time', 'DESC')->posts;
		
  		// Setup our pagination.
  		$post_count = ORM::factory('post')->where('blog_id', $blog->id)->count_all();
		
  		$page_config = array(
  		  'uri_segment' => 'page',
  		  'total_items' => $post_count,
  		  'items_per_page' => 5,
  		  'auto_hide' => true,
  		  'style' => 'digg'
  		  );
  		$pagination = new Pagination($page_config);
		
  		// Setup our view.
  		$view = new View('blog');
  		$view->posts = $posts;
  		$view->pagination = $pagination;
		
  		// Set the title and send our view to the template.
  		$this->template->page_title = $blog->name;
  		$this->template->alert = $alert;
  		$this->template->content = $view;
  	}
	}
	
	public function page($url = 'news')
	{
	  // Grab the Town Alerts
	  $alert = ORM::factory('alert')->find();
	  
	  // Grab our News Posts
		$blog = ORM::factory('blog')->where('url', 'news_events')->find();

		// Calculate our offset.
		$offset = ((int)$this->uri->segment('page') - 1 ) * 5;

		$posts = $blog->limit(5)->offset($offset)->orderby('time', 'DESC')->posts;
  
		// Setup our pagination.
		$post_count = ORM::factory('post')->where('blog_id', $blog->id)->count_all();
	
		$page_config = array(
		  'uri_segment' => 'page',
		  'total_items' => $post_count,
		  'items_per_page' => 5,
		  'auto_hide' => true,
		  'style' => 'digg'
		  );
		$pagination = new Pagination($page_config);
	
		// Setup our view.
		$view = new View('blog');
		$view->posts = $posts;
		$view->pagination = $pagination;
	
		// Set the title and send our view to the template.
		$this->template->page_title = $blog->name;
		$this->template->alert = $alert;
		$this->template->content = $view;
  	}

} // End Blog Controller