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

if($ViewData != "")
{
    echo $ViewData;    
}

echo form_open($UrlFraze);

$OptionWhat['plus'] = ''.$this->lang->line('a1195').'';
$OptionWhat['minus'] = ''.$this->lang->line('a1196').'';

echo '<strong>'.$this->lang->line('a1197').'</strong> <br /> '.form_dropdown('add_what', $OptionWhat, $Vadd_what, 'class="form-control"').'<br />';
echo form_error('add_what','<div class="alert alert-danger">','</div>');

echo form_hidden('formlogin','yes');
echo form_submit(array('name' => 'buttonstart', 'value' => ''.$this->lang->line('a1198').'', 'class' => 'btn btn-info btn-block'));
echo form_close();

?>