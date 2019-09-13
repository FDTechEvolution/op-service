<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OrderLinesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OrderLinesTable Test Case
 */
class OrderLinesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\OrderLinesTable
     */
    public $OrderLines;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.OrderLines',
        'app.Orgs',
        'app.Orders',
        'app.Products'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('OrderLines') ? [] : ['className' => OrderLinesTable::class];
        $this->OrderLines = TableRegistry::getTableLocator()->get('OrderLines', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->OrderLines);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
