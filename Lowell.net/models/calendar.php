<?php defined('SYSPATH') or die('No direct script access.');

class Calendar_Model extends ORM
{
  protected $has_many = array('events');
}

?>