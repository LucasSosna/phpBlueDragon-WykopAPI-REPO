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

echo form_open('searchmicroblog');
echo '<strong>'.$this->lang->line('a1165').'</strong> <br /> '.form_input(array('name' => 'search_q', 'id' => 'search_q', 'value' => $Vsearch_q, 'class' => 'form-control')).'<br />';
echo form_error('search_q','<div class="alert alert-danger">', '</div>');

echo form_hidden('formlogin','yes');
echo form_submit(array('name' => 'buttonstart', 'value' => ''.$this->lang->line('a1166').'', 'class' => 'btn btn-info btn-block'));
echo form_close();



?>