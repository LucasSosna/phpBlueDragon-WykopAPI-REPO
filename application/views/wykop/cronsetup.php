<?php

/**
 * @author Lukasz Sosna
 * @copyright 2019
 * @e-mail tree@interia.pl
 * @e-mail support@phpbluedragon.eu
 * @www http://phpbluedragon.eu
 */
 
echo '<ol class="breadcrumb">
<li><a href="'.base_url().'">'.$this->lang->line('a0981').'</a></li>
<li class="active">'.$Title.'</li>
</ol>';

echo '<h1>'.$Title.'</h1>';

echo $Content.'<br /><br />';

if($IsAdded)
{
    echo '<div class="alert alert-success">'.$this->lang->line('a1128').'</div>';
    $ClearFields = true;
}

echo form_open('cronsetup');

echo '<h3>'.$this->lang->line('a1129').'</h3>';
$OptionWhat['noaction'] = ''.$this->lang->line('a1130').'';
$OptionWhat['plus'] = ''.$this->lang->line('a1131').'';
$OptionWhat['minus'] = ''.$this->lang->line('a1132').'';
echo '<strong>'.$this->lang->line('a1133').'</strong> <br /> '.form_dropdown('cron_top', $OptionWhat, $Vcron_top, 'class="form-control"').'<br />';
echo form_error('cron_top','<div class="alert alert-danger">','</div>');


echo '<h3>'.$this->lang->line('a1134').'</h3>';
$OptionWhat2['noaction'] = ''.$this->lang->line('a1130').'';
$OptionWhat2['plus'] = ''.$this->lang->line('a1131').'';
$OptionWhat2['minus'] = ''.$this->lang->line('a1132').'';
echo '<strong>'.$this->lang->line('a1133').'</strong> <br /> '.form_dropdown('cron_dig', $OptionWhat2, $Vcron_dig, 'class="form-control"').'<br />';
echo form_error('cron_dig','<div class="alert alert-danger">','</div>');

echo '<h3>'.$this->lang->line('a1135').'</h3>';
$OptionWhat3['noaction'] = ''.$this->lang->line('a1130').'';
$OptionWhat3['6'] = ''.$this->lang->line('a1136').'';
$OptionWhat3['12'] = ''.$this->lang->line('a1137').'';
$OptionWhat3['24'] = ''.$this->lang->line('a1138').'';
echo '<strong>'.$this->lang->line('a1133').'</strong> <br /> '.form_dropdown('cron_microblog', $OptionWhat3, $Vcron_microblog, 'class="form-control"').'<br />';
echo form_error('cron_microblog','<div class="alert alert-danger">','</div>');


echo '<h3>'.$this->lang->line('a1139').'</h3>';
$OptionWhat4['noaction'] = ''.$this->lang->line('a1130').'';
$OptionWhat4['plus'] = ''.$this->lang->line('a1131').'';
$OptionWhat4['minus'] = ''.$this->lang->line('a1132').'';
$OptionWhat4['like'] = ''.$this->lang->line('a1140').'';
echo '<strong>'.$this->lang->line('a1133').'</strong> <br /> '.form_dropdown('cron_comm_top', $OptionWhat4, $Vcron_comm_top, 'class="form-control"').'<br />';
echo form_error('cron_comm_top','<div class="alert alert-danger">','</div>');
$OptionWhatNum['1'] = '1';
$OptionWhatNum['2'] = '2';
$OptionWhatNum['3'] = '3';
$OptionWhatNum['4'] = '4';
$OptionWhatNum['5'] = '5';
$OptionWhatNum['6'] = '6';
$OptionWhatNum['7'] = '7';
$OptionWhatNum['8'] = '8';
$OptionWhatNum['9'] = '9';
$OptionWhatNum['10'] = '10';
echo '<strong>'.$this->lang->line('a1133').'</strong> <br /> '.form_dropdown('cron_comm_howmany_top', $OptionWhatNum, $Vcron_comm_howmany_top, 'class="form-control"').'<br />';
echo form_error('cron_comm_howmany_top','<div class="alert alert-danger">','</div>');

echo '<h3>'.$this->lang->line('a1141').'</h3>';
$OptionWhat5['noaction'] = ''.$this->lang->line('a1130').'';
$OptionWhat5['plus'] = ''.$this->lang->line('a1131').'';
$OptionWhat5['minus'] = ''.$this->lang->line('a1132').'';
$OptionWhat5['like'] = ''.$this->lang->line('a1140').'';
echo '<strong>'.$this->lang->line('a1133').'</strong> <br /> '.form_dropdown('cron_comm_dig', $OptionWhat5, $Vcron_comm_dig, 'class="form-control"').'<br />';
echo form_error('cron_comm_dig','<div class="alert alert-danger">','</div>');
$OptionWhatNum2['1'] = '1';
$OptionWhatNum2['2'] = '2';
$OptionWhatNum2['3'] = '3';
$OptionWhatNum2['4'] = '4';
$OptionWhatNum2['5'] = '5';
$OptionWhatNum2['6'] = '6';
$OptionWhatNum2['7'] = '7';
$OptionWhatNum2['8'] = '8';
$OptionWhatNum2['9'] = '9';
$OptionWhatNum2['10'] = '10';
echo '<strong>'.$this->lang->line('a1133').'</strong> <br /> '.form_dropdown('cron_comm_howmany_dig', $OptionWhatNum2, $Vcron_comm_howmany_dig, 'class="form-control"').'<br />';
echo form_error('cron_comm_howmany_dig','<div class="alert alert-danger">','</div>');


echo '<h3>'.$this->lang->line('a1142').'</h3>';
echo '<strong>'.$this->lang->line('a1143').'</strong> <input type="text" name="cron_start" id="cron_start" value="'.$Vcron_start.'" class="form-control" />
'.form_error('cron_start','<div class="alert alert-danger">','</div>');

echo '<br />';

echo '<strong>'.$this->lang->line('a1144').'</strong> <input type="text" name="cron_stop" id="cron_stop" value="'.$Vcron_stop.'" class="form-control" />
'.form_error('cron_stop','<div class="alert alert-danger">','</div>');

echo '<br />';

echo form_hidden('formlogin','yes');
echo form_submit(array('name' => 'buttonstart', 'value' => ''.$this->lang->line('a1145').'', 'class' => 'btn btn-info btn-block'));
echo form_close();

?>
<script>

$('#cron_start').datetimepicker({
    timeOnly: true,
    timeFormat: "HH:mm:ss",
});

$('#cron_stop').datetimepicker({
    timeOnly: true,
    timeFormat: "HH:mm:ss",
});

</script>