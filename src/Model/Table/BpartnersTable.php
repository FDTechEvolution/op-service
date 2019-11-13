<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Bpartners Model
 *
 * @property \App\Model\Table\OrgsTable&\Cake\ORM\Association\BelongsTo $Orgs
 * @property \App\Model\Table\BpartnerAddressesTable&\Cake\ORM\Association\HasMany $BpartnerAddresses
 * @property \App\Model\Table\ShipmentInoutsTable&\Cake\ORM\Association\HasMany $ShipmentInouts
 *
 * @method \App\Model\Entity\Bpartner get($primaryKey, $options = [])
 * @method \App\Model\Entity\Bpartner newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Bpartner[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Bpartner|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Bpartner saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Bpartner patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Bpartner[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Bpartner findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BpartnersTable extends Table
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

        $this->setTable('bpartners');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Orgs', [
            'foreignKey' => 'org_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('BpartnerAddresses', [
            'foreignKey' => 'bpartner_id'
        ]);
        $this->hasMany('ShipmentInouts', [
            'foreignKey' => 'bpartner_id'
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
            ->scalar('company')
            ->maxLength('company', 255)
            ->allowEmptyString('company');

        $validator
            ->scalar('name')
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('mobile')
            ->maxLength('mobile', 20)
            ->requirePresence('mobile', 'create')
            ->notEmptyString('mobile');

        $validator
            ->scalar('level')
            ->requirePresence('level', 'create')
            ->notEmptyString('level');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->scalar('isactive')
            ->notEmptyString('isactive');

        $validator
            ->scalar('status')
            ->maxLength('status', 3)
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

        return $rules;
    }
}
