<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BpartnersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BpartnersTable Test Case
 */
class BpartnersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\BpartnersTable
     */
    public $Bpartners;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Bpartners',
        'app.Orgs',
        'app.BpartnerAddresses',
        'app.ShipmentInouts'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Bpartners') ? [] : ['className' => BpartnersTable::class];
        $this->Bpartners = TableRegistry::getTableLocator()->get('Bpartners', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Bpartners);

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
