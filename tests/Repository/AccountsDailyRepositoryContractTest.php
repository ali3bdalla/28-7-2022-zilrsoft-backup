<?php

namespace Tests\Repository;

use App\Models\Account;
use App\Models\Manager;
use App\Repository\Eloquent\AccountsDailyRepository;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccountsDailyRepositoryContractTest extends TestCase
{
    use WithFaker;

    public function testExample()
    {
        $this->markTestSkipped();
    }
//    private AccountsDailyRepository $accountsDailyRepository;
//    public function __construct(?string $name = null, array $data = [], $dataName = '')
//    {
//        parent::__construct($name, $data, $dataName);
//        $this->accountsDailyRepository = new AccountsDailyRepository();
//    }
//
//    public function testCreateDailyCloseAccountAggregate()
//    {
//        $manager = $this->createManager();
//        $this->actingAs($manager);
//        $banks = $this->getBanks($manager);
//        $this->accountsDailyRepository->createDailyCloseAccountAggregate();
//    }
//
//    public function getBanks(Manager $manager)
//    {
//        return Account::factory()->state(function (array $attributes) use ($manager) {
//            return array_merge($attributes, [
//                'organization_id' => $manager->organization_id,
//                'type' => 'debit',
//            ]);
//        })->count($this->faker->numberBetween(1, 5))->create()->map(function (Account $account) {
//            return [
//                'id' => $account->id,
//                'amount' => $this->faker->randomFloat()
//            ];
//        });
//    }
}
