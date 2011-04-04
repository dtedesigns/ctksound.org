<?php

/**
 * BaseRolesUsers
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $user_id
 * @property integer $role_id
 * @property Users $Users
 * @property Roles $Roles
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 5845 2009-06-09 07:36:57Z jwage $
 */
abstract class BaseRolesUsers extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('roles_users');
        $this->hasColumn('user_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'unsigned' => 1,
             'primary' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('role_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'unsigned' => 1,
             'primary' => true,
             'autoincrement' => false,
             ));
    }

    public function setUp()
    {
        $this->hasOne('Users', array(
             'local' => 'user_id',
             'foreign' => 'id'));

        $this->hasOne('Roles', array(
             'local' => 'role_id',
             'foreign' => 'id'));
    }
}