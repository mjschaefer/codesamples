<?php
    extract($_GET);

    $wkArr = array('S','M','T','W','T','F','S');
    $monArr = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
    
    if (!isset($mo)) {        
        $mn = date('n');
    } else {
        $mn = $mo;
    }
    
    $dn = date('j');
    
    if (!isset($yo)) {    
        $yn = date('Y');
    } else {
        $yn = $yo;
    }
    
    $pm = $mn - 1;
    $nm = $mn + 1;
        
    $ny = $yn + 1;
    $py = $yn - 1;
    
    
    $firstday = mktime(0, 0, 0, $mn, 1, $yn);
    $firstWkDay = date('w',$firstday);
    
    $lastDay = mktime(0, 0, 0, $mn+1, 0, $yn);
    $mnthLength = date('j',$lastDay);
?>
<script>
    function cal_load(s_url) {
        $.ajax({
            url: s_url,
            success: function(data) {
                $("#cal").html(data);
            }
        });     
    }

    function cal_hide() {
        $("#cal").html('');
    }
</script>

<table border="0" cellspacing="0" cellpadding="0" name="calTab">
    <tr>
        <?php
            if($pm == 0) {
                $pm = 12;
                echo '<th class="calTH"><a href="#" onclick="cal_load(\'./views/ajax_calendar.php?mo='.$pm.'&yo='.$py.'\');return false;" class="calTH">&lt;</a></th>';
            } else {
                echo '<th class="calTH"><a href="#" onclick="cal_load(\'./views/ajax_calendar.php?mo='.$pm.'&yo='.$yn.'\');return false;" class="calTH">&lt;</a></th>';
            }
            
            echo '<th colspan="5" class="calTH">'.$monArr[$mn-1].' '.$yn.'</th>';
            
            if($nm == 13) {
                $nm = 1;
                echo '<th class="calTH"><a href="#" onclick="cal_load(\'./views/ajax_calendar.php?mo='.$nm.'&yo='.$ny.'\');return false;" class="calTH">&gt;</a></th>';
            } else {                
                echo '<th class="calTH"><a href="#" onclick="cal_load(\'./views/ajax_calendar.php?mo='.$nm.'&yo='.$yn.'\');return false;" class="calTH">&gt;</a></th>';
            }
        ?>
    </tr>
    <tr>
        <?php
            //echo day of week headers
            foreach($wkArr as $k => $v) {
                echo '<th class="calTH">'.$v.'</th>';
            }
        ?>
    </tr>
        <?php
            $rows = 0;
            //echo days in month
            
            //keep track of what day of week it is (+1 cuz sunday is 0 from date function)
            $wkDay = $firstWkDay + 1;
            
            //start the rows
            echo '<tr>';
            //echo the days prior to the first day of selected month
            for($j = 0; $j < $firstWkDay; $j++) {
                echo '<td class="calTD"> </td>';
            }
            //echo days of selected month
            for($i = 1; $i <= $mnthLength; $i++) {
                //add one to wkDay
                $wkDay++;
                //echo the date
                if($mn == date('n') && $i == $dn && $yn == date('Y')) {
                    echo '<td class="calTD"><a href="#" class="dateLink" id="today" onclick="document.getElementById(\'date\').value = (\''.$mn.'/'.$i.'/'.$yn.'\');document.getElementById(\'date_input\').value = (\''.$mn.'/'.$i.'/'.$yn.'\');cal_hide();return false;">'.$i.'</a></td>';                    
                } else {
                    echo '<td class="calTD"><a href="#" class="dateLink" onclick="document.getElementById(\'date\').value = (\''.$mn.'/'.$i.'/'.$yn.'\');document.getElementById(\'date_input\').value = (\''.$mn.'/'.$i.'/'.$yn.'\');cal_hide();return false;">'.$i.'</a></td>';                    
                }
                //check to see if its the end of the week (saturday), if so new row
                if($wkDay == 8) {
                    echo '</tr><tr>';
                    //reset wkDay to 1
                    $wkDay = 1;
                    $rows++;
                }
            }
            //close the last row
            echo '</tr>';
        ?>
    
</table>