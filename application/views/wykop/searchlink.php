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

echo form_open('search/link');
echo '<strong>'.$this->lang->line('a1147').'</strong> <br /> '.form_input(array('name' => 'search_q', 'id' => 'search_q', 'value' => $Vsearch_q, 'class' => 'form-control')).'<br />';
echo form_error('search_q','<div class="alert alert-danger">', '</div>');


$OptionWhat['all'] = ''.$this->lang->line('a1148').'';
$OptionWhat['promoted'] = ''.$this->lang->line('a1149').'';
$OptionWhat['archive'] = ''.$this->lang->line('a1150').'';
$OptionWhat['duplicates'] = ''.$this->lang->line('a1151').'';

$OptionSort['best'] = ''.$this->lang->line('a1152').''; 
$OptionSort['diggs'] = ''.$this->lang->line('a1153').'';
$OptionSort['comments'] = ''.$this->lang->line('a1154').''; 
$OptionSort['new'] = ''.$this->lang->line('a1155').'';

$OptionWhen['all'] = ''.$this->lang->line('a1156').''; 
$OptionWhen['today'] = ''.$this->lang->line('a1157').''; 
$OptionWhen['yesterday'] = ''.$this->lang->line('a1158').''; 
$OptionWhen['week'] = ''.$this->lang->line('a1159').''; 
$OptionWhen['month'] = ''.$this->lang->line('a1160').''; 

echo '<strong>'.$this->lang->line('a1161').'</strong> <br /> '.form_dropdown('search_what', $OptionWhat, $Vsearch_what, 'class="form-control"').'<br />';
echo form_error('search_what','<div class="alert alert-danger">','</div>');
  
echo '<strong>'.$this->lang->line('a1162').'</strong> <br /> '.form_dropdown('search_sort', $OptionSort, $Vsearch_sort, 'class="form-control"').'<br />';
echo form_error('search_sort','<div class="alert alert-danger">','</div>');

echo '<strong>'.$this->lang->line('a1163').'</strong> <br /> '.form_dropdown('search_when', $OptionWhen, $Vsearch_when, 'class="form-control"').'<br />';
echo form_error('search_when','<div class="alert alert-danger">','</div>');

echo form_hidden('formlogin','yes');
echo form_submit(array('name' => 'buttonstart', 'value' => ''.$this->lang->line('a1164').'', 'class' => 'btn btn-info btn-block'));
echo form_close();



?>