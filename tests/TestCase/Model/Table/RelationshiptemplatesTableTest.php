<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RelationshiptemplatesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RelationshiptemplatesTable Test Case
 */
class RelationshiptemplatesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\RelationshiptemplatesTable
     */
    protected $Relationshiptemplates;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Relationshiptemplates',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Relationshiptemplates') ? [] : ['className' => RelationshiptemplatesTable::class];
        $this->Relationshiptemplates = $this->getTableLocator()->get('Relationshiptemplates', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Relationshiptemplates);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\RelationshiptemplatesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\RelationshiptemplatesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
