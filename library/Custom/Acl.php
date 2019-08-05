<?php

namespace Checkondispatch\Custom;

use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;
use Zend\Config\Config;
use Zend\Config\Factory;

class Acl extends ZendAcl {

    const DEFAULT_ROLE = 'guest';

    protected $_roleTableObject;
    protected $roles;
    protected $permissions;
    protected $resources;
    protected $rolePermission;
    protected $commonPermission;
    protected $config;
    
    /**
     * @desc initialize configuration
     */
    public function __construct() {              
        // reading file to authenticate roles, resources & permission
        $env = !empty(getenv('APP_ENV')) ? strtolower(getenv('APP_ENV')) : 'production';
        $filename = getcwd() . '/config/autoload/'.$env.'/auth.local.php';
        $configFile = \Zend\Config\Factory::fromFile($filename);
        $this->config = $configFile;        
    }

    public function initAcl() {
        $this->roles = $this->_getAllRoles();
        $this->resources = $this->_getAllResources();
        $this->rolePermission = $this->_getRolePermissions();
        // we are not putting these resource & permission in table bcz it is
        // common to all user
        $this->commonPermission = array(
            'Secure\Controller\Index' => array(
                'login',
                'index'
            )
        );
        $this->_addRoles()
                ->_addResources()
                ->_addRoleResources();
    }

    public function isAccessAllowed($role, $resource, $permission) {
        if (!$this->hasResource($resource)) {
            return false;
        }
        if ($this->isAllowed($role, $resource, $permission)) {
            return true;
        }
        return false;
    }

    protected function _addRoles() {
        $this->addRole(new Role(self::DEFAULT_ROLE));

        if (!empty($this->roles)) {
            foreach ($this->roles as $role) {
                $roleName = $role;
                if (!$this->hasRole($roleName)) {
                    $this->addRole(new Role($roleName), self::DEFAULT_ROLE);
                }
            }
        }
        return $this;
    }

    protected function _addResources() {
        if (!empty($this->resources)) {
            foreach ($this->resources as $resource) {
                if (!$this->hasResource($resource)) {
                    $this->addResource(new Resource($resource));
                }
            }
        }

        // add common resources
        if (!empty($this->commonPermission)) {
            foreach ($this->commonPermission as $resource => $permissions) {
                if (!$this->hasResource($resource)) {
                    $this->addResource(new Resource($resource));
                }
            }
        }

        return $this;
    }

    protected function _addRoleResources() {
        // allow common resource/permission to guest user
        if (!empty($this->commonPermission)) {
            foreach ($this->commonPermission as $resource => $permissions) {
                foreach ($permissions as $permission) {
                    $this->allow(self::DEFAULT_ROLE, $resource, $permission);
                }
            }
        }

        if (!empty($this->rolePermission)) {
            foreach ($this->rolePermission as $rolePermissions) {
                $this->allow($rolePermissions['role_name'], $rolePermissions['resource_name'], $rolePermissions['permission_name']);
            }
        }

        return $this;
    }

    protected function _getAllRoles() {
        $roles = array();
        $roles = $this->config['roles'];
        
        return $roles;
    }

    protected function _getAllResources() {
        $resources = array();
        $resources = $this->config['resources'];
        
        return $resources;
    }

    protected function _getRolePermissions() {
        $rolePermissions = array();        
        $rolePermissions = $this->config['rolePermissions'];

        return $rolePermissions;
    }

}
