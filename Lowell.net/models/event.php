<?php defined('SYSPATH') or die('No direct script access.');

class Event_Model extends ORM
{
  protected $belongs_to = array('calendar');
}

?>