<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Customer Entity
 *
 * @property string $id
 * @property string $name
 * @property string $org_id
 * @property string|null $mobile
 * @property string|null $description
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property string $isactive
 *
 * @property \App\Model\Entity\Org $org
 * @property \App\Model\Entity\CustomerAddress[] $customer_addresses
 */
class Customer extends Entity
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
        'name' => true,
        'org_id' => true,
        'mobile' => true,
        'description' => true,
        'created' => true,
        'modified' => true,
        'isactive' => true,
        'org' => true,
        'customer_addresses' => true
    ];
}
