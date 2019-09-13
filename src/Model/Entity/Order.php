<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Order Entity
 *
 * @property string $id
 * @property string|null $documentno
 * @property string $org_id
 * @property string $customer_id
 * @property string|null $user_id
 * @property \Cake\I18n\FrozenDate $orderdate
 * @property string $payment_method
 * @property string $status
 * @property string|null $description
 * @property float $totalamt
 * @property string $shipping
 * @property string|null $trackingno
 * @property string|null $createdby
 * @property string|null $modifiedby
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property string|null $raw_order_id
 *
 * @property \App\Model\Entity\Org $org
 * @property \App\Model\Entity\Customer $customer
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\RawOrder $raw_order
 * @property \App\Model\Entity\OrderLine[] $order_lines
 */
class Order extends Entity
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
        'documentno' => true,
        'org_id' => true,
        'customer_id' => true,
        'user_id' => true,
        'orderdate' => true,
        'payment_method' => true,
        'status' => true,
        'description' => true,
        'totalamt' => true,
        'shipping' => true,
        'trackingno' => true,
        'createdby' => true,
        'modifiedby' => true,
        'created' => true,
        'modified' => true,
        'raw_order_id' => true,
        'org' => true,
        'customer' => true,
        'user' => true,
        'raw_order' => true,
        'order_lines' => true
    ];
}
