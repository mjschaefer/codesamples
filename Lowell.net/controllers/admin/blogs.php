<?php defined('SYSPATH') OR die('No direct access allowed.');

class Blogs_Controller extends Template_Controller {
  
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
    
    $blog = ORM::factory('blog')->where('url', $url)->find();
    
    if($blog->loaded == true)
    {
      $posts = $blog->posts;
      
      $view = new View('posts');
      $view->blog = $blog;
      $view->posts = $posts;
      
      $this->template->page_title = $blog->name . ' Posts';
      $this->template->content = $view;
    }
    else
    {
      $blogs = ORM::factory('blog')->orderby('name', 'ASC')->find_all();
      
      $view = new View('blogs');
      $view->blogs = $blogs;
      
      $this->template->page_title = 'News &amp; Blogs';
      $this->template->content = $view;
    }
  }
  
  public function add()
  {
    $this->_is_logged_in();
        
    $view = new View('add_blog');
    
    $this->template->page_title = 'Add New Blog';
    $this->template->content = $view;
  }
  
  public function create()
  {
    $this->_is_logged_in();
    
    $blog = ORM::factory('blog');
    
    $blog->name = $this->input->post('name');
    $blog->url = url::title($blog->name, '_');
    
    $blog->save();
    
    $this->session->set_flash('notice', 'New Blog Created!');
    url::redirect('admin/blogs');
  }
  
  public function edit($url)
  {
    $this->_is_logged_in();
        
    $blog = ORM::factory('blog')->where('url', $url)->find();
    
    $view = new View('edit_blog');
    $view->blog = $blog;
    
    $this->template->page_title = 'Edit Blog';
    $this->template->content = $view;
  }
  
  public function update()
  {
    $this->_is_logged_in();
        
    $blog = ORM::factory('page', $this->input->post('id'));
    
    $blog->name = $this->input->post('name');
    $blog->url = url::title($blog->name, '_');
    
    $blog->save();
    
    $this->session->set_flash('notice', 'Blog Edited!');
    url::redirect('admin/blogs');
  }
  
  public function delete($url)
  {
    $this->_is_logged_in();
    
    $blog = ORM::factory('blog')->where('url', $url)->find();
    $blog->delete();
    
    $this->session->set_flash('notice', 'Blog Deleted!');
    url::redirect('admin/blogs');
  }
  
  public function add_post($url)
  {
    $this->_is_logged_in();
    
    $blog = ORM::factory('blog')->where('url', $url)->find();
    
    $view = new View('add_post');
    $view->blog = $blog;
    
    $this->template->page_title = 'Add a Post';
    $this->template->content = $view;
  }
  
  public function create_post()
  {
    $this->_is_logged_in();
    
    $blog = ORM::factory('blog')->where('id', $this->input->post('blog_id'))->find();
    $post = ORM::factory('post');
    
    $post->title = $this->input->post('title');
    $post->url = url::title($post->title, '_');
    $post->time = time();
    $post->blog_id = $this->input->post('blog_id');
    $post->body = $this->input->post('body');
    
    $post->save();
    
    $this->session->set_flash('notice', 'Post Created!');
    url::redirect('admin/blogs/' . $blog->url);
  }
  
  public function edit_post($url)
  {
    $this->_is_logged_in();
    
    $post = ORM::factory('post')->where('url', $url)->find();
    $blog = ORM::factory('blog')->where('id', $post->blog_id)->find();
    
    $view = new View('edit_post');
    $view->post = $post;
    $view->blog = $blog;
    
    $this->template->page_title = 'Edit Post';
    $this->template->content = $view;
  }
  
  public function update_post()
  {
    $this->_is_logged_in();
    
    $post = ORM::factory('post')->where('id', $this->input->post('id'))->find();
    $blog = ORM::factory('blog')->where('id', $post->blog_id)->find();
    
    $post->title = $this->input->post('title');
    $post->url = url::title($post->title, '_');
    $post->body = $this->input->post('body');
    
    $post->save();
    
    $this->session->set_flash('notice', 'Post Updated!');
    url::redirect('admin/blogs/' . $blog->url);
  }
  
  public function delete_post($url)
  {
    $this->_is_logged_in();
    
    $post = ORM::factory('post')->where('url', $url)->find();
    $blog = ORM::factory('blog')->where('id', $post->blog_id)->find();
    $post->delete();
    
    $this->session->set_flash('notice', 'Post Deleted!');
    url::redirect('admin/blogs/' . $blog->url);
  }
  
  function _is_logged_in()
	{
	  if(!$this->session->get('logged_in'))
	  {
	    redirect('admin/login');
	  }
	}
  
} // End Blogs Controller