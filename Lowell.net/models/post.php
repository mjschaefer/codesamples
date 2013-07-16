<?php defined('SYSPATH') or die('No direct script access.');

class Post_Model extends ORM
{
  protected $belongs_to = array('blog');
}

?>