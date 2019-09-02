<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * RawOrder Entity
 *
 * @property string $id
 * @property string $org_id
 * @property int $orderno
 * @property string $data
 * @property \Cake\I18n\FrozenTime|null $created
 * @property string|null $createdby
 * @property string $status
 * @property string|null $lineid
 *
 * @property \App\Model\Entity\Org $org
 */
class RawOrder extends Entity
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
        'orderno' => true,
        'data' => true,
        'created' => true,
        'createdby' => true,
        'status' => true,
        'lineid' => true,
        'org' => true
    ];
}
