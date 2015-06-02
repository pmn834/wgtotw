<div class='view_question'>
    <div class='question'>
        <h2><?=$presentation['question']['title']?></h2>
        <?=$this->textFilter->doFilter($presentation['question']['text'], 'markdown');?>
        <div class='question_user_info'>
            <div class="user_frame"><a href="<?=$this->url->create('')?>/user/id/<?=$presentation['question']['userId']?>"><img src='<?=$this->CommentController->getGravatar($presentation['question']['userEmail'], 40)?>' alt="<?=$presentation['question']['userAcronym']?>" /></a>
            <span class='username'><a href="<?=$this->url->create('')?>/user/id/<?=$presentation['question']['userId']?>"><?=$presentation['question']['userAcronym']?></a></span><br />
            <?=date("j M Y, H:i", strtotime($presentation['question']['created']))?></div>
        </div>
        <div class="q_tags">
            <?php foreach ($presentation['tags'] as $id => $tag) : ?>
                <a href="<?=$this->url->create('')?>/question/tag/<?=$tag?>"><?=$tag?></a>
            <?php endforeach; ?>
        </div>
        <?php $upd = is_null($presentation['question']['updated']) ? null : "<span style='font-size: 0.8em; color: #888'>Uppdaterad: " . date("j M Y, H:i", strtotime($presentation['question']['updated'])) . "</span>"; echo $upd; ?>
    </div>
    <div class='answers'>
        <p class='answer_count'><?=count($presentation['answers'])?> svar</p>
        <hr class='comments_hr' />
        <?php foreach ($presentation['answers'] as $id => $answer) : ?>
            <div class='answer'>
                <div class='answer_text'><p> <?=$this->textFilter->doFilter($answer['text'], 'markdown');?></p></div>
                <div class='answer_user_info'><div class="user_frame"><a href="<?=$this->url->create('')?>/user/id/<?=$answer['userId']?>"><img src='<?=$this->CommentController->getGravatar($answer['userEmail'], 40)?>' alt="<?=$answer['userAcronym']?>" /></a><a href="<?=$this->url->create('')?>/user/id/<?=$answer['userId']?>"><?=$answer['userAcronym']?></a><br /><?=date("j M Y, H:i", strtotime($answer['created']))?>
                </div>
                <?php $upd = is_null($answer['updated']) ? null : "<p style='text-align: left; clear:both; font-size: 0.8em; margin: 0.5em; color: #888'>Uppdaterad: " . date("j M Y, H:i", strtotime($answer['updated'])) . "</p>"; echo $upd; ?>
            </div>
            <?php foreach ($presentation['comments'] as $id => $comment) : ?>
                <?php if($comment['parentId'] == $answer['id']) : ?>
                    <div class='comment'>
                    <div class='comment_text'><p> <?=$this->textFilter->doFilter($comment['text'], 'markdown');?></p></div>
                    <div class='comment_user_info'><div class="user_frame"><a href="<?=$this->url->create('')?>/user/id/<?=$comment['userId']?>"><img src='<?=$this->CommentController->getGravatar($comment['userEmail'], 40)?>' alt="<?=$comment['userAcronym']?>" /></a><a href="<?=$this->url->create('')?>/user/id/<?=$comment['userId']?>"><?=$comment['userAcronym']?></a><br /><?=date("j M Y, H:i", strtotime($comment['created']))?>
                    </div>
                    <?php $upd = is_null($comment['updated']) ? null : "<p style='text-align: left; clear:both; font-size: 0.8em; margin: 0.5em; color: #888'>Uppdaterad: " . date("j M Y, H:i", strtotime($comment['updated'])) . "</p>"; echo $upd; ?>
                    </div>
                    </div>
                <?php endif ?>
            <?php endforeach; ?>
            <?php if($this->UserauthenticationController->isAuthenticated()) : ?>
                <p style='padding-bottom: 1em'><a href="<?=$this->url->create('')?>/question/addComment/<?=$answer['id']?>" style='float:right;font-size:0.8em'>Skriv en kommentar till detta svar</a></p>
            <?php endif ?>
            </div>
            <hr class='comments_hr' />
        <?php endforeach; ?>
    </div>
    <div class='comments'>
        <p>
            <span style='font-weight: bold; font-size: 1.2em'>Kommentarer till frågan</span>
            <?php if($this->UserauthenticationController->isAuthenticated()) : ?>
                <a href="<?=$this->url->create('')?>/question/addComment/<?=$presentation['question']['id']?>" style='float:right;font-size:0.8em'>Skriv en kommentar</a>
             <?php endif ?>
        </p>
        <?php foreach ($presentation['comments'] as $id => $comment) : ?>
            <?php if($comment['parentId'] == $presentation['question']['id']) : ?>
                <div class='comment'>
                <div class='comment_text'><p> <?=$this->textFilter->doFilter($comment['text'], 'markdown');?></p></div>
                <div class='comment_user_info'><div class="user_frame"><a href="<?=$this->url->create('')?>/user/id/<?=$comment['userId']?>"><img src='<?=$this->CommentController->getGravatar($comment['userEmail'], 40)?>' alt="<?=$comment['userAcronym']?>" /></a><a href="<?=$this->url->create('')?>/user/id/<?=$comment['userId']?>"><?=$comment['userAcronym']?></a><br /><?=date("j M Y, H:i", strtotime($comment['created']))?>
                </div>
                <?php $upd = is_null($comment['updated']) ? null : "<p style='text-align: left; clear:both; font-size: 0.8em; margin: 0.5em; color: #888'>Uppdaterad: " . date("j M Y, H:i", strtotime($comment['updated'])) . "</p>"; echo $upd; ?>
                </div>
                </div>
            <?php endif ?>
        <?php endforeach; ?>
    </div>
</div> 

