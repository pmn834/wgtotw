<?php

namespace Anax\User;
 
/**
 * A controller for users and admin related events.
 *
 */
class UserController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;
    
    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        $this->users = new \Anax\User\User();
        $this->users->setDI($this->di);
    }
 
    /**
     * List all users.
     *
     * @return void
     */
    public function listAction()
    {
        $all = $this->users->findAll();
        $users = array();
        
        foreach ($all as $user) {
            $users[] = $user->getProperties();
        }
     
        $this->theme->setTitle("Lista alla användare");
        $this->views->add('users/list-all', [
            'users' => $users,
            'title' => 'Alla användare',
        ]);
    }
    
    /**
     * List user with id.
     *
     * @param int $id of user to display
     *
     * @return void
     */
    public function idAction($id = null)
    {
        $user = $this->users->find($id);
        $userProperties = $user->getProperties();
        
        // Get user posts
        $qry = "SELECT * FROM mvc_comments
                WHERE userID=?
                ORDER BY created DESC";
        $posts = array();
        $all = $this->db->executeFetchAll($qry,array($id));
        $posts = json_decode(json_encode($all),TRUE);
        $sorted_posts = array();
        
        foreach ($posts as $post) {
            switch ($post['commentTypeId']) {
                case 3:
                    $sorted_posts['comments'][] = $post;
                    break;
                case 2:
                    $sorted_posts['answers'][] = $post;
                    break;
                default:
                    $sorted_posts['questions'][] = $post;
                    break;
            }
        }
        

        $this->theme->setTitle("Visa en användare");
        $this->views->add('users/view', [
            'user' => $userProperties,
            'posts' => $sorted_posts,
        ]);
    }
    
    /**
     * Add new user.
     *
     * @return void
     */
    public function addAction()
    {
        $form = new \Mos\HTMLForm\CForm(array(), array(
            'acronym' => array(
                'type'        => 'text',
                'label'       => 'Acronym:',
                'required'    => true,
                'validation'  => array('not_empty'),
            ),   
            'name' => array(
                'type'        => 'text',
                'label'       => 'Fullständigt namn:',
                'required'    => true,
                'validation'  => array('not_empty'),
            ),   
            'email' => array(
                'type'        => 'text',
                'label'       => 'E-mail:',
                'required'    => true,
                'validation'  => array('not_empty', 'email_adress'),
            ),   
            'password' => array(
                'type'        => 'password',
                'label'       => 'Lösenord:',
                'required'    => true,
                'validation'  => array('not_empty'),
            ), 
            'submit' => array(
                  'type'      => 'submit',
                  'value'      => 'Lägg till användare',
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
            
            header("Location: " . $_SERVER['PHP_SELF']);
            $now = gmdate('Y-m-d H:i:s');
            
            if(!$this->userExistsAction($form->Value('acronym'))) {
                $this->users->save([
                    'acronym' => $form->Value('acronym'),
                    'email' => $form->Value('email'),
                    'name' => $form->Value('name'),
                    'password' => password_hash($form->Value('name'), PASSWORD_DEFAULT),
                    'created' => $now,
                    'active' => $now,
                ]);
         
                $url = $this->url->create('users/id/' . $this->users->id);
                $this->response->redirect($url);
            }
        }
        
        // What to do when form could not be processed
        else if($status === false){
          header("Location: " . $_SERVER['PHP_SELF']);
        }
    
        
        $this->theme->setTitle("Lägg till en ny användare");
        $this->views->add('default/page', [
            'title' => "Lägg till en ny användare",
            'content' => $form->getHTML()
        ]);
        
    }
    
    
    public function userExistsAction($acronym) {

        $qry = "SELECT * FROM mvc_user
                WHERE acronym=?";
        $all = $this->db->executeFetchAll($qry,array($acronym));
        if ($all) {
            return true;
        }
        else {
            return false;
        }
    }
    
    
    /**
     * Delete user.
     *
     * @param integer $id of user to delete.
     *
     * @return void
     */
    public function deleteAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }
     
        $res = $this->users->delete($id);
     
        $url = $this->url->create();
        $this->response->redirect($url);
    }
    
    /**
     * Delete (soft) user.
     *
     * @param integer $id of user to delete.
     *
     * @return void
     */
    public function softDeleteAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }
     
        $now = gmdate('Y-m-d H:i:s');
     
        $user = $this->users->find($id);
     
        $user->deleted = $now;
        $user->save();
     
        $url = $this->url->create('users/id/' . $id);
        $this->response->redirect($url);
    }
    
    /**
     * Undo soft delete of user.
     *
     * @param integer $id of user to undo soft delete.
     *
     * @return void
     */
    public function undoSoftDeleteAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }

        $user = $this->users->find($id);
     
        $user->deleted = null;
        $user->save();
     
        $url = $this->url->create('users/id/' . $id);
        $this->response->redirect($url);
    }
    
    /**
     * Mark a user as active.
     *
     * @param integer $id of user to mark as active.
     *
     * @return void
     */
    public function activateAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }

        $user = $this->users->find($id);
        
        $now = gmdate('Y-m-d H:i:s');
     
        $user->active = $now;
        $user->save();
     
        $url = $this->url->create('users/id/' . $id);
        $this->response->redirect($url);
    }
    
    /**
     * Mark a user as being not active.
     *
     * @param integer $id of user to mark as not being active.
     *
     * @return void
     */
    public function deactivateAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }

        $user = $this->users->find($id);
     
        $user->active = null;
        $user->save();
     
        $url = $this->url->create('users/id/' . $id);
        $this->response->redirect($url);
    }
    
    /**
     * List all active and not deleted users.
     *
     * @return void
     */
    public function activeAction()
    {
        $all = $this->users->query()
            ->where('active IS NOT NULL')
            ->andWhere('deleted is NULL')
            ->execute();
            
        $users = array();
        
        foreach ($all as $user) {
            $users[] = $user->getProperties();
        }
     
        $this->theme->setTitle("Användare som är aktiva och ej i papperskorgen");
        $this->views->add('users/list-all', [
            'users' => $users,
            'title' => 'Användare som är aktiva och ej i papperskorgen',
        ]);
    }
    
    /**
     * List all soft deleted users.
     *
     * @return void
     */
    public function trashAction()
    {
        $all = $this->users->query()
            ->where('deleted IS NOT NULL')
            ->execute();
            
        $users = array();
        
        foreach ($all as $user) {
            $users[] = $user->getProperties();
        }
     
        $this->theme->setTitle("Användare i papperskorgen");
        $this->views->add('users/list-all', [
            'users' => $users,
            'title' => 'Användare i papperskorgen',
        ]);
    }
    
    /**
     * List all deactivated users.
     *
     * @return void
     */
    public function deactivatedAction()
    {
        $all = $this->users->query()
            ->where('active is NULL')
            ->execute();
            
        $users = array();
        
        foreach ($all as $user) {
            $users[] = $user->getProperties();
        }
     
        $this->theme->setTitle("Användare markerade som ej aktiva");
        $this->views->add('users/list-all', [
            'users' => $users,
            'title' => 'Användare markerade som ej aktiva',
        ]);
    }
    
    /**
     * Edit user info.
     *
     * @param integer $id of user to edit.
     * 
     * @return void
     */
    public function editAction($id = null)
    {
        $user = $this->users->find($id);
        $userProperties = $user->getProperties();
        
        $form = new \Mos\HTMLForm\CForm(array(), array(
            'acronym' => array(
                'type'        => 'text',
                'label'       => 'Acronym:',
                'value'       => $userProperties['acronym'],
                'required'    => true,
                'validation'  => array('not_empty'),
            ),   
            'name' => array(
                'type'        => 'text',
                'label'       => 'Fullständigt namn:',
                'value'       => $userProperties['name'],
                'required'    => true,
                'validation'  => array('not_empty'),
            ),   
            'email' => array(
                'type'        => 'text',
                'label'       => 'E-mail:',
                'value'       => $userProperties['email'],
                'required'    => true,
                'validation'  => array('not_empty', 'email_adress'),
            ),   
            'password' => array(
                'type'        => 'password',
                'label'       => 'Lösenord:',
                'required'    => true,
                'validation'  => array('not_empty'),
            ), 
            'submit' => array(
                  'type'      => 'submit',
                  'value'      => 'Uppdatera användarinfo',
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
            header("Location: " . $_SERVER['PHP_SELF']);
            $now = gmdate('Y-m-d H:i:s');
     
            $this->users->save([
                'acronym' => $form->Value('acronym'),
                'email' => $form->Value('email'),
                'name' => $form->Value('name'),
                'password' => password_hash($form->Value('name'), PASSWORD_DEFAULT),
                'updated' => $now,
            ]);
         
            $url = $this->url->create('users/id/' . $this->users->id);
            $this->response->redirect($url);
        }
        
        // What to do when form could not be processed
        else if($status === false){
          header("Location: " . $_SERVER['PHP_SELF']);
        }
        
        $output = "<h1>" . $userProperties['acronym'] . "</h1><div>";
        
        // Links for Soft delete / undo Soft delete
        if (isset($userProperties['deleted'])) {
            $url = $this->url->create('users/undoSoftDelete/' . $this->users->id);
            $userStatusReport = '<p style="color:#cc3233">Användaren är placerad i papperskorgen.</p>';
            $softDelete = '<a href="' . $url . '"><i class="fa fa-undo fa-2x" style="color: #333; margin-right: 15px"></i></a>';
        }
        else {
            $url = $this->url->create('users/softDelete/' . $this->users->id);
            $userStatusReport = '<p style="color:#247a1e">Användaren är ej i papperskorgen.</p>';
            $softDelete = '<a href="' . $url . '"><i class="fa fa-trash-o fa-2x" style="color: #333; margin-right: 15px"></i></a>';
        }
        
        // Links for activation / deactivation of user
        if (isset($userProperties['active'])) {
            $url = $this->url->create('users/deactivate/' . $this->users->id);
            $userStatusReport .= '<p style="color:#247a1e">Användaren är markerad som aktiv.</p><p>';
            $userActive = '<a href="' . $url . '"><i class="fa fa-ban fa-2x" style="color: #333; margin-right: 15px"></i></a>';
        }
        else {
            $url = $this->url->create('users/activate/' . $this->users->id);
            $userStatusReport .= '<p style="color:#cc3233">Användaren är markerad som ej aktiv.</p><p>';
            $userActive = '<a href="' . $url . '"><i class="fa fa-check-circle-o fa-2x" style="color: #333; margin-right: 15px"></i></a>';
        }
        
        // Link for permanent removal of user
        $url = $this->url->create('users/delete/' . $this->users->id);
        $userRemove = '<a href="' . $url . '"><i class="fa fa-remove fa-2x" style="color: #333;"></i></a>';
        
        $output .= $userStatusReport;
        $output .= $softDelete;
        $output .= $userActive;
        $output .= $userRemove;
        $output .= "</p></div>";

        $output .= $form->getHTML();
         
        $this->theme->setTitle("Redigera användare");
        $this->views->add('me/page', [
            'content' => $output,
        ]);
        
    }
    
    /**
     * Create formatted HTML presentation of user posts
     *
     * @param array $user containing user info.
     * @param array $posts containing user posts.
     * @param string $loggedIn set string to define user as logged in and enable editing of posts.
     *
     * @return string presentation as HTML
     */
    public function formatUserPosts($user,$posts,$loggedIn=null)
    {
        // User questions
        $html = "<p style='clear:both; margin: 3em 0 0 0 '><strong>Frågor av " . $user['acronym'] ."</strong></p>";
        
        if(!empty ($posts['questions'])) {
            foreach ($posts['questions'] as $question) {
                $html .= "<p style='margin: 1em 0 0 0'>";
                if ($loggedIn) {
                    $html .= "<span class='post_edit_link'><a href='" .  $this->url->create('') . "/question/editPost/" . $question['id'] . "'>Redigera</a></span>";
                }
                $html .= "<a href='" . $this->url->create('') . "/question/view/" . $question['id'] ."'>" . $question['title']. "</a> <span style='color: #999; font-size: 0.8em; margin-left: 1em'>" . date("j M Y", strtotime($question['created'])) . "</span></p>";
            }
        }
        else {
            $html .= "<p style='color: #999; font-size: 0.8em'>" . $user['acronym'] . " har inte publicerat några frågor.";
        }
        
        // User answers
        $html .= "<p style='clear:both; margin: 3em 0 0 0 '><strong>Svar av " . $user['acronym'] ."</strong></p>";
        
        if(!empty ($posts['answers'])) {
            foreach ($posts['answers'] as $answer) {
                $html .= "<p style='margin: 1em 0 0 0'>";
                if ($loggedIn) {
                    $html .= "<span class='post_edit_link'><a href='" .  $this->url->create('') . "/question/editPost/" . $answer['id'] . "'>Redigera</a></span>";
                }
                $html .= "<a href='" . $this->url->create('') . "/question/view/" . $answer['questionId'] ."'>" . $answer['title']. "</a> <span style='color: #999; font-size: 0.8em; margin-left: 1em'>" . date("j M Y", strtotime($answer['created'])) . "</span></p>";
            }
        }
        else {
            $html .= "<p style='color: #999; font-size: 0.8em'>" . $user['acronym'] . " har inte publicerat några svar.";
        }
        
        // User comments
        $html .= "<p style='clear:both; margin: 3em 0 0 0 '><strong>Kommentarer av " . $user['acronym'] ."</strong></p>";
        
        if(!empty ($posts['comments'])) {
            foreach ($posts['comments'] as $comment) {
                $html .= "<p style='margin: 1em 0 0 0'>";
                if ($loggedIn) {
                    $html .= "<span class='post_edit_link'><a href='" .  $this->url->create('') . "/question/editPost/" . $comment['id'] . "'>Redigera</a></span>";
                }
                $html .="<a href='" . $this->url->create('') . "/question/view/" . $comment['questionId'] ."'>" . $comment['title']. "</a> <span style='color: #999; font-size: 0.8em; margin-left: 1em'>" . date("j M Y", strtotime($comment['created'])) . "</span></p>"; 
            }
        }
        else {
            $html .= "<p style='color: #999; font-size: 0.8em'>" . $user['acronym'] . " har inte publicerat några kommentarer.";
        }
        
        return $html;
    }
}