<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ShipmentInoutsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ShipmentInoutsTable Test Case
 */
class ShipmentInoutsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ShipmentInoutsTable
     */
    public $ShipmentInouts;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ShipmentInouts',
        'app.Orgs',
        'app.FromWarehouses',
        'app.ToWarehouses',
        'app.Users',
        'app.Bpartners',
        'app.ShipmentInoutLines'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ShipmentInouts') ? [] : ['className' => ShipmentInoutsTable::class];
        $this->ShipmentInouts = TableRegistry::getTableLocator()->get('ShipmentInouts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ShipmentInouts);

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
