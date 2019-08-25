<?php

/**
 * @author Lukasz Sosna
 * @copyright 2019
 * @e-mail tree@interia.pl
 * @e-mail support@phpbluedragon.eu
 * @www http://phpbluedragon.eu
 */

echo '<ol class="breadcrumb">
<li class="active">'.$this->lang->line('a0981').'</li>
</ol>';

echo '<h1>'.$this->lang->line('a0981').'</h1>';

echo '<br />';

echo '<div class="row">
<div class="col-md-6 text-left"><h2>'.$this->lang->line('a1167').'</h2></div>
<div class="col-md-2 text-left"></div>
<div class="col-md-4 text-left"><h2>'.$this->lang->line('a1168').'</h2></div>
</div>';

echo '<br />';

echo '<div class="row">
<div class="col-md-2 text-center"><a href="'.base_url('access').'"><img src="'.base_url('images/access.png').'" width="64" class="center-block" /><br />'.$this->lang->line('a1169').'</a></div>
<div class="col-md-2 text-center"><a href="'.base_url('wykop/promoted').'"><img src="'.base_url('images/top.png').'" width="64" class="center-block" /><br />'.$this->lang->line('a1170').'</a></div>
<div class="col-md-2 text-center"><a href="'.base_url('wykop/upcoming').'"><img src="'.base_url('images/upcoming.png').'" width="64" class="center-block" /><br />'.$this->lang->line('a1171').'</a></div>
<div class="col-md-2 text-center"></div>
<div class="col-md-2 text-center"><a href="'.base_url('addcomment').'"><img src="'.base_url('images/comment.png').'" width="64" class="center-block" /><br />'.$this->lang->line('a1172').'</a></div>
<div class="col-md-2 text-center"><a href="'.base_url('addentry').'"><img src="'.base_url('images/entry.png').'" width="64" class="center-block" /><br />'.$this->lang->line('a1173').'</a></div>
</div>';

echo '<br />';

echo '<div class="row">
<div class="col-md-8 text-left"><h2>'.$this->lang->line('a1174').'</h2></div>
<div class="col-md-2 text-left"></div>
</div>';

echo '<br />';

echo '<div class="row">
<div class="col-md-2 text-center"><a href="'.base_url('search/link').'"><img src="'.base_url('images/search.png').'" width="64" class="center-block" /><br />'.$this->lang->line('a1175').'</a></div>
<div class="col-md-2 text-center"><a href="'.base_url('search/hits/year').'"><img src="'.base_url('images/search.png').'" width="64" class="center-block" /><br />'.$this->lang->line('a1176').'</a></div>
<div class="col-md-2 text-center"><a href="'.base_url('search/hits/month').'"><img src="'.base_url('images/search.png').'" width="64" class="center-block" /><br />'.$this->lang->line('a1177').'</a></div>
<div class="col-md-2 text-center"><a href="'.base_url('search/hits/2months').'"><img src="'.base_url('images/search.png').'" width="64" class="center-block" /><br />'.$this->lang->line('a1178').'</a></div>
<div class="col-md-2 text-center"><a href="'.base_url('search/hits/3months').'"><img src="'.base_url('images/search.png').'" width="64" class="center-block" /><br />'.$this->lang->line('a1179').'</a></div>

</div>';

echo '<br />';

echo '<div class="row">
<div class="col-md-8 text-left"><h2>'.$this->lang->line('a1180').'</h2></div>
<div class="col-md-2 text-left"></div>
</div>';

echo '<br />';

echo '
<div class="row">
<div class="col-md-2 text-center"><a href="'.base_url('searchmicroblog').'"><img src="'.base_url('images/search.png').'" width="64" class="center-block" /><br />'.$this->lang->line('a1181').'</a></div>
<div class="col-md-2 text-center"><a href="'.base_url('microblog').'"><img src="'.base_url('images/microblog.png').'" width="64" class="center-block" /><br />'.$this->lang->line('a1182').'</a></div>
<div class="col-md-2 text-center"><a href="'.base_url('hotfrommicroblog').'"><img src="'.base_url('images/hot.png').'" width="64" class="center-block" /><br />'.$this->lang->line('a1183').'</a></div>
<div class="col-md-2 text-center"><a href="'.base_url('tagsfrommicroblog').'"><img src="'.base_url('images/tag.png').'" width="64" class="center-block" /><br />'.$this->lang->line('a1184').'</a></div>
</div>';

echo '<br />';

echo '<div class="row">
<div class="col-md-10 text-left"><h2>'.$this->lang->line('a1185').'</h2><br />'.$this->lang->line('a1186').'</div>
<div class="col-md-2 text-left"></div>
</div>';

echo '<br />';

echo '
<div class="row">
<div class="col-md-2 text-center"><a href="'.base_url('topplusminus').'"><img src="'.base_url('images/plusminus.png').'" width="64" class="center-block" /><br />'.$this->lang->line('a1187').'</a></div>
<div class="col-md-2 text-center"><a href="'.base_url('digplusminus').'"><img src="'.base_url('images/plusminus.png').'" width="64" class="center-block" /><br />'.$this->lang->line('a1188').'</a></div>
<div class="col-md-2 text-center"><a href="'.base_url('microblogplus').'"><img src="'.base_url('images/plus.png').'" width="64" class="center-block" /><br />'.$this->lang->line('a1189').'</a></div>
<div class="col-md-2 text-center"><a href="'.base_url('topcommplusminus').'"><img src="'.base_url('images/plusminus.png').'" width="64" class="center-block" /><br />'.$this->lang->line('a1190').'</a></div>
<div class="col-md-2 text-center"><a href="'.base_url('digcommplusminus').'"><img src="'.base_url('images/plusminus.png').'" width="64" class="center-block" /><br />'.$this->lang->line('a1191').'</a></div>
<div class="col-md-2 text-center"><a href="'.base_url('cronsetup').'"><img src="'.base_url('images/cron.png').'" width="64" class="center-block" /><br />'.$this->lang->line('a1192').'</a></div>
</div>
';

echo '<br />';

/*
<div class="col-md-2 text-center"><a href="'.base_url('access').'"><img src="'.base_url('images/access.png').'" width="64" class="center-block" /><br />Plus/Minus dla komentarzy użytkownika</a></div>
<div class="row">
<div class="col-md-2 text-center"><a href="'.base_url('access').'"><img src="'.base_url('images/access.png').'" width="64" class="center-block" /><br />Plus/Minus dla wpisów według użytkownika</a></div>
<div class="col-md-2 text-center"><a href="'.base_url('access').'"><img src="'.base_url('images/access.png').'" width="64" class="center-block" /><br />Plus/Minus dla wpisów według tagu</a></div>
<div class="col-md-2 text-center"><a href="'.base_url('access').'"><img src="'.base_url('images/access.png').'" width="64" class="center-block" /><br />Plus/Minus dla wpisów według frazy</a></div>
<div class="col-md-2 text-center"><a href="'.base_url('access').'"><img src="'.base_url('images/access.png').'" width="64" class="center-block" /><br />Plus dla mikrobloga użytkownika (wpisy)</a></div>
<div class="col-md-2 text-center"><a href="'.base_url('access').'"><img src="'.base_url('images/access.png').'" width="64" class="center-block" /><br />Plus dla mikrobloga wegług tagu (wpisy)</a></div>
<div class="col-md-2 text-center"><a href="'.base_url('access').'"><img src="'.base_url('images/access.png').'" width="64" class="center-block" /><br />Plus dla mikrobloga według frazy (wpisy)</a></div>

</div>
*/

?>