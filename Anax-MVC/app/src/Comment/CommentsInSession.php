<?php

namespace Phpmvc\Comment;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class CommentsInSession implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;



    /**
     * Add a new comment.
     *
     * @param array $comment with all details.
     * 
     * @return void
     */
    public function add($comment)
    {
        $comments = $this->session->get('comments', []);
        $comments[] = $comment;
        $this->session->set('comments', $comments);
    }



    /**
     * Find and return all comments for a specific page.
     *
     * @param $pageID to find comments.
     * 
     * @return array with all comments.
     */
    public function findAll($pageID)
    {
        $comments = $this->session->get('comments', []);
        foreach ($comments as $key => $val) {
            if ($val['page']!=$pageID){
             unset ($comments[$key]);
            }
        }
        return $comments;
    }

    /**
     * Find and return a comment.
     *
     * @param $commentID to find.
     * 
     * @return array with the comment.
     */
    public function findComment($commentID)
    {
        $comments = $this->session->get('comments', []);
        $comment = $comments[$commentID];
        return $comment;
    }

    /**
     * Delete all comments on a specific page.
     *
     * @param $pageID to delete all comments.
     * 
     * @return void
     */
    public function deleteAll($pageID)
    {
        $comments = $this->session->get('comments', []);
        foreach ($comments as $key => $val) {
            if ($val['page']==$pageID){
             unset ($comments[$key]);
            }
        }
        $this->session->set('comments', $comments);
    }
    
    /**
     * Delete a comment.
     *
     * @param $commentID to remove.
     * 
     * @return void
     */
    public function deleteSelected($commentID)
    {
        $comments = $this->session->get('comments', []);
        unset($comments[$commentID]); // remove comment by ID
        $this->session->set('comments', $comments);
    }
    
    /**
     * Save an edited comment.
     *
     * @param array $comment with all details.
     * @param $commentID to save.
     * 
     * @return void
     */
    public function saveEdited($comment, $commentID)
    {
        $comments = $this->session->get('comments', []);
        $comments[$commentID] = $comment;
        $this->session->set('comments', $comments);
    }
    
}
