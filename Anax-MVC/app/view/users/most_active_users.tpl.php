<div id='sidebar' class='most_active_users'>
    <h2><?=$title?></h2>
    <?php foreach ($users as $user) : ?>
        <div class="user_list">
            <a href="<?=$this->url->create('')?>/user/id/<?=$user['userId']?>"><img src='<?=$this->CommentController->getGravatar($user['userEmail'], 40)?>' alt="<?=$user['userAcronym']?>" /></a><a href="<?=$this->url->create('')?>/user/id/<?=$user['userId']?>"><?=$user['userAcronym']?></a> 
        </div>
    <?php endforeach; ?>
</div>