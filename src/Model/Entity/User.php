<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property string $id
 * @property string $org_id
 * @property string $name
 * @property string|null $email
 * @property string $mobile
 * @property string $password
 * @property string $isactive
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property string|null $createdby
 * @property string|null $modifiedby
 * @property string|null $description
 * @property string $status
 *
 * @property \App\Model\Entity\Org $org
 * @property \App\Model\Entity\ShipmentInout[] $shipment_inouts
 */
class User extends Entity
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
        'email' => true,
        'mobile' => true,
        'password' => true,
        'isactive' => true,
        'created' => true,
        'modified' => true,
        'createdby' => true,
        'modifiedby' => true,
        'description' => true,
        'status' => true,
        'org' => true,
        'shipment_inouts' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];
}
