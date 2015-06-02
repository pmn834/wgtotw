<?php

namespace Anax\UserAuthentication;

/**
 * Class to handle user authentication, with login/logout and status.
 *
 */
class UserAuthenticationController implements \Anax\DI\IInjectionAware {
    
    use \Anax\DI\TInjectable;

    /**
     * Members
     */
    private $acronym = null;                // User acronym.
    private $name = null;                   // User name.
    private $userAuthenticated = false;     // User authenticated boolean.

    /**
     * Constructor get database connection settings,
     * and checks if user is logged in. If logged in
     * user credentials are stored to members.
     *
     * @param array $db_conn_set details for connecting to database.
     */
    public function __construct() {

        if (isset($_SESSION['user'])){
            $this->userAuthenticated = true;
            $this->acronym = $_SESSION['user']->acronym;
            $this->name = $_SESSION['user']->name;
        }
    }
    
    /**
     * Try to log in user, based on the provided username
     * and password. If successful user details are stored stored
     * members and $_SESSION. 
     *
     * @param string $user username to log in.
     * @param string $password password for the user.
     */
    public function login($user, $password) {

        $sql = "SELECT * FROM mvc_user WHERE acronym = ?";
        $res = $this->db->executeFetchAll($sql, array($user));
        if(isset($res[0]) && password_verify($password, $res[0]->password)) {
            $_SESSION['user'] = $res[0];
            $this->userAuthenticated = true;
            $this->acronym = $_SESSION['user']->acronym;
            $this->name = $_SESSION['user']->name;
        }

    }
  
    /**
     * Log out the currently logged in user.
     */
    public function logoutAction() {
        unset($_SESSION['user']);
        $this->userAuthenticated = false;
        $this->acronym = null;
        $this->name = null;
        $url = $this->url->create('');
        $this->response->redirect($url);
    }
    
    /**
    * Get information if user is logged in,
    * if logged in boolean 'true' is returned.
    *
    * @return boolean determining user authentication status
    */
    public function isAuthenticated() {
        return $this->userAuthenticated;
    }
    
    /**
    * Get the acronym of a logged in user.
    *
    * @return string acronym of user.
    */
    public function getAcronym() {
        return $this->acronym;
    }
    
    /**
    * Get the name of a logged in user.
    *
    * @return string name of user.
    */
    public function getName() {
        return $this->name;
    }
    
    /**
    * 
    *
    * @return 
    */
    public function userHomeAction() {
        if(!$this->UserauthenticationController->isAuthenticated()) {
            $this->UserauthenticationController->userLoginAction();
        }
        else {
            
            $id = $_SESSION['user']->id;
            $acronym = $_SESSION['user']->acronym;
            $userProperties = json_decode(json_encode($_SESSION['user']),TRUE);
            
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
    
            $this->theme->setTitle("Mina sidor - " . $_SESSION['user']->acronym);
            $this->views->add('users/home_view', [
                'user' => $userProperties,
                'posts' => $sorted_posts,
            ]);
                
            /*
            $this->views->add('wgtotw/page', [
                'title' => 'Mina sidor',
                'content' => 'Mina sidor!',
            ]);
            */
        }
    }
    
    /**
     * Handle user login.
     *    
     * @return void
     */
    public function userLoginAction()
    {
        if(!$this->UserauthenticationController->isAuthenticated()) {
            
            $form = new \Mos\HTMLForm\CForm(array(), array(
                'username' => array(
                    'type'        => 'text',
                    'label'       => 'Användarnamn:',
                    'required'    => true,
                    'validation'  => array('not_empty'),
                ), 
                'password' => array(
                    'type'        => 'password',
                    'label'       => 'Lösenord:',
                    'required'    => true,
                    'validation'  => array('not_empty'),
                ),   
                'submit' => array(
                      'type'      => 'submit',
                      'value'      => 'Logga in',
                      'callback'  => function($form) {
                        return true;
                      }
                ),
            ));
        
            // Check the status of the form
            $status = $form->Check();
            
            // What to do if the form was submitted
            if ($status === true) {
                
                $this->UserauthenticationController->login($form->Value('username'), $form->Value('password')); 
                
                if ($this->UserauthenticationController->isAuthenticated()) {
                    // Redirect if login is successful
                    $url = $this->url->create('') . '/my_pages';
                    $this->response->redirect($url);
                }
                // Redirect if login failed 
                    $url = $this->url->create('') . '/my_pages';
                    $this->response->redirect($url);
            }
            
            // What to do when form could not be processed
            else if($status === false){
              header("Location: " . $_SERVER['PHP_SELF']);
            }
            
            $nyttKonto = '<p style="margin-top: 1em">Ej medlem ännu?<br /><a href="' . $this->url->create('') . '/Userauthentication/userSignup">Skaffa ett konto</a></p>';
            
            $this->theme->setTitle("Logga in");
    
            $this->views->add('comment/form', [
                'title' => "Logga in",
                'content' => $form->getHTML() . $nyttKonto 
            ]);
            
        }
    }
    
