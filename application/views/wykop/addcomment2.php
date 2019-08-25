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

echo $Content.'';

if($IsAdded)
{
    echo '<div class="alert alert-success">'.$this->lang->line('a1104').'</div>';
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
    echo '<div class="alert alert-danger">Błąd: '.$CommentId.'</div>';
    $ClearFields = true;
}

if($IsOk == 'ok')
{
    echo '<div class="alert alert-success">'.$this->lang->line('a1105').' <a href="https://www.wykop.pl/link/'.$EntryId.'/comment/'.$CommentId.'/#comment-'.$CommentId.'">https://www.wykop.pl/link/'.$EntryId.'/comment/'.$CommentId.'/#comment-'.$CommentId.'</a></div>';
    $ClearFields = true;
}

//https://www.wykop.pl/link/3734151/comment/45294067/#comment-45294067
//echo '<pre>';
//print_r($_FILES);
//echo '</pre>';

//, array('body' => 'komentarz dodany przez API'

echo form_open_multipart('addcomment2/'.$EntryId);
echo '<h2>'.$this->lang->line('a1106').'</h2>';
echo '<strong>'.$this->lang->line('a1107').'</strong> <br /> '.form_textarea(array('name' => 'comment_body', 'id' => 'comment_body', 'value' => $Vcomment_body, 'class' => 'form-control')).'';
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
echo '<br /><br /><strong>'.$this->lang->line('a1114').'</strong> <br /> '.form_input(array('name' => 'comment_file', 'id' => 'comment_file', 'value' => $Vcomment_file, 'class' => 'form-control')).'<br />';
echo form_error('comment_file','<div class="alert alert-danger">','</div>');
echo '<strong>'.$this->lang->line('a1115').'</strong>';
echo '<div class="input-group">
<span class="input-group-btn">
<span class="btn btn-default btn-file">
'.$this->lang->line('a1116').' <input type="file" name="comment_file2" id="comment_file2">
</span>
</span>
<input type="text" class="form-control" readonly>
</div><br />';

echo form_hidden('formlogin','yes');
echo form_submit(array('name' => 'buttonstart', 'value' => ''.$this->lang->line('a1117').'', 'class' => 'btn btn-info btn-block'));
echo form_close();

?>
<script>

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