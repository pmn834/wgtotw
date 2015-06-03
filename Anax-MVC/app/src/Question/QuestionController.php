<?php

namespace Anax\Question;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class QuestionController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;
    
    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        $this->comments = new \Anax\Question\Question();
        $this->comments->setDI($this->di);
    }

    /**
     * Output a list of the latest questions.
     *    
     * @param integer $limit the limit of questions to fetch - default 10.
     * 
     * @return void
     */
    public function getLatestQuestionsAction($limit=10)
    {
        $qry = "SELECT * FROM mvc_VQuestion
                WHERE commentTypeId=1
                ORDER BY created DESC
                LIMIT " . $limit;
                
        $all = $this->db->executeFetchAll($qry);

        $questions = array();
                              
        foreach ($all as $comment) {
            $question = json_decode(json_encode($comment),TRUE);
            $tags = $question['tags'];
            $tagsSep = explode(",", $tags);
            $questions[] = array($question, $tagsSep);
        }

        $this->views->add('comment/questions_list', [
            'title' => 'Senast publicerade frågor',
            'questions' => $questions,
        ]);
        
    }
    
    /**
     * Output list of the most active users.
     *    
     * @param integer $limit the limit of users to fetch - default 3
     * 
     * @return void
     */
    public function mostActiveUsersAction($limit=3)
    {
        $qry = "SELECT *, COUNT(userId)
                FROM mvc_comments
                GROUP BY userId
                ORDER BY COUNT(*) DESC
                LIMIT " . $limit;
        
        $all = $this->db->executeFetchAll($qry);
        $users = json_decode(json_encode($all),TRUE);

        $this->views->add('users/most_active_users', [
            'title' => 'Mest aktiva medlemmar',
            'users' => $users,
        ]);
    }
    
    /**
     * Output list of the most popular tags.
     *    
     * @param integer $limit the limit of tags to fetch - default 8
     * 
     * @return void
     */
    public function mostActiveTagsAction($limit=8)
    {
        $qry = "SELECT *, COUNT(mvc_tag2question.idTag) 
                FROM mvc_tag
                INNER JOIN mvc_tag2question
                ON mvc_tag.id=mvc_tag2question.idTag
                GROUP BY mvc_tag2question.idTag
                ORDER BY COUNT(mvc_tag2question.idTag) DESC
                LIMIT " . $limit;

        $all = $this->db->executeFetchAll($qry);
        $tags = json_decode(json_encode($all),TRUE);

        $this->views->add('comment/most_active_tags', [
            'title' => 'Mest populära taggar',
            'tags' => $tags,
        ]);
    }
    
    /**
     * Get question information by its id.
     *    
     * @param integer $id the id to fetch.
     * 
     * @return array with the data for the question.
     */
    public function getQuestionById($id=null)
    {
        $qry = "SELECT * FROM mvc_VQuestion
                WHERE id=?";
                
        $all = $this->db->executeFetchAll($qry, array($id));

        $question = json_decode(json_encode($all[0]),TRUE);
        $tags = $question['tags'];
        $tagsSep = explode(",", $tags);

        $question_data = array($question, $tagsSep);

        return $question_data;
    }
    
    /**
     * Get page for a specific question.
     *    
     * @param integer $qid the question ID to fetch.
     * 
     * @return void
     */
    public function viewAction($qid=null)
    {
        // Define array for presentaion storage
        $presentation = array();
        
        // Get the question
        $qry = "SELECT * FROM mvc_VQuestion
                WHERE commentTypeId=1
                AND id=?
                ORDER BY created DESC";

        $all = $this->db->executeFetchAll($qry,array($qid));

        $question = json_decode(json_encode($all),TRUE); 
        
        // Separate the defined tags for this question.
        $tags = $question[0]['tags'];
        $tagsSep = explode(",", $tags);

        // Store question to presentation array
        $presentation['question'] = $question[0];
        $presentation['tags'] = $tagsSep;
 
        // Get the answers
        $qry = "SELECT * FROM mvc_VQuestion
                WHERE commentTypeId=2
                AND questionId=?";
        
        $all = $this->db->executeFetchAll($qry,array($qid));
        
        $answers = array();

        foreach ($all as $answer) {
            $answers[] = $answer;
        }

        $answers = json_decode(json_encode($answers),TRUE); 
        
        // Store answers to presentation array
        $presentation['answers'] = $answers;
        
        // Get the comments
        $qry = "SELECT * FROM mvc_VQuestion
                WHERE commentTypeId=3
                AND questionId=?";
        $all = $this->db->executeFetchAll($qry,array($qid));
        $comments = array();
        foreach ($all as $comment) {
            $comments[] = $comment;
        }
        $comments = json_decode(json_encode($comments),TRUE); 
        
        // Store answers to presentation array
        $presentation['comments'] = $comments;
        
        
        $this->theme->setTitle($question[0]['title']);
        $this->views->add('comment/view_question', [
            'presentation' => $presentation,
        ]);

    }

    /**
     * Add a new question.
     *  
     * @return void
     */
    public function addQuestionAction()
    {

        if($this->UserauthenticationController->isAuthenticated()) {
            
            $form = new \Mos\HTMLForm\CForm(array(), array(
                'title' => array(
                    'type'        => 'text',
                    'label'       => 'Rubrik:',
                    'required'    => true,
                    'validation'  => array('not_empty'),
                ), 
                'text' => array(
                    'type'        => 'textarea',
                    'label'       => 'Text:',
                    'required'    => true,
                    'validation'  => array('not_empty'),
                ),   
                'tags' => array(
                    'type'        => 'text',
                    'label'       => 'Taggar (separeras med kommatecken):',
                    'required'    => true,
                    'validation'  => array('not_empty'),
                ),   
                'submit' => array(
                      'type'      => 'submit',
                      'value'      => 'Publicera fråga',
                      'callback'  => function($form) {
                        return true;
                      }
                    ),
              )
            );
        
            // Check the status of the form
            $status = $form->Check();
            
            // What to do if the form was submitted
            if($status === true) {
                
                 $now = gmdate('Y-m-d H:i:s');
                
                $sql = "INSERT INTO mvc_comments (commentTypeId, userId, userAcronym, userEmail, title, text, created) VALUES ('1', '" . $_SESSION['user']->id . "', '" . $_SESSION['user']->acronym . "', '" . $_SESSION['user']->email . "', '" . $form->Value('title') . "', '" . $form->Value('text') . "', '" . $now . "')";
                $res = $this->db->executeFetchAll($sql);
                $qid = $this->db->lastInsertId();
                
                // Separate the defined tags for this question.
                $tagsSep = explode(",", strtolower($form->Value('tags')));
                foreach ($tagsSep as $key => $val) {
                    if (!$this->tagExistAction($val)) {
                        $sql = "INSERT INTO mvc_tag (name) VALUES (?)";
                        $res = $this->db->executeFetchAll($sql,array(trim($val)));
                        $tagId = $this->db->lastInsertId();
                    }
                    else {
                        $tagId = $this->getTagIdAction($val);
                    }
                    $sql = "INSERT INTO mvc_tag2question (idQuestion, idTag) VALUES (?, ?)";
                    $res = $this->db->executeFetchAll($sql,array($qid, $tagId));
                }
                
                $url = $this->url->create('') . '/question/view/' . $qid;
                $this->response->redirect($url);
            }
            
            // What to do when form could not be processed
            else if($status === false){
              header("Location: " . $_SERVER['PHP_SELF']);
            }
            
            $this->theme->setTitle("Skapa en ny fråga");
    
            $this->views->add('comment/form', [
                'title' => "Skapa en ny fråga",
                'content' => $form->getHTML()
            ]);
            
        }
    }
    
    /**
     * Add an answer to a question.
     * 
     * @param integer $qid the question which to add this answer
     * @param string  $qt the question title
     *  
     * @return void
     */
    public function addAnswerAction($qid=null,$qt=null)
    {

        if($this->UserauthenticationController->isAuthenticated()) {
            
            $form = new \Mos\HTMLForm\CForm(array(), array(
                'text' => array(
                    'type'        => 'textarea',
                    'label'       => 'Text:',
                    'required'    => true,
                    'validation'  => array('not_empty'),
                ),   
                'submit' => array(
                      'type'      => 'submit',
                      'value'      => 'Publicera svar',
                      'callback'  => function($form) {
                        return true;
                      }
                    ),
              )
            );
        
            // Check the status of the form
            $status = $form->Check();
            
            // What to do if the form was submitted
            if($status === true) {
                
                 $post = $this->getQuestionById($qid);
                 $qt = $post[0]['title'];
                
                 $now = gmdate('Y-m-d H:i:s');
                
                 $sql = "INSERT INTO mvc_comments (commentTypeId, questionId, title, userId, userAcronym, userEmail, text, created) VALUES ('2', '" . $qid . "','" . $qt . "','" . $_SESSION['user']->id . "', '" . $_SESSION['user']->acronym . "', '" . $_SESSION['user']->email . "', '" . $form->Value('text') . "', '" . $now . "')";
                $res = $this->db->executeFetchAll($sql);
                $qw = '/question/view/' . $qid;
                $url = $this->url->create('') . $qw;
                $this->response->redirect($url);
            }
            
            // What to do when form could not be processed
            else if($status === false){
              header("Location: " . $_SERVER['PHP_SELF']);
            }
            
            $post = $this->getQuestionById($qid);
            
            $this->views->add('comment/view_single_post', [
                'presentation' => $post
            ]);

            $this->views->add('comment/form', [
                'title' => "Publicera ett svar",
                'content' => $form->getHTML()
            ]);
            
        }
    }
    
    /**
     * Add a comment to a question or an answer.
     * 
     * @param string $parent the parent question/answer to comment on
     *  
     * @return void
     */
    public function addCommentAction($parent=null)
    {

        if($this->UserauthenticationController->isAuthenticated()) {
            
            $post = $this->getQuestionById($parent);
            
            if($post[0]['commentTypeId'] == 1){
                $qid = $post[0]['id'];
            }
            else {
                $qid = $post[0]['questionId'];
            }
            
            $qt = $post[0]['title'];
            
            $form = new \Mos\HTMLForm\CForm(array(), array(
                'text' => array(
                    'type'        => 'textarea',
                    'label'       => 'Text:',
                    'required'    => true,
                    'validation'  => array('not_empty'),
                ),   
                'submit' => array(
                      'type'      => 'submit',
                      'value'      => 'Publicera kommentar',
                      'callback'  => function($form) {
                        return true;
                      }
                ),
              )
            );
        
            // Check the status of the form
            $status = $form->Check();
            
            // What to do if the form was submitted
            if($status === true) {
                
                 $now = gmdate('Y-m-d H:i:s');
                
                 $sql = "INSERT INTO mvc_comments (commentTypeId, questionId, parentId, userId, userAcronym, userEmail, title, text, created) VALUES ('3', '" . $qid . "','" . $parent . "','" . $_SESSION['user']->id . "', '" . $_SESSION['user']->acronym . "', '" . $_SESSION['user']->email . "', '" . $qt . "', '" . $form->Value('text') . "', '" . $now . "')";
                $res = $this->db->executeFetchAll($sql);
                $qw = '/question/view/' . $qid;
                $url = $this->url->create('') . $qw;
                $this->response->redirect($url);
            }
            
            // What to do when form could not be processed
            else if($status === false){
              header("Location: " . $_SERVER['PHP_SELF']);
            }

            $this->views->add('comment/view_single_post', [
                'presentation' => $post
            ]);
            
            $this->theme->setTitle("Skriv en kommentar");
    
            $this->views->add('comment/form', [
                'title' => "Skriv en kommentar",
                'content' => $form->getHTML()
            ]);
        }
    }

    /**
     * Allow user to edit a post.
     * 
     * @param integer $id the post id to edit.
     *  
     * @return void
     */
    public function editPostAction($id=null)
    {
        if($this->UserauthenticationController->isAuthenticated()) {
            
            $qry = "SELECT * FROM mvc_VQuestion WHERE id=?";
            $all = $this->db->executeFetchAll($qry,array($id));
            $post = json_decode(json_encode($all[0]),TRUE);

            if($post['commentTypeId']=='1') {
                $form = new \Mos\HTMLForm\CForm(array(), array(
                'title' => array(
                    'type'        => 'text',
                    'label'       => 'Rubrik:',
                    'value'       => $post['title'],
                    'required'    => true,
                    'validation'  => array('not_empty'),
                ), 
                'text' => array(
                    'type'        => 'textarea',
                    'label'       => 'Text:',
                    'value'       => $post['text'],
                    'required'    => true,
                    'validation'  => array('not_empty'),
                ),   
                'submit' => array(
                      'type'      => 'submit',
                      'value'      => 'Uppdatera',
                      'callback'  => function($form) {
                        return true;
                      }
                ),
            ));}
            else {
                $form = new \Mos\HTMLForm\CForm(array(), array(
                'text' => array(
                    'type'        => 'textarea',
                    'label'       => 'Text:',
                    'value'       => $post['text'],
                    'required'    => true,
                    'validation'  => array('not_empty'),
                ),   
                'submit' => array(
                      'type'      => 'submit',
                      'value'      => 'Uppdatera',
                      'callback'  => function($form) {
                        return true;
                      }
                ),
            ));}
        
            // Check the status of the form
            $status = $form->Check();
            
            $qid = null;
            
            // What to do if the form was submitted
            if($status === true) {
                $now = gmdate('Y-m-d H:i:s');
                if($post['commentTypeId']=='1') {
                    $sql = "UPDATE mvc_comments SET title='" . $form->Value('title') ."', text='" . $form->Value('text') . "', updated='" . $now . "' WHERE id='" . $id . "'";
                    $qId = $id;
                }
                else {
                    $sql = "UPDATE mvc_comments SET text='" . $form->Value('text') . "', updated='" . $now . "' WHERE id='" . $id . "'";
                    $qId = $post['questionId'];
                }
                $res = $this->db->executeFetchAll($sql, array());

                $url = $this->url->create('') . '/question/view/' . $qId;
                $this->response->redirect($url);
            }
            
            // What to do when form could not be processed
            else if($status === false){
                header("Location: " . $_SERVER['PHP_SELF']);
            }
            
            $this->theme->setTitle("Redigera inlägg");
        
            $this->views->add('comment/form', [
                'title' => "Redigera inlägg",
                'content' => $form->getHTML()
            ]);
            
        }
    }

    /**
     * Remove all comments.
     *
     * @param $pageID of the page to remove comments.
     * 
     * @return void
     */
    public function removeAllAction($pageID="")
    {
        $isPosted = $this->request->getPost('doRemoveAll');
        
        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }
        
        $sql = 'DELETE FROM test_comments WHERE page = "' . $pageID . '"';
        $this->db->execute($sql);
        
        $url = $this->url->create($pageID);
        $this->response->redirect($url);

    }
    
    /**
     * Get all the defined tags
     * 
     * @return array with tags
     */
    public function storedTagsAction()
    {

        $sql = 'SELECT * FROM mvc_tag';
        $allTags = $this->db->executeFetchAll($sql);
        
        $tags = json_decode(json_encode($allTags),TRUE); 

        return $tags;
    }
    
    /**
     * Output a list of all the defined tags
     *
     */
    public function getAllTagsAction()
    {
        $tags = $this->storedTagsAction();

        $this->views->add('comment/tags_list', [
                'title' => "Taggar",
                'tags' => $tags
        ]);
    }
    
    /**
     * Get all questions with a specific tag
     *
     * @param $tag the tag to search for
     * 
     * @return array with the results
     */
    public function questionsByTagAction($tag=null)
    {
        $tag = strtolower($tag);

        $sql = "SELECT * FROM mvc_tag WHERE name='" . $tag . "'";
        $all = $this->db->executeFetchAll($sql);
        
        if ($all) {
            $tag_info = json_decode(json_encode($all[0]),TRUE);
    
            $sql = "SELECT idQuestion FROM mvc_tag2question WHERE idTag='" . $tag_info['id'] . "'";
            $all = $this->db->executeFetchAll($sql);
            $all = json_decode(json_encode($all),TRUE);
            $questions = array();
            foreach ($all as $key => $val) {
                $qid = $val['idQuestion'];
                $questions[] = $this->getQuestionById($qid);
            }
        }
        
        $res = array();
        $res['tag_info'] = $tag_info;
        $res['questions'] = $questions;
        
        return $res;
    }
    
    /**
     * Get the id of a tag
     *
     * @param $tag the tag to search for
     * 
     * @return integer the tagId
     */
    public function getTagIdAction($tag=null)
    {
        $tag = strtolower($tag);

        $sql = "SELECT * FROM mvc_tag WHERE name='" . $tag . "'";
        $all = $this->db->executeFetchAll($sql);

        $tagId = $all[0]->id;

        return $tagId;
    }
    
    /**
     * Find out if a tag exists in the database.
     *
     * @param $tag the tag to search for
     * 
     * @return boolean true is tag exist, else false.
     */
    public function tagExistAction($tag=null)
    {
        $tag = strtolower($tag);

        $sql = "SELECT * FROM mvc_tag WHERE name='" . $tag . "'";
        $all = $this->db->executeFetchAll($sql);
        
        if($all){
            $res = true;
        }
        else {
            $res = false;
        }


        return $res;
    }
    
    /**
     * Output all questions with a specific tag.
     *
     * @return void
     */
    public function tagAction($tag=null)
    {
        $questions = $this->questionsByTagAction($tag);
        
        $this->views->add('comment/questions_by_tag', [
            'title' => "Frågor med taggen: " . $tag,
            'questions' => $questions
        ]);

    }
    
    /**
     * Remove a comment.
     * 
     * @param $cID ID of comment to be removed.
     *
     * @return void
     */
    public function removeAction($cID)
    {
        $isPosted = $this->request->getPost('doRemove');
        
        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }
        
        $sql = 'DELETE FROM test_comments WHERE id = ' . $cID;
        $this->db->execute($sql);
        
        $url = $this->url->create();
        $this->response->redirect($url);

    }
    
    /**
     * Edit a comment.
     * 
     * @param $cID ID of comment to edit.
     *
     * @return void
     */
    public function editAction($cID)
    {
        $commentToEdit = $this->comments->find($cID);
        
        $commentProperties = $commentToEdit->getProperties();
        
        $form = new \Mos\HTMLForm\CForm(array(), array(
            'content' => array(
                'type'        => 'textarea',
                'label'       => 'Comment:',
                'value'       => $commentProperties['content'],
                'required'    => true,
                'validation'  => array('not_empty'),
            ),   
            'name' => array(
                'type'        => 'text',
                'label'       => 'Name:',
                'value'       => $commentProperties['name'],
                'required'    => true,
                'validation'  => array('not_empty'),
            ),   
            'web' => array(
                'type'        => 'text',
                'label'       => 'Homepage:',
                'value'       => $commentProperties['web'],
            ),   
            'mail' => array(
                'type'        => 'text',
                'label'       => 'Email:',
                'value'       => $commentProperties['mail'],
            ),   
            'page' => array(
                'type'        => 'hidden',
                'value'       => $commentProperties['page'],
            ), 
            'submit' => array(
                  'type'      => 'submit',
                  'value'      => 'Post comment',
                  'callback'  => function($form) {
                    return true;
                  }
                ),
          )
        );
    
        // Check the status of the form
        $status = $form->Check();
        
        // What to do if the form was submitted
        if($status === true) {

            $this->comments->save([
                'content' => $form->Value('content'),
                'name' => $form->Value('name'),
                'web' => $form->Value('web'),
                'mail' => $form->Value('mail'),
                'page' => $form->Value('page'),
                'ip' => $this->request->getServer('REMOTE_ADDR'),
            ]);
            $url = $this->url->create();
            $this->response->redirect($url);
        }
        
        // What to do when form could not be processed
        else if($status === false){
            $url = $this->url->create();
            $this->response->redirect($url);
        }

        $this->views->add('comment/form', [
            'title' => "Edit comment",
            'content' => $form->getHTML()
        ]);

    }
    
    /**
     * Save an edited comment.
     * 
     * @param $cID ID of comment to save.
     * 
     * @return void
     */
    public function saveEditAction($cID)
    {
        $isPosted = $this->request->getPost('doSaveEdit');
        
        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }

        $comments = new \Anax\Comment\CommentsInDatabase();
        $comments->setDI($this->di);
        
        $comment = $comments->findComment($cID);
        
        $comment['content'] = $this->request->getPost('content');
        $comment['name'] = $this->request->getPost('name');
        $comment['web'] = $this->request->getPost('web');
        $comment['mail'] = $this->request->getPost('mail');

        $comments->saveEdited($comment, $cID);

        $this->response->redirect($this->request->getPost('redirect'));
    }
    
    /**
     * Get URI of Gravatar for an email address.
     *    
     * @param $email The email address.
     * @param $size Image dimensions in px.
     * 
     * @return void
     */
    public function getGravatar($email, $size)
    {
        return 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($email))) . '.jpg?' . ($size ? "s=$size" : null) . '&d=retro';
    }
    
}
