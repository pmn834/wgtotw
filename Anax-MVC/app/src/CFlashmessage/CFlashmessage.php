<?php

namespace Anax\CFlashmessage;

/**
 * Flashmessage service to provide feedback to users.
 *
 */
class CFlashmessage
{
    
    // Members
    private $useFA = null;          
    private $flashtype = [
        'info' => [
            'class' => 'flash_info',
            'fa' => 'fa fa-info'
            ],
        'error' => [
            'class' => 'flash_error',
            'fa' => 'fa fa-exclamation-triangle'
            ],
        'success' => [
            'class' => 'flash_success',
            'fa' => 'fa fa-check'
            ],
        'warning' => [
            'class' => 'flash_warning',
            'fa' => 'fa fa-bolt'
            ],
        ];
        
        
    /**
     * Constructor
     * 
     * @param string $fa use 'nofa' to disable Font Awesome in flash messages.
     */
    public function __construct($fa=null)
    {
        $this->useFA = ($fa=='nofa' ? false : true);

    }

    /**
     * Add a new flash message.
     *
     * @param array $params containing the flash message to add.
     */
    private function add($params)
    {
        $flash_messages = isset($_SESSION['flash_messages']) ? $_SESSION['flash_messages'] : array();
        $_SESSION['flash_messages'][] = $params;
    }
    
    /**
     * Delete all flash messages.
     * 
     */
    private function deleteAll()
    {
        unset($_SESSION['flash_messages']);
    }
    
    /**
     * Create an info flash message.
     * 
     * @param string $message text for the flash message to display.  
     */
    public function info($message)
    {
        $flash = $this->flashtype['info'];
        $flash['message'] = $message;
        $this->add($flash);
    }
    
    /**
     * Create an error flash message.
     *
     * @param string $message text for the flash message to display.  
     */
    public function error($message)
    {
        $flash = $this->flashtype['error'];
        $flash['message'] = $message;
        $this->add($flash);
    }
    
    /**
     * Create a success flash message.
     *
     * @param string $message text for the flash message to display.  
     */
    public function success($message)
    {
        $flash = $this->flashtype['success'];
        $flash['message'] = $message;
        $this->add($flash);
    }
    
    /**
     * Create a warning flash message.
     *
     * @param string $message text for the flash message to display.  
     */
    public function warning($message)
    {
        $flash = $this->flashtype['warning'];
        $flash['message'] = $message;
        $this->add($flash);
    }
    
    /**
     * Get all stored flash messages as HTML.
     *
     * @return string containing the HTML presentation of the flash messages.
     */
    public function output()
    {
        $out = '<div class="flash_messages">';
        $stored_messages = isset($_SESSION['flash_messages']) ? $_SESSION['flash_messages'] : array();
        foreach ($stored_messages as $key => $val){
            $out .= '<div class="flash_message ' . htmlspecialchars($val['class']) . '">';
            $out .= $this->useFA ? '<i class="' . htmlspecialchars($val['fa']) . '"></i>' : null;
            $out .= htmlspecialchars($val['message']);
            $out .= '</div>';
        }
        $out .= '</div>';
        $this->deleteAll();
        return $out;
    }

}
