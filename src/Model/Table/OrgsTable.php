<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Orgs Model
 *
 * @property \App\Model\Table\BpartnersTable&\Cake\ORM\Association\HasMany $Bpartners
 * @property \App\Model\Table\BrandsTable&\Cake\ORM\Association\HasMany $Brands
 * @property \App\Model\Table\CustomersTable&\Cake\ORM\Association\HasMany $Customers
 * @property &\Cake\ORM\Association\HasMany $OrderLines
 * @property &\Cake\ORM\Association\HasMany $Orders
 * @property \App\Model\Table\OrgSettingsTable&\Cake\ORM\Association\HasMany $OrgSettings
 * @property \App\Model\Table\ProductCategoriesTable&\Cake\ORM\Association\HasMany $ProductCategories
 * @property \App\Model\Table\ProductsTable&\Cake\ORM\Association\HasMany $Products
 * @property &\Cake\ORM\Association\HasMany $RawOrders
 * @property \App\Model\Table\ShipmentInoutsTable&\Cake\ORM\Association\HasMany $ShipmentInouts
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\HasMany $Users
 * @property \App\Model\Table\WarehousesTable&\Cake\ORM\Association\HasMany $Warehouses
 *
 * @method \App\Model\Entity\Org get($primaryKey, $options = [])
 * @method \App\Model\Entity\Org newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Org[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Org|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Org saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Org patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Org[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Org findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OrgsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('orgs');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Bpartners', [
            'foreignKey' => 'org_id'
        ]);
        $this->hasMany('Brands', [
            'foreignKey' => 'org_id'
        ]);
        $this->hasMany('Customers', [
            'foreignKey' => 'org_id'
        ]);
        $this->hasMany('OrderLines', [
            'foreignKey' => 'org_id'
        ]);
        $this->hasMany('Orders', [
            'foreignKey' => 'org_id'
        ]);
        $this->hasMany('OrgSettings', [
            'foreignKey' => 'org_id'
        ]);
        $this->hasMany('ProductCategories', [
            'foreignKey' => 'org_id'
        ]);
        $this->hasMany('Products', [
            'foreignKey' => 'org_id'
        ]);
        $this->hasMany('RawOrders', [
            'foreignKey' => 'org_id'
        ]);
        $this->hasMany('ShipmentInouts', [
            'foreignKey' => 'org_id'
        ]);
        $this->hasMany('Users', [
            'foreignKey' => 'org_id'
        ]);
        $this->hasMany('Warehouses', [
            'foreignKey' => 'org_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->uuid('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 150)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('code')
            ->maxLength('code', 45)
            ->allowEmptyString('code');

        $validator
            ->scalar('isactive')
            ->allowEmptyString('isactive');

        $validator
            ->scalar('address')
            ->requirePresence('address', 'create')
            ->notEmptyString('address');

        $validator
            ->uuid('createdby')
            ->allowEmptyString('createdby');

        $validator
            ->uuid('modifiedby')
            ->allowEmptyString('modifiedby');

        return $validator;
    }
}
