<?php

declare(strict_types=1);

namespace groupsAndModules\core;

use \LEPTON_handle;
use \LEPTON_database;
use \Lepton_singleton;
use \LEPTON_core;
use \LEPTON_tools;
use \lib_twig_box;

class Basic
{
    use LEPTON_singleton;
    
    public array $allModules = [];
    public array $allGroups =[];
    
    public static $instance;
    
    public function show(): void
    {
        $oTWIG = lib_twig_box::getInstance();
        $oTWIG->registerModule("groupsAndModules");
        
        echo $oTWIG->render(
            "@groupsAndModules/list.lte",
            [
                'action'      => "#",
                'leptoken'    => get_leptoken(),
                'allGroups'   => $this->allGroups,
                'allModules'  => $this->allModules
            ]
        );
    }
    
    public function handleSave(): void
    {        
        $save = LEPTON_core::getValue("groups", "array");
        if (!is_null($save))
        {
            // echo LEPTON_tools::display_dev($save, "pre", "ui message green");
            $database = LEPTON_database::getInstance();
            
            foreach ($save as $group_id => $accessValues)
            {
                sort($accessValues);
                $accessString = implode(",", $accessValues);
                
                $database->simple_query(
                    "UPDATE `".TABLE_PREFIX."groups` 
                    SET `module_permissions` = '".$accessString."'
                    WHERE `group_id` = ".$group_id
                );
            }
            
            $database->simple_query(
                    "UPDATE `".TABLE_PREFIX."groups` SET `module_permissions` = '' WHERE `backend_access` = 0"
            );
            echo LEPTON_tools::display_dev("Data saved. [2]", "pre", "ui message green");
        } 
    }

    protected function getModules(): void
    {
        $fields = ["addon_id", "name", "function", "directory"];
        
        LEPTON_database::getInstance()->execute_query(
            "SELECT ".$this->buildFields($fields)." FROM `".TABLE_PREFIX."addons` WHERE `function` in ('page','tool') ORDER BY `name`",
            true,
            $this->allModules,
            true
        );
    }
    
    protected function getGroups(): void
    {   
        $fields = ["group_id", "name", "module_permissions", "backend_access"];
        
        LEPTON_database::getInstance()->execute_query(
            "SELECT ".$this->buildFields($fields)." FROM `".TABLE_PREFIX."groups` WHERE `group_id` > 1",
            true,
            $this->allGroups,
            true
        );
        
        foreach ($this->allGroups as &$ref)
        {
            $ref['module_permissions'] = \explode(",", $ref['module_permissions']);
        }
    }
    
    /**
     * 
     * @param array $fields
     * @return string
     */
    protected function buildFields(array $fields): string
    {
        return "`".implode("`,`", $fields)."`";
    }
    
    private function __construct()
    {
        // own stuff here
        $this->handleSave();
        $this->getGroups();
        $this->getModules();
    }
}