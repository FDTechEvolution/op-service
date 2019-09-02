<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Warehouse Entity
 *
 * @property string $id
 * @property string $org_id
 * @property string $name
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property string|null $createdby
 * @property string|null $modifiedby
 * @property string|null $description
 * @property string|null $isactive
 *
 * @property \App\Model\Entity\Org $org
 * @property \App\Model\Entity\WarehouseLine[] $warehouse_lines
 */
class Warehouse extends Entity
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
        'name' => true,
        'created' => true,
        'modified' => true,
        'createdby' => true,
        'modifiedby' => true,
        'description' => true,
        'isactive' => true,
        'org' => true,
        'warehouse_lines' => true
    ];
}
