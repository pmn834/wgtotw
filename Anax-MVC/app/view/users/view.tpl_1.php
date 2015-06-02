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
<hr class='comments_hr' />

<?php 

// User questions
echo "<p style='clear:both; margin: 3em 0 0 0 '><strong>Frågor av " . $user['acronym'] ."</strong></p>";

if(!empty ($posts['questions'])) {
    foreach ($posts['questions'] as $question) {
        echo "<p><a href='" . $this->url->create('') . "/question/view/" . $question['id'] ."'>" . $question['title']. "</a> <span style='color: #999; font-size: 0.8em; margin-left: 1em'>" . date("d M Y", strtotime($question['created'])) . "</span></p>";
    }
}
else {
    echo "<p style='color: #999; font-size: 0.8em'>" . $user['acronym'] . " har inte publicerat några frågor.";
}

// User answers
echo "<p style='clear:both; margin: 3em 0 0 0 '><strong>Svar av " . $user['acronym'] ."</strong></p>";

if(!empty ($posts['answers'])) {
    foreach ($posts['answers'] as $answer) {
        echo "<p><a href='" . $this->url->create('') . "/question/view/" . $answer['questionId'] ."'>" . $answer['title']. "</a> <span style='color: #999; font-size: 0.8em; margin-left: 1em'>" . date("d M Y", strtotime($answer['created'])) . "</span></p>";
    }
}
else {
    echo "<p style='color: #999; font-size: 0.8em'>" . $user['acronym'] . " har inte publicerat några svar.";
}

// User comments
echo "<p style='clear:both; margin: 3em 0 0 0 '><strong>Kommentarer av " . $user['acronym'] ."</strong></p>";

if(!empty ($posts['comments'])) {
    foreach ($posts['comments'] as $answer) {
        echo "<p><a href='" . $this->url->create('') . "/question/view/" . $answer['questionId'] ."'>" . $answer['title']. "</a> <span style='color: #999; font-size: 0.8em; margin-left: 1em'>" . date("d M Y", strtotime($answer['created'])) . "</span></p>";
    }
}
else {
    echo "<p style='color: #999; font-size: 0.8em'>" . $user['acronym'] . " har inte publicerat några kommentarer.";
}

?>
