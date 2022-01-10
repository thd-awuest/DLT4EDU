<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Http\Client;

/**
 * Relationshiptemplates Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Relationshiptemplate newEmptyEntity()
 * @method \App\Model\Entity\Relationshiptemplate newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Relationshiptemplate[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Relationshiptemplate get($primaryKey, $options = [])
 * @method \App\Model\Entity\Relationshiptemplate findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Relationshiptemplate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Relationshiptemplate[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Relationshiptemplate|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Relationshiptemplate saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Relationshiptemplate[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Relationshiptemplate[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Relationshiptemplate[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Relationshiptemplate[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RelationshiptemplatesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('relationshiptemplates');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('template')
            ->requirePresence('template', 'create')
            ->notEmptyString('template');

        $validator
            ->scalar('token')
            ->requirePresence('token', 'create')
            ->notEmptyString('token');

        $validator
            ->scalar('accepted')
            ->requirePresence('accepted', 'create')
            ->notEmptyString('accepted');

        $validator
            ->dateTime('edited')
            ->notEmptyDateTime('edited');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('user_id', 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }


    public function ownRelationshiptemplateForUser($user) {


        $url = env('LOCAL_ENMESHED_URL') . '/api/v1/RelationshipTemplates/Own';
        $http = new Client();

        // TODO: Move json string to new format
        $content = '{
            "expiresAt": "2022-06-01T00:00:00.000Z",
            "content": {
                "attributes": [
                    {
                        "name": "App.name",
                        "value": "DLT4EDU"
                    },
                    {
                        "name": "App.website",
                        "value": "https://dlt4edu.th-deg.de"
                    }
                ],
                "metadata": {
                    "webSessionId": "' . $user->daad_uid. '"
                },
                "request": {
                    "create": [
                        {
                            "attribute": "Person.familyName",
                            "value": "' . $user->family_name. '"
                        },
                        {
                            "attribute": "Person.givenName",
                            "value": "' . $user->given_name. '"
                        },
                        {
                            "attribute": "Comm.email",
                            "value": "' . $user->email. '"
                        }
                    ],
                    "optional": [
                        {
                            "attribute": "Person.university"
                        }
                    ]
                },
                "privacy": {
                    "text": "Ja, ich habe die Datenschutzerklärung der THD gelesen und akzeptiere diese hiermit.",
                    "required": false,
                    "activeConsent": false,
                    "link": "https://th-deg.de/datenschutz"
                }
            }
        }';

        $response = $http->post($url, $content,
            [
                'headers' => ['X-API-KEY' => env('ENMESHED_API_KEY'), 'accept' => 'application/json', 'Content-Type' => 'application/json'],
                'type' => 'json'
            ]
        );

        if ($response->isOk()) {

            $result = $response->getJson();

            $relationshiptemplate = $this->newEmptyEntity();

            $relationshiptemplate->user_id = $user->id;
            $relationshiptemplate->token = $result["result"]["id"];
            $relationshiptemplate->template = (string)json_encode($response->getJson());

            $this->save($relationshiptemplate);

            return $relationshiptemplate;
        }

        return false;
    }
}
