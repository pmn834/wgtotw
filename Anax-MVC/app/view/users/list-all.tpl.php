<h1><?=$title?></h1>

<?php foreach ($users as $user) : ?>
<div class="user_list">
    <a href="<?=$this->url->create('')?>/user/id/<?=$user['id']?>"><img src='<?=$this->CommentController->getGravatar($user['email'], 40)?>' alt="<?=$user['acronym']?>" /></a><a href="<?=$this->url->create('')?>/user/id/<?=$user['id']?>"><?=$user['acronym']?></a>
</div>
<?php endforeach; ?>