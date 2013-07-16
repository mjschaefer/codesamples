<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

	<title>Lowell.net - <?=$page_title?></title>
	
	<link rel="shortcut icon" href="<?=url::base()?>imx/favicon.ico" type="image/x-icon"/>
	<link rel="stylesheet" href="<?=url::base()?>css/reset.css" type="text/css" charset="utf-8"/>
	<link rel="stylesheet" href="<?=url::base()?>css/screen.css" type="text/css" charset="utf-8"/>
	<style type="text/css" media="screen">
	 body{background:url(<?=url::base()?>imx/header_<? srand((double) microtime( )*1000000); $random = rand(1,2); echo $random; ?>.jpg) no-repeat top center #fff;}
	</style>
</head>
<body>
  <div id="page">
    <div id="header">
      <img id="logo" src="<?=url::base()?>imx/logo.png" alt="Lowell. Proud Past, Bright Future" width="220px" height="200px"/>
      <p id="info">
        <strong>Lowell Town Hall Business Hours:</strong> Mon.&ndash;Fri. from 8am to 4pm<br/>
        <strong>Address:</strong> 501 E Main St &bull; Lowell, IN<br/>
        <strong>Phone:</strong> 219-696-7794 <strong>Fax:</strong> 219-696-7796
      </p>
    </div>
    
    <?php if($alert->warning != '' || $alert->notice != '') { ?>
    <div id="alerts">
      <?php if($alert->warning != '') { ?><h1 id="warning"><span class="bigger">Warning:</span> <?=$alert->warning?></h1><?php } ?>
      <?php if($alert->notice != '') { ?><h1 id="notice"><span class="bigger">Notice:</span> <?=$alert->notice?></h1><?php } ?>
    </div>
    <?php } ?>
    
    <div id="menu">
      <ul>
        <li>
          <form id="search-form" method="get" action="http://www.jrank.org/api/search/v2">
            <input id="key" name="key" type="hidden" value="279c02ddfa8972cac69689fd0d4a5c85ed40e904" />
            <input type="text" name="q" id="query" value="Search here..."/><input type="image" name="commit" src="<?=url::base()?>imx/search_button.png" id="submit"/>
          </form>
        </li>
        <li><a href="<?=url::site('bill_pay')?>" id="a_bill_pay"><img id="img_bill_pay" src="<?=url::base()?>imx/bill_pay_green.jpg" alt="Pay your bill" /></a></li>
        <li class="hover_effect"><a href="<?=url::site('')?>">Home</a></li>
        <li class="hover_effect"><a href="<?=url::site('contact_us')?>">Contact Us</a></li>
        <li class="hover_effect"><a href="<?=url::site('around_town')?>">Around Town</a></li>
        <li class="hover_effect"><a href="<?=url::site('calendar/town')?>">Event Calendar</a></li>
        
        <li class="hover_effect"><a href="<?=url::site('council')?>">Town Council</a>
          <ul>
            <li><a href="<?=url::site('council')?>">About</a></li>
            <li><a href="<?=url::site('council/code_of_ordinances')?>">Code of Ordinances</a></li>
            <li><a href="<?=url::site('council/reports')?>">Minutes / Agendas</a></li>
          </ul>
        </li>
        
        <li class="hover_effect"><a href="<?=url::site('town_administrator')?>">Town Administrator</a></li>

        <li class="hover_effect"><span class="fake-menu-item">Clerk Treasurer</span>
          <ul>
            <li><a href="<?=url::site('treasurer/clerk_treasurer')?>">About</a></li>
            <li><a href="<?=url::site('treasurer/clerk_treasurers_report')?>">Clerk Treasurer's Report</a></li>
            <li><a href="<?=url::site('treasurer/state_board_of_account_audit')?>">State Board of Account Audit</a></li>
          </ul>
        </li>
        
        <li class="hover_effect"><span class="fake-menu-item">Boards / Commissions</span>
          <ul>
            <li><a href="<?=url::site('boards/annexation')?>">Annexation Committee</a></li>
            <li><a href="<?=url::site('boards/zoning')?>">Board of Zoning Appeals</a></li>
            <li><a href="<?=url::site('boards/edc')?>">EDC</a></li>
            <li><a href="<?=url::site('boards/freedom')?>">Freedom Park</a></li>
            <li><a href="<?=url::site('boards/preservation')?>">Historic Preservation</a></li>
            <li><a href="<?=url::site('boards/plan')?>">Plan Commission</a></li>
            <li><a href="<?=url::site('boards/police')?>">Police Commission</a></li>
            <li><a href="<?=url::site('boards/redevelopment')?>">Redevelopment Commission</a></li>
            <li><a href="<?=url::site('boards/stormwater_dept')?>">Stormwater Management (MS4)</a></li>
            <li><a href="<?=url::site('boards/traffic')?>">Traffic Commission</a></li>
            <li><a href="<?=url::site('boards/uba')?>">Unsafe Building Authority</a></li>
          </ul>
        </li>
        
        <li class="hover_effect"><span class="fake-menu-item">Departments</span>
          <ul>
            <li><a href="<?=url::site('departments/building')?>">Building Dept.</a></li>
            <li><a href="http://www.lowellvfd.com">Fire Dept.</a></li>
            <li><a href="http://www.696cops.com">Police Dept.</a></li>
            <li><a href="<?=url::site('departments/parks')?>">Parks Dept.</a></li>
            <li><a href="<?=url::site('departments/recycling')?>">Recycling Dept.</a></li>
            <li><a href="<?=url::site('departments/sewer')?>">Sewer Dept.</a></li>
            <li><a href="<?=url::site('departments/stormwater')?>">Stormwater Management (MS4)</a></li>
            <li><a href="<?=url::site('departments/street')?>">Street Dept.</a></li>
            <li><a href="<?=url::site('departments/water')?>">Water Dept.</a></li>
            <li><a href="<?=url::site('departments/waste_water')?>">Waste Water Dept.</a></li>
          </ul>
        </li>

        <li class="hover_effect"><span class="fake-menu-item">Americans with Disabilities Act</span>
          <ul>
            <li><a href="<?=url::site('ada')?>">ADA</a></li>
          </ul>
        </li>
        
        <li class="hover_effect"><a href="<?=url::site('court')?>">Lowell Town Court</a></li>
        <li class="hover_effect"><a href="<?=url::site('annual_licenses')?>">Annual Licenses</a></li>
      </ul>
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

    <div id="footer">
      <p>
        <img id="sponsors1" src="<?=url::base()?>imx/circle_recovery_logo.jpg" alt="Lowell. Proud Past, Bright Future" />
        <img id="sponsors2" src="<?=url::base()?>imx/logo copy[1].jpg" alt="Lowell. Proud Past, Bright Future" />
        <br />
        <br />
        Copyright &copy; 2009 by the Town of Lowell.
        Design by <a href="http://gigahertzpc.net">Gigahertz PC Services &amp; Design</a><br/>
        Search provided by <a href="http://www.jrank.org/">JRank</a>.
      </p>
    </div>
  </div>
</body>
</html>