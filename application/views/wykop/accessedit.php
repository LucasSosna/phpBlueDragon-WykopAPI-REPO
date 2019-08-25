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
<li><a href="'.base_url('access').'">'.$this->lang->line('a1093').'</a></li>
<li class="active">'.$Title.'</li>
</ol>';

echo '<h1>'.$Title.'</h1>';

echo $Content.'<br /><br />';

if($IsAdded)
{
    echo '<div class="alert alert-success">'.$this->lang->line('a1094').'</div>';
    $ClearFields = true;
}
    
echo form_open('accessedit/'.$ProjectId);
    
echo '<strong>'.$this->lang->line('a1095').'</strong> <br /> '.form_input(array('name' => 'access_key', 'id' => 'access_key', 'value' => $Vaccess_key, 'class' => 'form-control')).'<br />';
echo form_error('access_key','<div class="alert alert-danger">','</div>');

echo '<strong>'.$this->lang->line('a1096').'</strong> <br /> '.form_input(array('name' => 'access_secret', 'id' => 'access_secret', 'value' => $Vaccess_secret, 'class' => 'form-control')).'<br />';
echo form_error('access_secret','<div class="alert alert-danger">','</div>');

echo '<strong>'.$this->lang->line('a1097').'</strong> <br /> '.form_input(array('name' => 'access_connection', 'id' => 'access_connection', 'value' => $Vaccess_connection, 'class' => 'form-control')).'<br />';
echo form_error('access_connection','<div class="alert alert-danger">','</div>');



echo form_hidden('formlogin','yes');
echo form_submit(array('name' => 'buttonstart', 'value' => ''.$this->lang->line('a1098').'', 'class' => 'btn btn-info btn-block'));
echo form_close();

?>