<hr class='comments_hr' />
<?php if (is_array($comments)) : ?>
<div class='comments_header'>
    <h2>Comments</h2>
    <div class='comments_admin'>
        <form method=post>
            <input type=hidden name="redirect" value="<?=$this->url->create('' . $this->request->getRoute())?>">
            <input type='submit' name='doRemoveAll' value='Remove all comments' onClick="this.form.action = '<?=$this->url->create('comment/remove-all/' . $this->request->getRoute())?>'"/>
        </form>
    </div>
</div>
<div class='comments'>
<?php foreach ($comments as $id => $comment) : ?>
    <div class='comment'>
        <div class='commenter_info'>
            <p class='commenter_name'><?=$comment['name']?></p>
            <img src='<?=$this->CommentController->getGravatar($comment['mail'], 80)?>' alt="<?=$comment['name']?>" />
            <p class='commenter_email_web'><?=$comment['mail']?></p>
            <p class='commenter_email_web'><?=$comment['web']?></p>
        </div>
        <div class='comment_main'>
            <p class='comment_timestamp'><?=$comment['timestamp']?></p>
            <p><?=$comment['content']?></p>
        </div>
    </div>
    <div class='comment_admin'>
        <form method=post>
            <input type=hidden name="redirect" value="<?=$this->url->create('' . $this->request->getRoute())?>">
            <input type='submit' name='doEdit' value='Edit comment' onClick="this.form.action = '<?=$this->url->create('comment/edit/' . $comment['id'])?>'"/>
            <input type='submit' name='doRemove' value='Remove comment' onClick="this.form.action = '<?=$this->url->create('comment/remove/' . $comment['id'])?>'"/>
        </form>
    </div>
<?php endforeach; ?>
</div>
<?php endif; ?>
