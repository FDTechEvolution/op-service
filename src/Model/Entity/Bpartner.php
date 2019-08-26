<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Bpartner Entity
 *
 * @property string $id
 * @property string $org_id
 * @property string|null $company
 * @property string $name
 * @property string $mobile
 * @property string $level
 * @property string|null $description
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $isactive
 *
 * @property \App\Model\Entity\Org $org
 * @property \App\Model\Entity\BpartnerAddress[] $bpartner_addresses
 * @property \App\Model\Entity\ShipmentInout[] $shipment_inouts
 */
class Bpartner extends Entity
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
        'company' => true,
        'name' => true,
        'mobile' => true,
        'level' => true,
        'description' => true,
        'created' => true,
        'modified' => true,
        'isactive' => true,
        'org' => true,
        'bpartner_addresses' => true,
        'shipment_inouts' => true
    ];
}