    /**
     * User signup.
     *    
     * @return void
     */
    public function userSignupAction()
    {
        $form = new \Mos\HTMLForm\CForm(array(), array(
            'username' => array(
                'type'        => 'text',
                'label'       => 'Användarnamn:',
                'required'    => true,
                'validation'  => array('not_empty'),
            ), 
            'password' => array(
                'type'        => 'password',
                'label'       => 'Lösenord:',
                'required'    => true,
                'validation'  => array('not_empty'),
            ),   
            'email' => array(
                'type'        => 'text',
                'label'       => 'Email:',
                'required'    => true,
                'validation'  => array('not_empty'),
            ), 
            'fullname' => array(
                'type'        => 'text',
                'label'       => 'Fullständigt namn:',
                'required'    => true,
                'validation'  => array('not_empty'),
            ), 
            'presentation' => array(
                'type'        => 'textarea',
                'label'       => 'Personlig presentation:',
                'required'    => true,
                'validation'  => array('not_empty'),
            ), 
            'submit' => array(
                  'type'      => 'submit',
                  'value'      => 'Logga in',
                  'callback'  => function($form) {
                    return true;
                  }
            ),
        ));
    
        // Check the status of the form
        $status = $form->Check();
        
        // What to do if the form was submitted
        if ($status === true) {
            
            $now = gmdate('Y-m-d H:i:s');
            $password = password_hash($form->Value('password'), PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO mvc_user(acronym, email, name, password, description, created, active) VALUES('" . $form->Value('username') . "', '" . $form->Value('email') . "', '" . $form->Value('fullname') . "', '" . $password . "', '" . $form->Value('presentation') . "', '" . $now . "', '" . $now ."')";

            
            $res = $this->db->executeFetchAll($sql);

            $this->UserauthenticationController->login($form->Value('username'), $form->Value('password')); 

            $url = $this->url->create('') . '/my_pages';
            $this->response->redirect($url);
        }
        
        // What to do when form could not be processed
        else if($status === false){
          header("Location: " . $_SERVER['PHP_SELF']);
        }

        $this->theme->setTitle("Skapa konto");
        $this->views->add('comment/form', [
            'title' => "Skapa konto",
            'content' => $form->getHTML()
        ]);
        
    }
    
    /**
     * Allow user to edit a post.
     * 
     * @param integer $id the post id to edit.
     *  
     * @return void
     */
    public function editpostAction($id=null)
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
                'tags' => array(
                    'type'        => 'text',
                    'label'       => 'Taggar:',
                    'value'       => $post['tags'],
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
            
            // What to do if the form was submitted
            if($status === true) {
                $now = gmdate('Y-m-d H:i:s');
                if($post['commentTypeId']=='1') {
                    $sql = "UPDATE mvc_comments SET title='" . $form->Value('title') ."', text='" . $form->Value('text') . "', tags='" . $form->Value('tags') . "', updated='" . $now . "' WHERE id='" . $id . "'";
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
     * Allow a logged in user to edit their user profile.
     *  
     * @return void
     */
    public function editprofileAction()
    {
        if($this->UserauthenticationController->isAuthenticated()) {

            $form = new \Mos\HTMLForm\CForm(array(), array(
            'fullname' => array(
                'type'        => 'text',
                'label'       => 'Fullständigt namn:',
                'value'       =>  $_SESSION['user']->name,
                'required'    => true,
                'validation'  => array('not_empty'),
            ), 
            'email' => array(
                'type'        => 'text',
                'label'       => 'Email:',
                'value'       =>  $_SESSION['user']->email,
                'required'    => true,
                'validation'  => array('not_empty'),
            ), 
            'password' => array(
                'type'        => 'password',
                'label'       => 'Lösenord:',
                'required'    => true,
                'validation'  => array('not_empty'),
            ),
            'presentation' => array(
                'type'        => 'textarea',
                'label'       => 'Personlig presentation:',
                'value'       =>  $_SESSION['user']->description,
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
            ));
        
            // Check the status of the form
            $status = $form->Check();
            
            // What to do if the form was submitted
            if($status === true) {
                $now = gmdate('Y-m-d H:i:s');
                $pw = password_hash($form->Value('password'), PASSWORD_DEFAULT);
                
                $sql = "UPDATE mvc_user SET name='" . $form->Value('fullname') ."', email='" . $form->Value('email') . "', password='" . $pw . "', description='" . $form->Value('presentation') . "', updated='" . $now . "' WHERE id='" . $_SESSION['user']->id . "'";

                $res = $this->db->executeFetchAll($sql, array());
                $acronym = $_SESSION['user']->acronym;
                $password = $form->Value('password');
                unset($_SESSION['user']);
                $this->userAuthenticated = false;
                $this->acronym = null;
                $this->name = null;
                $this->UserauthenticationController->login($acronym, $password);
                
                $url = $this->url->create('') . '/my_pages';
                $this->response->redirect($url);
            }
            
            // What to do when form could not be processed
            else if($status === false){
                header("Location: " . $_SERVER['PHP_SELF']);
            }
            
            $this->theme->setTitle("Redigera användarprofil");
        
            $this->views->add('comment/form', [
                'title' => "Redigera användarprofil",
                'content' => $form->getHTML()
            ]);
            
        }
    }
 
}