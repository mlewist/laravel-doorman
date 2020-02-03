<?php

namespace Redsnapper\LaravelDoorman\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Redsnapper\LaravelDoorman\Models\Contracts\Role;
use Redsnapper\LaravelDoorman\Models\Contracts\Permission;
use Redsnapper\LaravelDoorman\PermissionsRegistrar;

class PermissionsRelation extends Relation
{
    /**
     * @var Permission
     */
    protected $permissionClass;

    /**
     * The pivot table for role users
     *
     * @var string
     */
    protected $roleUserPivotTable;

    /**
     * The pivot table for permission roles
     *
     * @var string
     */
    protected $permissionRolePivotTable;

    /**
     * The foreign key of the permission
     *
     * @var string
     */
    protected $permissionForeignKey;

    /**
     * The foreign key of the role
     *
     * @var string
     */
    protected $roleForeignKey;

    /**
     * The foreign key of the user
     *
     * @var string
     */
    protected $userForeignKey;

    public function __construct(Model $parent)
    {

        $this->permissionClass = $this->getPermissionClass();
        $roleClass = $this->getRoleClass();

        // Pivot tables
        $this->roleUserPivotTable = $parent->joiningTable('role');
        $this->permissionRolePivotTable = $this->permissionClass->joiningTable('role');

        // Foreign keys
        $this->permissionForeignKey = $this->permissionClass->getForeignKey();
        $this->roleForeignKey = $roleClass->getForeignKey();
        $this->userForeignKey = $parent->getForeignKey();

        parent::__construct($this->permissionClass::query(), $parent);
    }

    /**
     * Set the base constraints on the relation query.
     *
     * @return void
     */
    public function addConstraints()
    {

        $this->query
          ->join($this->permissionRolePivotTable,
            $this->permissionRolePivotTable.'.'.$this->permissionForeignKey,
            '=',
            $this->permissionClass->getQualifiedKeyName()
          )
          ->join($this->roleUserPivotTable,
            $this->roleUserPivotTable.'.'.$this->roleForeignKey,
            '=',
            $this->permissionRolePivotTable.'.'.$this->roleForeignKey
          )
          ->groupBy($this->getQualifiedUserPivotKeyName(), $this->permissionClass->getQualifiedKeyName())
          ->select($this->permissionClass->getTable().".*",$this->getQualifiedUserPivotKeyName());

        if (static::$constraints) {
            $this->constrainByUser();
        }
    }

    /**
     *  Constrain the query by the user
     */
    protected function constrainByUser()
    {
        $userKey = $this->parent->getKey();

        $this->query->where($this->getQualifiedUserPivotKeyName(), $userKey);

        return $this;
    }

    /**
     * Get the fully qualified "user key" for the user role pivot table.
     *
     * @return string
     */
    public function getQualifiedUserPivotKeyName(): string
    {
        return $this->roleUserPivotTable.'.'.$this->userForeignKey;
    }

    public function addEagerConstraints(array $models)
    {
        $whereIn = $this->whereInMethod($this->parent, $this->parent->getKeyName());

        $this->query->{$whereIn}(
          $this->getQualifiedUserPivotKeyName(),
          $this->getKeys($models, $this->parent->getKeyName())
        );
    }

    public function initRelation(array $models, $relation)
    {
        foreach ($models as $model) {
            $model->setRelation(
              $relation,
              $this->related->newCollection()
            );
        }

        return $models;
    }

    public function match(array $users, Collection $results, $relation)
    {
        $dictionary = $this->buildDictionary($results);

        // Once we have an array dictionary of child objects we can easily match the
        // children back to their parent using the dictionary and the keys on the
        // the parent models. Then we will return the hydrated models back out.
        foreach ($users as $user) {
            if (isset($dictionary[$key = $user->getKey()])) {
                $user->setRelation(
                  $relation, $this->related->newCollection($dictionary[$key])
                );
            }
        }

        return $users;
    }

    /**
     * Build model dictionary keyed by the relation's foreign key.
     *
     * @param  Collection  $results
     * @return array
     */
    protected function buildDictionary(Collection $results)
    {
        // First we will build a dictionary of child models keyed by the foreign key
        // of the relation so that we will easily and quickly match them to their
        // parents without having a possibly slow inner loops for every models.
        $dictionary = [];

        foreach ($results as $result) {
            $dictionary[$result->{$this->userForeignKey}][] = $result;
        }

        return $dictionary;
    }

    public function getResults()
    {
        return $this->query->get();
    }

    protected function getRoleClass(): Role
    {
        return app(PermissionsRegistrar::class)->getRoleClass();
    }

    protected function getPermissionClass(): Permission
    {
        return app(PermissionsRegistrar::class)->getPermissionClass();
    }

}