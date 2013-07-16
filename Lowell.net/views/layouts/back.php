<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>The Lowell Dashboard - <?=$page_title?></title>
	
	<link rel="shortcut icon" href="<?=url::base()?>imx/favicon.ico" type="image/x-icon"/>
	<link rel="stylesheet" href="<?=url::base()?>css/reset.css" type="text/css" charset="utf-8"/>
	<link rel="stylesheet" href="<?=url::base()?>css/admin.css" type="text/css" charset="utf-8"/>
</head>
<body>
  <div id="page">
    <div id="header">
      <h1>The Lowell Dashboard</h1>
    </div>
    
    <div id="menu">
      <ul>
        <li><a href="<?=url::site('admin')?>">Home</a></li>
        <li><a href="<?=url::site('admin/alerts')?>">Alerts</a></li>
        <li><a href="<?=url::site('admin/blogs')?>">News and Blogs</a></li>
        <li><a href="<?=url::site('admin/pages')?>">Pages</a></li>
        <li><a href="<?=url::site('admin/calendars')?>">Calendars</a></li>
        <li><a href="<?=url::site('admin/councils')?>">Council</a></li>
        <li><a href="<?=url::site('admin/treasurers')?>">Treasurer</a></li>
        <li><a href="<?=url::site('admin/boards')?>">Boards</a></li>
        <li><a href="<?=url::site('admin/departments')?>">Departments</a></li>
        <li><a href="<?=url::site('admin/courts')?>">Court</a></li>
        <li><a href="<?=url::site('admin/adas')?>">Americans with Disabilities Act</a></li>
        <li><a href="<?=url::site('admin/licenses')?>">Licenses</a></li>
        <li><a href="<?=url::base()?>">Back to the Site</a></li>
        <li><a href="<?=url::site('admin/logout')?>">Logout</a></li>
    </div>
    
    <div id="content">
      <?php if($this->session->get('notice')): ?>
      <div class="flash-notice"><?=$this->session->get('notice')?></div>
      <?php endif; ?>
      
      <?php if($this->session->get('error')): ?>
      <div class="flash-error"><?=$this->session->get('error')?></div>
      <?php endif; ?>
      
      <?=$content?>
    </div>
  </div>
</body>
</html>