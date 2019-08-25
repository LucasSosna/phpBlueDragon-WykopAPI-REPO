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
    echo '<div class="alert alert-success">'.$this->lang->line('a1099').'</div>';
    $ClearFields = true;
}

if($NoThatEntry)
{
    echo '<div class="alert alert-danger">'.$this->lang->line('a1100').' '.$this->input->post('key_id').'</div>';
}

echo form_open('addcomment');
echo '<h2>'.$this->lang->line('a1101').'</h2>';
echo '<strong>'.$this->lang->line('a1102').'</strong> <br /> '.form_input(array('name' => 'key_id', 'id' => 'key_id', 'value' => $Vkey_id, 'class' => 'form-control')).'<br />';
echo form_error('key_id','<div class="alert alert-danger">','</div>');

echo form_hidden('formlogin','yes');
echo form_submit(array('name' => 'buttonstart', 'value' => ''.$this->lang->line('a1103').'', 'class' => 'btn btn-info btn-block'));
echo form_close();

?>
<script>
/*
$("#key_id").change(function () {
    var str = "";
    str = $("#key_id").text();
    alert(str)
}).change();
*/
</script>
<?php

/*echo form_open('accessedit/'.$ProjectId);
    
echo '<strong>Klucz</strong> <br /> '.form_input(array('name' => 'access_key', 'id' => 'access_key', 'value' => $Vaccess_key, 'class' => 'form-control')).'<br />';
echo form_error('access_key','<div class="alert alert-danger">','</div>');

echo '<strong>Sekret</strong> <br /> '.form_input(array('name' => 'access_secret', 'id' => 'access_secret', 'value' => $Vaccess_secret, 'class' => 'form-control')).'<br />';
echo form_error('access_secret','<div class="alert alert-danger">','</div>');

echo '<strong>Połączenie</strong> <br /> '.form_input(array('name' => 'access_connection', 'id' => 'access_connection', 'value' => $Vaccess_connection, 'class' => 'form-control')).'<br />';
echo form_error('access_connection','<div class="alert alert-danger">','</div>');



echo form_hidden('formlogin','yes');
echo form_submit(array('name' => 'buttonstart', 'value' => 'Edytuj', 'class' => 'btn btn-info btn-block'));
echo form_close();*/

?>