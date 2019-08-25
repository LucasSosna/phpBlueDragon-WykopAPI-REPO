<?php

/**
 * @author Lukasz Sosna
 * @copyright 2019
 * @e-mail tree@interia.pl
 * @e-mail support@phpbluedragon.eu
 * @www http://phpbluedragon.eu
 */
 
//http://madapaja.github.io/jquery.selection/

echo '<ol class="breadcrumb">
<li><a href="'.base_url().'">'.$this->lang->line('a0981').'</a></li>
<li class="active">'.$Title.'</li>
</ol>';

echo '<h1>'.$Title.'</h1>';

echo $Content.'<br /><br />';

if($IsAdded)
{
    echo '<div class="alert alert-success">'.$this->lang->line('a1119').'</div>';
    $ClearFields = true;
}

if($UploadError)
{
    foreach($UploadError as $Comment)
    {
        echo '<div class="alert alert-danger">'.$Comment.'</div>';
    }
}

if($IsOk == 'no')
{
    echo '<div class="alert alert-danger">'.$this->lang->line('a1120').' '.$CommentId.'</div>';
    $ClearFields = true;
}

if($IsOk == 'ok')
{
    echo '<div class="alert alert-success">'.$this->lang->line('a1121').' <a href="https://www.wykop.pl/wpis/'.$CommentId.'/" target="_blank">https://www.wykop.pl/wpis/'.$CommentId.'/</a></div>';
    $ClearFields = true;
}

if($IsOk2 == 'ok')
{
    echo '<div class="alert alert-success">'.$this->lang->line('a1122').'</div>';
    $ClearFields = true;
}

//https://www.wykop.pl/link/3734151/comment/45294067/#comment-45294067
//echo '<pre>';
//print_r($_FILES);
//echo '</pre>';

//, array('body' => 'komentarz dodany przez API'

echo form_open_multipart('addentry');
echo '<strong>'.$this->lang->line('a1123').'</strong> <br /> '.form_textarea(array('name' => 'comment_body', 'id' => 'comment_body', 'value' => $Vcomment_body, 'class' => 'form-control')).'';
echo form_error('comment_body','<div class="alert alert-danger">','</div>');
?>
<div style="padding-top: 5px;">
    <div id="buttonBold" class="btn btn-info"><?php echo $this->lang->line('a1108'); ?></div>
    <div id="buttonItalic" class="btn btn-info"><?php echo $this->lang->line('a1109'); ?></div>
    <div id="buttonCite" class="btn btn-info"><?php echo $this->lang->line('a1110'); ?></div>
    <div id="buttonLink" class="btn btn-info"><?php echo $this->lang->line('a1111'); ?></div>
    <div id="buttonCode" class="btn btn-info"><?php echo $this->lang->line('a1112'); ?></div>
    <div id="buttonSpoiler" class="btn btn-info"><?php echo $this->lang->line('a1113'); ?></div>
    <div class="btn-group">
      <button type="button" class="btn btn-info">( ͡° ͜ʖ ͡°)</button>
      <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
      </button>
      <ul class="dropdown-menu" id="ListSelectFrom">
        <li><a>( ͡° ʖ̯ ͡°)</a></li>
        <li><a>( ͡º ͜ʖ͡º)</a></li>
        <li><a>( ͡°( ͡° ͜ʖ( ͡° ͜ʖ ͡°)ʖ ͡°) ͡°)</a></li>
        <li><a>(⌐ ͡■ ͜ʖ ͡■)</a></li>
        <li><a>(╥﹏╥)</a></li>
        <li><a>(╯︵╰,)</a></li>
        <li><a>(ʘ‿ʘ)</a></li>
        <li><a>(｡◕‿‿◕｡)</a></li>
        <li><a>ᕙ(⇀‸↼‶)ᕗ</a></li>
        <li><a>ᕦ(òóˇ)ᕤ</a></li>
        <li><a>(✌ ﾟ ∀ ﾟ)☞</a></li>
        <li><a>ʕ•ᴥ•ʔ</a></li>
        <li><a>ᶘᵒᴥᵒᶅ</a></li>
        <li><a>(⌒(oo)⌒)</a></li>
        <li><a>ᄽὁȍ ̪ őὀᄿ</a></li>
        <li><a>( ͡€ ͜ʖ ͡€)</a></li>
        <li><a>( ͡° ͜ʖ ͡°)</a></li>
        <li><a>( ͡° ͜ʖ ͡°)ﾉ⌐■-■</a></li>
        <li><a>(⌐ ͡■ ͜ʖ ͡■)</a></li>
      </ul>
    </div>
