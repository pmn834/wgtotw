<h1><?=$title?></h1>

<?php foreach ($users as $user) : ?>


<a href="<?=$this->url->create('')?>/user/id/<?=$user['id']?>"><img src='<?=$this->CommentController->getGravatar($user['email'], 40)?>' alt="<?=$user['acronym']?>" /></a>



<p><a href="<?=$this->url->create('')?>/user/id/<?=$user['id']?>"><?=$user['acronym']?></a></p>

<?php endforeach; ?>

<p>Totalt antal användare: <?=count($users)?></p