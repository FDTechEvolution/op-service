<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Org Entity
 *
 * @property string $id
 * @property string $name
 * @property string|null $code
 * @property string|null $isactive
 * @property string $address
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property string|null $createdby
 * @property string|null $modifiedby
 *
 * @property \App\Model\Entity\Bpartner[] $bpartners
 * @property \App\Model\Entity\Brand[] $brands
 * @property \App\Model\Entity\Customer[] $customers
 * @property \App\Model\Entity\OrgSetting[] $org_settings
 * @property \App\Model\Entity\ProductCategory[] $product_categories
 * @property \App\Model\Entity\Product[] $products
 * @property \App\Model\Entity\ShipmentInout[] $shipment_inouts
 * @property \App\Model\Entity\User[] $users
 * @property \App\Model\Entity\Warehouse[] $warehouses
 */
class Org extends Entity
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
        'code' => true,
        'isactive' => true,
        'address' => true,
        'created' => true,
        'modified' => true,
        'createdby' => true,
        'modifiedby' => true,
        'bpartners' => true,
        'brands' => true,
        'customers' => true,
        'org_settings' => true,
        'product_categories' => true,
        'products' => true,
        'shipment_inouts' => true,
        'users' => true,
        'warehouses' => true
    ];
}
