<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OrderShipping Entity
 *
 * @property string $id
 * @property string $order_id
 * @property string $address_id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Order $order
 * @property \App\Model\Entity\Address $address
 */
class OrderShipping extends Entity
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
        'order_id' => true,
        'address_id' => true,
        'created' => true,
        'modified' => true,
        'order' => true,
        'address' => true
    ];
}
