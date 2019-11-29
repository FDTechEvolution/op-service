<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * BpartnerAddress Entity
 *
 * @property string $id
 * @property string $bpartner_id
 * @property string $address_id
 * @property string|null $isdefault
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null $seq
 *
 * @property \App\Model\Entity\Bpartner $bpartner
 * @property \App\Model\Entity\Address $address
 */
class BpartnerAddress extends Entity
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
        'bpartner_id' => true,
        'address_id' => true,
        'isdefault' => true,
        'created' => true,
        'modified' => true,
        'seq' => true,
        'bpartner' => true,
        'address' => true
    ];
}
