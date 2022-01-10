<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string|null $daad_uid
 * @property string|null $family_name
 * @property string|null $given_name
 * @property string|null $email
 * @property string|null $nmshd_relationship_id
 * @property string|null $nmshd_template_id
 * @property string|null $nmshd_request
 * @property string|null $nmshd_accept
 * @property string|null $nmshd_accept_response
 * @property string|null $nmshd_rel_id
 * @property string|null $nmshd_change_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $edited
 *
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
        'daad_uid' => true,
        'family_name' => true,
        'given_name' => true,
        'email' => true,
        'nmshd_relationship_id' => true,
        'nmshd_template_id' => true,
        'nmshd_request' => true,
        'nmshd_accept' => true,
        'nmshd_accept_response' => true,
        'nmshd_rel_id' => true,
        'nmshd_change_id' => true,
        'created' => true,
        'edited' => true
    ];
}
