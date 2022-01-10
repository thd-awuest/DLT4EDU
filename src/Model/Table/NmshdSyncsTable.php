<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * NmshdSyncs Model
 *
 * @method \App\Model\Entity\NmshdSync newEmptyEntity()
 * @method \App\Model\Entity\NmshdSync newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\NmshdSync[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\NmshdSync get($primaryKey, $options = [])
 * @method \App\Model\Entity\NmshdSync findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\NmshdSync patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\NmshdSync[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\NmshdSync|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\NmshdSync saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\NmshdSync[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\NmshdSync[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\NmshdSync[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\NmshdSync[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class NmshdSyncsTable extends Table
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

        $this->setTable('nmshd_syncs');

        $this->addBehavior('Timestamp');
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
            ->scalar('content')
            ->allowEmptyString('content');

        return $validator;
    }
}
