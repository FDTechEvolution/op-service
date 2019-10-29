<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ShipmentInouts Model
 *
 * @property \App\Model\Table\OrgsTable&\Cake\ORM\Association\BelongsTo $Orgs
 * @property \App\Model\Table\FromWarehousesTable&\Cake\ORM\Association\BelongsTo $FromWarehouses
 * @property \App\Model\Table\ToWarehousesTable&\Cake\ORM\Association\BelongsTo $ToWarehouses
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\BpartnersTable&\Cake\ORM\Association\BelongsTo $Bpartners
 * @property \App\Model\Table\ShipmentInoutLinesTable&\Cake\ORM\Association\HasMany $ShipmentInoutLines
 *
 * @method \App\Model\Entity\ShipmentInout get($primaryKey, $options = [])
 * @method \App\Model\Entity\ShipmentInout newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ShipmentInout[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ShipmentInout|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ShipmentInout saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ShipmentInout patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ShipmentInout[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ShipmentInout findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ShipmentInoutsTable extends Table
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

        $this->setTable('shipment_inouts');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Orgs', [
            'foreignKey' => 'org_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('FromWarehouses', [
            'className' => 'Warehouses',
            'foreignKey' => 'from_warehouse_id',
            'propertyName' => 'FromWarehouses'
        ]);
        $this->belongsTo('ToWarehouses', [
            'className' => 'Warehouses',
            'foreignKey' => 'to_warehouse_id',
            'propertyName' => 'ToWarehouses'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);
        $this->belongsTo('Bpartners', [
            'foreignKey' => 'bpartner_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ShipmentInoutLines', [
            'foreignKey' => 'shipment_inout_id'
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
            ->date('docdate')
            ->requirePresence('docdate', 'create')
            ->notEmptyDate('docdate');

        $validator
            ->scalar('isshipment')
            ->notEmptyString('isshipment');

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->allowEmptyString('description');

        $validator
            ->scalar('status')
            ->maxLength('status', 2)
            ->requirePresence('status', 'create')
            ->notEmptyString('status');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['org_id'], 'Orgs'));
        $rules->add($rules->existsIn(['from_warehouse_id'], 'FromWarehouses'));
        $rules->add($rules->existsIn(['to_warehouse_id'], 'ToWarehouses'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['bpartner_id'], 'Bpartners'));

        return $rules;
    }
}
