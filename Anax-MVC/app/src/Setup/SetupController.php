<?php

namespace Anax\Setup;
 
/**
 * A controller for database setup.
 *
 */
class SetupController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;
    
    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        $this->setup = new \Anax\Setup\Setup();
        $this->setup->setDI($this->di);
    }
 
    /**
     * Create the database tables.
     *
     * @return void
     */
    public function createAction()
    {
        //---------------------------------------------
        // Create the database tables
        //---------------------------------------------
        $sql = file_get_contents(ANAX_APP_PATH . '/src/Setup/setup.sql');
        $res = $this->db->execute($sql);

    }
    
    /**
     * Populate the database tables.
     *
     * @return void
     */
    public function populateAction()
    {
        
        //---------------------------------------------
        // Add users
        //---------------------------------------------
        $this->db->insert(
        'user',
        ['acronym', 'email', 'name', 'password', 'description', 'created', 'active']
        );
 
        $now = gmdate('Y-m-d H:i:s');
     
        $this->db->execute([
            'admin',
            'admin@wgtotw.nu',
            'Administrator',
            password_hash('admin', PASSWORD_DEFAULT),
            'Jag heter admin.',
            $now,
            $now
        ]);
     
        $this->db->execute([
            'doe',
            'doe@wgtotw.nu',
            'John/Jane Doe',
            password_hash('doe', PASSWORD_DEFAULT),
            'Jag heter doe.',
            $now,
            $now
        ]);
     
        $this->db->execute([
            'doe2',
            'doe2@wgtotw.nu',
            'John/Jane Doe 2',
            password_hash('doe2', PASSWORD_DEFAULT),
            'Jag heter doe2.',
            $now,
            $now
        ]);
     
        $this->db->execute([
            'doe3',
            'doe3@wgtotw.nu',
            'John/Jane Doe 3',
            password_hash('doe3', PASSWORD_DEFAULT),
            'Jag heter doe3.',
            $now,
            $now
        ]);
        
     
        $this->db->execute([
            'doe4',
            'doe4@wgtotw.nu',
            'John/Jane Doe 4',
            password_hash('doe4', PASSWORD_DEFAULT),
            'Jag heter doe4.',
            $now,
            $now
        ]);
        
        //---------------------------------------------
        // Add questions, answers, comments and tags
        //---------------------------------------------
        
        $sql = file_get_contents(ANAX_APP_PATH . '/src/Setup/setup_populate.sql');
        $res = $this->db->execute($sql);
        
    }
    
}