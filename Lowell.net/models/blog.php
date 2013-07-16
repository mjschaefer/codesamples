<?php defined('SYSPATH') or die('No direct script access.');

class Blog_Model extends ORM
{
  protected $has_many = array('posts');
}

?>