<?php defined('SYSPATH') OR die('No direct access allowed.');

// Get the day names
$days = Calendar::days(3);

?>
<tr>
<?php foreach ($days as $day): ?>
<th><?php echo $day ?></th>
<?php endforeach ?>
</tr>
<?php foreach ($weeks as $week): ?>
<tr class="days">
<?php foreach ($week as $day):

list ($number, $current, $data) = $day;

if (is_array($data))
{
	$classes = $data['classes'];
	$output = empty($data['output']) ? '' : '<ul class="output"><li class="event-item">'.implode('</li><li class="event-item">', $data['output']).'</li></ul>';
}
else
{
	$classes = array();
	$output = '';
}

?>
<td class="<?php echo implode(' ', $classes) ?><?php if($current == false){echo"non_current";}?><?php if(!empty($data['output'])){echo"event";}?>"><span class="day"><?php echo $day[0] ?></span><?php echo $output ?></td>
<?php endforeach ?>
</tr>
<?php endforeach ?>
</table>
