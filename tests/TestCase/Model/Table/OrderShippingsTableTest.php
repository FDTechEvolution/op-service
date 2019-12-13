<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OrderShippingsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OrderShippingsTable Test Case
 */
class OrderShippingsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\OrderShippingsTable
     */
    public $OrderShippings;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.OrderShippings',
        'app.Orders',
        'app.Addresses'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('OrderShippings') ? [] : ['className' => OrderShippingsTable::class];
        $this->OrderShippings = TableRegistry::getTableLocator()->get('OrderShippings', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->OrderShippings);

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