</div>
<?php
echo '<br /><br /><strong>'.$this->lang->line('a1124').'</strong> <br /> '.form_input(array('name' => 'comment_file', 'id' => 'comment_file', 'value' => $Vcomment_file, 'class' => 'form-control')).'<br />';
echo form_error('comment_file','<div class="alert alert-danger">','</div>');
echo '<strong>'.$this->lang->line('a1125').'</strong>';
echo '<div class="input-group">
<span class="input-group-btn">
<span class="btn btn-default btn-file">
'.$this->lang->line('a1126').' <input type="file" name="comment_file2" id="comment_file2">
</span>
</span>
<input type="text" class="form-control" readonly style="z-index: 0;">
</div><br />';

/*if($Vperiod_what == 'one' OR $Vperiod_what == '')
{
    $OneSelected = ' selected="selected"';
}
else
{
    $CronSelected = ' selected="selected"';
}

$OptionsList[5] = '5 min';
$OptionsList[10] = '10 min';
$OptionsList[15] = '15 min';
$OptionsList[20] = '20 min';
$OptionsList[25] = '25 min';
$OptionsList[40] = '20 min';
$OptionsList[35] = '35 min';
$OptionsList[40] = '40 min';
$OptionsList[45] = '45 min';
$OptionsList[50] = '50 min';
$OptionsList[55] = '55 min';
$OptionsList[60] = '1 godzina';
$OptionsList[120] = '2 godziny';
$OptionsList[180] = '3 godziny';
$OptionsList[240] = '4 godziny';
$OptionsList[300] = '5 godzin';
$OptionsList[360] = '6 godzin';
$OptionsList[420] = '7 godzin';
$OptionsList[480] = '8 godzin';

echo 'Dodaj:
<select name="period_what" class="form-control">
<option value="one" '.$OneSelected.'>Jednokrotnie</option>
<option value="cron" '.$CronSelected.'>Wielokrotnie</option>
</select>
Od dnia: <input type="text" name="period_date_1" id="period_date_1" value="'.$Vperiod_date_1.'" class="form-control" />
'.form_error('period_date_1','<div class="alert alert-danger">','</div>').'
Do dnia: <input type="text" name="period_date_2" id="period_date_2" value="'.$Vperiod_date_2.'" class="form-control" />
'.form_error('period_date_2','<div class="alert alert-danger">','</div>').'
Co jaki czas dodawać 


'.form_dropdown('period_period', $OptionsList, $Vperiod_period, 'class="form-control"').'


';
*/

echo '<br />';
echo form_hidden('formlogin','yes');
echo form_submit(array('name' => 'buttonstart', 'value' => ''.$this->lang->line('a1127').'', 'class' => 'btn btn-info btn-block'));
echo form_close();

?>
<script>

$('#period_date_1').datetimepicker({
    timeFormat: "HH:mm:ss",
    dateFormat: "yy-mm-dd"
});

$('#period_date_2').datetimepicker({
    timeFormat: "HH:mm:ss",
    dateFormat: "yy-mm-dd"
});

$( "#ListSelectFrom" ).on("click","li", function(event) { 
    $('#comment_body')
        .selection('insert', {text: $(this).text(), mode: 'before'});
});
    
$("#buttonBold").click(function() {
      $('#comment_body')
        .selection('insert', {text: '**', mode: 'before'})
        .selection('insert', {text: '**', mode: 'after'});
});

$("#buttonItalic").click(function() {
      $('#comment_body')
        .selection('insert', {text: '_', mode: 'before'})
        .selection('insert', {text: '_', mode: 'after'});
});

$("#buttonCite").click(function() {
      $('#comment_body')
        .selection('insert', {text: '> ', mode: 'before'});
});

$("#buttonLink").click(function() {
      $('#comment_body')
        .selection('insert', {text: '[<?php echo $this->lang->line('a1118'); ?>](http://www.wykop.pl)', mode: 'before'});
});

$("#buttonCode").click(function() {
      $('#comment_body')
        .selection('insert', {text: '`', mode: 'before'})
        .selection('insert', {text: '`', mode: 'after'});
});

$("#buttonSpoiler").click(function() {
      $('#comment_body')
        .selection('insert', {text: '! ', mode: 'before'});
});

</script>
<script>
$(document).on('change', '.btn-file :file', function() {
    var input = $(this),
    numFiles = input.get(0).files ? input.get(0).files.length : 1,
    label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
});

$(document).ready( function() {
    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
    
        var input = $(this).parents('.input-group').find(':text'),
        log = numFiles > 1 ? numFiles + ' files selected' : label;
        
        if( input.length ) {
        input.val(log);
        } else {
        if( log ) alert(log);
        }
    
    });
});
</script>           