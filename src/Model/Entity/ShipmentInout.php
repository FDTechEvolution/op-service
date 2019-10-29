<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ShipmentInout Entity
 *
 * @property string $id
 * @property string $org_id
 * @property \Cake\I18n\FrozenDate $docdate
 * @property string|null $from_warehouse_id
 * @property string $to_warehouse_id
 * @property string|null $user_id
 * @property string $isshipment
 * @property string $bpartner_id
 * @property string|null $description
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property string $status
 *
 * @property \App\Model\Entity\Org $org
 * @property \App\Model\Entity\FromWarehouse $from_warehouse
 * @property \App\Model\Entity\ToWarehouse $to_warehouse
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Bpartner $bpartner
 * @property \App\Model\Entity\ShipmentInoutLine[] $shipment_inout_lines
 */
class ShipmentInout extends Entity
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
        'docdate' => true,
        'from_warehouse_id' => true,
        'to_warehouse_id' => true,
        'user_id' => true,
        'isshipment' => true,
        'bpartner_id' => true,
        'description' => true,
        'created' => true,
        'modified' => true,
        'status' => true,
        'org' => true,
        'from_warehouse' => true,
        'to_warehouse' => true,
        'user' => true,
        'bpartner' => true,
        'shipment_inout_lines' => true
    ];
}
