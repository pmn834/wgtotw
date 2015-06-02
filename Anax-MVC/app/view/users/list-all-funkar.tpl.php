<h1><?=$title?></h1>

<?php foreach ($users as $user) : ?>
<tr> 
<p><a href="<?=$this->url->create('')?>/users/id/<?=$user['id']?>"><?=$user['acronym']?></a></p>
<p><?=var_dump($user)?></p>
 
<?php endforeach; ?>
 
<p><a href='<?=$this->url->create('')?>'>Home</a></p> 