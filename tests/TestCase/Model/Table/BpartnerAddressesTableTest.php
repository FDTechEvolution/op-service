<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BpartnerAddressesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BpartnerAddressesTable Test Case
 */
class BpartnerAddressesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\BpartnerAddressesTable
     */
    public $BpartnerAddresses;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.BpartnerAddresses',
        'app.Bpartners',
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
        $config = TableRegistry::getTableLocator()->exists('BpartnerAddresses') ? [] : ['className' => BpartnerAddressesTable::class];
        $this->BpartnerAddresses = TableRegistry::getTableLocator()->get('BpartnerAddresses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->BpartnerAddresses);

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
