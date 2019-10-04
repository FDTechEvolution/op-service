<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Address Entity
 *
 * @property string $id
 * @property string|null $line1
 * @property string $subdistrict
 * @property string $district
 * @property string $province
 * @property string $zipcode
 * @property string|null $description
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\BpartnerAddress[] $bpartner_addresses
 * @property \App\Model\Entity\CustomerAddress[] $customer_addresses
 */
class Address extends Entity
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
        'line1' => true,
        'subdistrict' => true,
        'district' => true,
        'province' => true,
        'zipcode' => true,
        'description' => true,
        'created' => true,
        'modified' => true,
        'bpartner_addresses' => true,
        'customer_addresses' => true
    ];
}
