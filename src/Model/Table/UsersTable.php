<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Http\Session;

/**
 * Users Model
 *
 *
 * @method \App\Model\Entity\User newEmptyEntity()
 * @method \App\Model\Entity\User newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Relationshiptemplates');

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
            ->scalar('daad_uid')
            ->maxLength('daad_uid', 100)
            ->allowEmptyString('daad_uid');

        $validator
            ->scalar('family_name')
            ->maxLength('family_name', 50)
            ->allowEmptyString('family_name');

        $validator
            ->scalar('given_name')
            ->maxLength('given_name', 50)
            ->allowEmptyString('given_name');

        $validator
            ->email('email')
            ->allowEmptyString('email');

        $validator
            ->scalar('nmshd_request')
            ->allowEmptyString('nmshd_request');

        $validator
            ->scalar('nmshd_relationship_id')
            ->maxLength('nmshd_relationship_id', 100)
            ->allowEmptyString('nmshd_relationship_id');

        $validator
            ->scalar('nmshd_template_id')
            ->maxLength('nmshd_template_id', 100)
            ->allowEmptyString('nmshd_template_id');

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
        $rules->add($rules->isUnique(['email']), ['errorField' => 'email']);

        return $rules;
    }


    public function loggedinUser() {

        $session = new Session();
        $samlUserdata = $session->read('samlUserdata');

        return $this->find('all', ['conditions' => ['Users.email' => $samlUserdata['urn:oid:0.9.2342.19200300.100.1.3'][0]], 'contain' => ['Relationshiptemplates']])->first();
    }

    public function isLoggedIn() {

        $user = $this->loggedinUser();
        if (!$user) {
            return false;
        }
        return true;
    }

}
