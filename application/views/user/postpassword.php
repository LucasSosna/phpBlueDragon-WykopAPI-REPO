<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author Lukasz Sosna
 * @copyright 2019
 * @e-mail tree@interia.pl
 * @e-mail support@phpbluedragon.eu
 * @www http://phpbluedragon.eu
 */
 
echo '<h1>'.$Title.'</h1>';

echo $Content.'<br />';

if($change_password)
{
    echo '<br /><div class="alert alert-success">'.$this->lang->line('a0972').'</div>';
}
else
{
    echo '<br /><div class="alert alert-danger">'.$this->lang->line('a0973').'</div>';
}

?>