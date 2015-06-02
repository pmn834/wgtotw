<h1>Mina sidor: <?=$user['acronym']?></h1>
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
<p class="post_edit_link" style="margin: 0; width:160px; text-align:center; padding: 1em"><a href="<?=$this->url->create('') . "/Userauthentication/editprofile"?>">Redigera användarprofil</a></p>
<hr class='comments_hr' style="margin-top: 2em" />
<p class="post_edit_link" style="margin: 0; width:160px; text-align:center; padding: 1em"><a href="<?=$this->url->create('') . "/question/addQuestion"?>">Skapa en ny fråga</a></p>
<hr class='comments_hr' style="margin-top: 2em" />
<?=$this->UserController->formatUserPosts($user,$posts,'true')?>

