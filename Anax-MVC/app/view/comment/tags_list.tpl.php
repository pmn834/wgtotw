<h1><?=$title?></h1>
<div class='tags_list'>
    <div class="q_tags">
        <p>
        <?php foreach ($tags as $id => $tag) : ?>
            <a href="<?=$this->url->create('')?>/question/tag/<?=$tag['name']?>"><?=$tag['name']?></a>
        <?php endforeach; ?>
        </p>
    </div>
</div>
