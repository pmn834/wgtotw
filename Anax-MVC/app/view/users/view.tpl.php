<h1><?=$user['acronym']?></h1>
<div>
    <div style="float: left; margin-right: 1em">
        <img src='<?=$this->CommentController->getGravatar($user['email'], 100)?>' alt="<?=$user['acronym']?>" />
    </div>
    <div style="padding-top: 1.5em">
        <p>
            <strong>Namn:</strong> <?=$user['name']?><br />
            <strong>Email:</strong> <?=$user['email']?>
        </p>
    </div>
</div>
<p style="clear:both; margin: 3em 0 0 0 "><strong>Om mig</strong></p>
<p style="clear:both"><?=$user['description']?></p>
<hr class='comments_hr' style="margin-top: 2em" />
<?=$this->UserController->formatUserPosts($user,$posts)?>
