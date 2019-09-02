<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RawOrders Model
 *
 * @property \App\Model\Table\OrgsTable&\Cake\ORM\Association\BelongsTo $Orgs
 *
 * @method \App\Model\Entity\RawOrder get($primaryKey, $options = [])
 * @method \App\Model\Entity\RawOrder newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\RawOrder[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\RawOrder|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RawOrder saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RawOrder patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\RawOrder[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\RawOrder findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RawOrdersTable extends Table
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

        $this->setTable('raw_orders');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Orgs', [
            'foreignKey' => 'org_id',
            'joinType' => 'INNER'
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
            ->integer('orderno')
            ->requirePresence('orderno', 'create')
            ->notEmptyString('orderno');

        $validator
            ->scalar('data')
            ->maxLength('data', 4294967295)
            ->requirePresence('data', 'create')
            ->notEmptyString('data');

        $validator
            ->uuid('createdby')
            ->allowEmptyString('createdby');

        $validator
            ->scalar('status')
            ->maxLength('status', 5)
            ->notEmptyString('status');

        $validator
            ->scalar('lineid')
            ->maxLength('lineid', 255)
            ->allowEmptyString('lineid');

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
