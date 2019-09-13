<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OrderLine Entity
 *
 * @property string $id
 * @property string $org_id
 * @property string $order_id
 * @property string $product_id
 * @property float $qty
 * @property float $price
 * @property float $amount
 * @property string|null $description
 * @property float|null $discount
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Org $org
 * @property \App\Model\Entity\Order $order
 * @property \App\Model\Entity\Product $product
 */
class OrderLine extends Entity
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
        'org_id' => true,
        'order_id' => true,
        'product_id' => true,
        'qty' => true,
        'price' => true,
        'amount' => true,
        'description' => true,
        'discount' => true,
        'created' => true,
        'modified' => true,
        'org' => true,
        'order' => true,
        'product' => true
    ];
}
