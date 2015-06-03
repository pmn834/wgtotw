<h1><?=$title?></h1>
<div class='questions_list'>
<?php foreach ($questions['questions'] as $key => $q) : ?>
    <div class='question'>
        <div class='comment_main'>
            <p><a href="<?=$this->url->create('')?>/question/view/<?=$q[0]['id']?>"><?=$q[0]['title']?></a></p>
        </div>
        <div class="q_tags">
            <?php foreach ($q[1] as $id => $tag) : ?>
                <a href="<?=$this->url->create('')?>/question/tag/<?=$tag['id']?>"><?=$tag['name']?></a>
            <?php endforeach; ?>
        </div>
        <div class='question_info'>
            <p>Frågan publicerades <?=date("j M Y", strtotime($q[0]['created']))?> av <a href="<?=$this->url->create('')?>/user/id/<?=$q[0]['userId']?>"><?=$q[0]['userAcronym']?></a>.</p>
        </div>
    </div>
<?php endforeach; ?>
</div>
