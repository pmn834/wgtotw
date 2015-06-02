<div class='edit-form'>
    <form method=post>
        <input type=hidden name="redirect" value="<?=$this->url->create('' . $commentToEdit['page'])?>">
        <fieldset>
        <legend>Edit comment</legend>
        <p><label>Comment:<br/><textarea name='content'><?=$commentToEdit['content']?></textarea></label></p>
        <p><label>Name:<br/><input type='text' name='name' value='<?=$commentToEdit['name']?>'/></label></p>
        <p><label>Homepage:<br/><input type='text' name='web' value='<?=$commentToEdit['web']?>'/></label></p>
        <p><label>Email:<br/><input type='text' name='mail' value='<?=$commentToEdit['mail']?>'/></label></p>
        <p class=buttons>
            <input type='submit' name='doSaveEdit' value='Save Comment' onClick="this.form.action = '<?=$this->url->create('comment/save-edit/' . $commentToEdit['commentID'])?>'"/>
            <input type='reset' value='Reset'/>
            <input type='submit' name='doRemove' value='Delete Comment' onClick="this.form.action = '<?=$this->url->create('comment/remove/' . $commentToEdit['commentID'])?>'"/>
        </p>
        </fieldset>
    </form>
</div>
