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
<li><a href="'.base_url('access').'">'.$this->lang->line('a1090').'</a></li>
<li class="active">'.$Title.'</li>
</ol>';

echo '<h1>'.$Title.'</h1>';


echo $Content.'<br /><br />';

if($Comunicat == 'ok')
{
    echo '<div class="alert alert-success">'.$this->lang->line('a1091').'</div>';
}
else
{
    echo '<div class="alert alert-danger">'.$this->lang->line('a1092').'</div>';
}

?>