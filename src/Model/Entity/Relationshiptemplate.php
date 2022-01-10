<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Relationshiptemplate Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $template
 * @property string $token
 * @property string $relationship_id
 * @property string $change_id
 * @property string $peer
 * @property string $accepted
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $edited
 *
 * @property \App\Model\Entity\User $user
 */
class Relationshiptemplate extends Entity
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
        'user_id' => true,
        'template' => true,
        'token' => true,
        'relationship_id' => true,
        'change_id' => true,
        'peer' => true,
        'accepted' => true,
        'created' => true,
        'edited' => true,
        'user' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'token',
    ];
}
