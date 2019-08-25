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
<li class="active">'.$this->lang->line('a1074').'</li>
</ol>';

echo '<h1>'.$Title.'</h1>';

echo $Content.'<br /><br />';

if($ProjectDeleted)
{
    echo '<div class="alert alert-danger" role="alert">'.$this->lang->line('a1075').'</div>';    
}

echo '<div class="container RowTable" style="max-width: 960px;">';

echo '<div class="row RowColor3">
<div class="col-md-1">'.$this->lang->line('a1076').'</div>
<div class="col-md-2">'.$this->lang->line('a1077').'</div>
<div class="col-md-2">'.$this->lang->line('a1078').'</div>
<div class="col-md-3">'.$this->lang->line('a1079').'</div>
<div class="col-md-2">'.$this->lang->line('a1080').'</div>
<div class="col-md-2">'.$this->lang->line('a1081').'</div>
</div>';

$ResultDB = $this->System_model->AccessShowAll();

foreach($ResultDB->result() as $row)
{
    if($i==0)
    {
        $ThisClass = 'RowColor1';
        $i=1;
    }
    else
    {
        $ThisClass = 'RowColor2';
        $i=0;
    }
    
    if($row->access_check == '0000-00-00 00:00:00')
    {
        $AccessCheck = '<em>'.$this->lang->line('a1082').'</em>';
    }
    else
    {
        $AccessCheck = $row->access_check;
    }
    
    echo '<div class="row '.$ThisClass.'">
    <div class="col-md-1">'.$row->access_id.'</div>
    <div class="col-md-2">'.$row->access_key.'</div>
    <div class="col-md-2">'.$row->access_secret.'</div>
    <div class="col-md-3">'.$row->access_connection.'</div>
    <div class="col-md-2">'.$AccessCheck.'</div>
    <div class="col-md-2">
    <a href="'.base_url('accesscheck/'.$row->access_id).'" title="'.$this->lang->line('a1083').'" class="btn btn-info btn-xs">'.$this->lang->line('a1083').'</span></a>
    <a href="'.base_url('accessedit/'.$row->access_id).'" title="'.$this->lang->line('a1045').'" class="btn btn-info btn-xs">'.$this->lang->line('a1046').'</span></a> 
    <a href="JavaScript:DeteleInfo(\''.base_url().'access/delete/'.$row->access_id.'\',\''.$this->lang->line('a1047').'\');" title="'.$this->lang->line('a1048').'" class="btn btn-danger btn-xs">'.$this->lang->line('a1049').'</a></div>
    </div>';  
}

echo '</div>';

echo '<br />';

echo '<h2>'.$this->lang->line('a1084').'</h2>';

if($IsAdded)
{
    echo '<div class="alert alert-success">'.$this->lang->line('a1085').'</div>';
    $ClearFields = true;
}
    
echo form_open('access');
    
echo '<strong>'.$this->lang->line('a1086').'</strong> <br /> '.form_input(array('name' => 'access_key', 'id' => 'access_key', 'value' => $Vaccess_key, 'class' => 'form-control')).'<br />';
echo form_error('access_key','<div class="alert alert-danger">','</div>');

echo '<strong>'.$this->lang->line('a1087').'</strong> <br /> '.form_input(array('name' => 'access_secret', 'id' => 'access_secret', 'value' => $Vaccess_secret, 'class' => 'form-control')).'<br />';
echo form_error('access_secret','<div class="alert alert-danger">','</div>');

echo '<strong>'.$this->lang->line('a1088').'</strong> <br /> '.form_input(array('name' => 'access_connection', 'id' => 'access_connection', 'value' => $Vaccess_connection, 'class' => 'form-control')).'<br />';
echo form_error('access_connection','<div class="alert alert-danger">','</div>');



echo form_hidden('formlogin','yes');
echo form_submit(array('name' => 'buttonstart', 'value' => ''.$this->lang->line('a1089').'', 'class' => 'btn btn-info btn-block'));
echo form_close();

?>