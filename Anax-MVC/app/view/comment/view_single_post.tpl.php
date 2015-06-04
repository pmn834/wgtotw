<div class='view_post'>
    <div class='post'>
        <h2><?=$presentation[0]['title']?></h2>
        <?=$this->textFilter->doFilter($presentation[0]['text'], 'markdown');?>
        <div class='question_user_info'>
            <div class="user_frame"><a href="<?=$this->url->create('')?>/user/id/<?=$presentation[0]['userId']?>"><img src='<?=$this->CommentController->getGravatar($presentation[0]['userEmail'], 40)?>' alt="<?=$presentation[0]['userAcronym']?>" /></a>
            <span class='username'><a href="<?=$this->url->create('')?>/user/id/<?=$presentation[0]['userId']?>"><?=$presentation[0]['userAcronym']?></a></span><br />
            <?=date("j M Y, H:i", strtotime($presentation[0]['created']))?></div>
        </div>
        <div class="q_tags">
            <?php if (!empty($presentation[1])) : ?>
                <?php foreach ($presentation[1] as $id => $tag) : ?>
                    <a href="<?=$this->url->create('')?>/question/tag/<?=$tag['id']?>"><?=$tag['name']?></a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php $upd = is_null($presentation[0]['updated']) ? null : "<span style='font-size: 0.8em; color: #888'>Uppdaterad: " . date("d M Y, H:i", strtotime($presentation[0]['updated'])) . "</span>"; echo $upd; ?>
    </div>
</div>
<hr class='comments_hr' />
