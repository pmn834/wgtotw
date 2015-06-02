<?php

namespace Anax\Comment;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class CommentController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;
    
    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        $this->comments = new \Anax\Comment\Comments();
        $this->comments->setDI($this->di);
    }

    /**
     * View all comments.
     *    
     * @param 
     * 
     * @return void
     */
    public function viewAction($pageID="")
    {

        $qry = 'page = "' . $pageID .'"';
        $pageComments = array();

        $all = $this->comments->query()
                              ->where($qry)
                              ->execute();
            
        foreach ($all as $comment) {
            $pageComments[] = $comment->getProperties();
        }

        $this->views->add('comment/comments', [
            'comments' => $pageComments,
        ]);
    }

    /**
     * Add a comment.
     *  
     * @return void
     */
    public function addAction($pageID="")
    {
        $form = new \Mos\HTMLForm\CForm(array(), array(
            'content' => array(
                'type'        => 'textarea',
                'label'       => 'Comment:',
                'required'    => true,
                'validation'  => array('not_empty'),
            ),   
            'name' => array(
                'type'        => 'text',
                'label'       => 'Name:',
                'required'    => true,
                'validation'  => array('not_empty'),
            ),   
            'web' => array(
                'type'        => 'text',
                'label'       => 'Homepage:',
            ),   
            'mail' => array(
                'type'        => 'text',
                'label'       => 'Email:',
            ),   
            'page' => array(
                'type'        => 'hidden',
                'value'       => $pageID,
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
         
            $url = $this->url->create($pageID);
            $this->response->redirect($url);
        }
        
        // What to do when form could not be processed
        else if($status === false){
          header("Location: " . $_SERVER['PHP_SELF']);
        }

        $this->views->add('comment/form', [
            'title' => "Add a comment",
            'content' => $form->getHTML()
        ]);
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
