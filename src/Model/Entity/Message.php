<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Message Entity
 *
 * @property int $id
 * @property string $message_id
 * @property string $subject
 * @property string $body
 * @property string $peer
 * @property string $attachment_id
 * @property string $response
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $edited
 *
 * @property \App\Model\Entity\Message[] $messages
 */
class Message extends Entity
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
        'message_id' => true,
        'subject' => true,
        'body' => true,
        'peer' => true,
        'attachment_id' => true,
        'response' => true,
        'created' => true,
        'edited' => true,
        'messages' => true,
        'attachment' => true,
    ];
}
