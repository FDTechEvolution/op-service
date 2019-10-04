<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CustomerAddress Entity
 *
 * @property string $id
 * @property string $customer_id
 * @property string $address_id
 * @property string|null $isdefault
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int $seq
 *
 * @property \App\Model\Entity\Customer $customer
 * @property \App\Model\Entity\Address $address
 */
class CustomerAddress extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'customer_id' => true,
        'address_id' => true,
        'isdefault' => true,
        'created' => true,
        'modified' => true,
        'seq' => true,
        'customer' => true,
        'address' => true
    ];
}
