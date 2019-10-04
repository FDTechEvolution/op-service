<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Addresses Model
 *
 * @property \App\Model\Table\BpartnerAddressesTable&\Cake\ORM\Association\HasMany $BpartnerAddresses
 * @property \App\Model\Table\CustomerAddressesTable&\Cake\ORM\Association\HasMany $CustomerAddresses
 *
 * @method \App\Model\Entity\Address get($primaryKey, $options = [])
 * @method \App\Model\Entity\Address newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Address[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Address|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Address saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Address patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Address[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Address findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AddressesTable extends Table
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

        $this->setTable('addresses');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('BpartnerAddresses', [
            'foreignKey' => 'address_id'
        ]);
        $this->hasMany('CustomerAddresses', [
            'foreignKey' => 'address_id'
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
            ->scalar('line1')
            ->maxLength('line1', 255)
            ->allowEmptyString('line1');

        $validator
            ->scalar('subdistrict')
            ->maxLength('subdistrict', 100)
            ->requirePresence('subdistrict', 'create')
            ->notEmptyString('subdistrict');

        $validator
            ->scalar('district')
            ->maxLength('district', 100)
            ->requirePresence('district', 'create')
            ->notEmptyString('district');

        $validator
            ->scalar('province')
            ->maxLength('province', 100)
            ->requirePresence('province', 'create')
            ->notEmptyString('province');

        $validator
            ->scalar('zipcode')
            ->maxLength('zipcode', 5)
            ->requirePresence('zipcode', 'create')
            ->notEmptyString('zipcode');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        return $validator;
    }
}
