<?php

namespace Tests\Repository\Eloquent;

use App\Dto\AccountDto;
use App\Enums\AccountingTypeEnum;
use App\Models\Account;
use App\Models\Transaction;
use App\Repository\AccountRepositoryContract;
use Illuminate\Contracts\Foundation\Application;
use Tests\TestCase;

class AccountRepositoryTest extends TestCase
{


    /**
     * @var Application|mixed
     */
    private AccountRepositoryContract $accountRepositoryContract;

    public function testItCanCalcAccountBalance()
    {
        $manager = $this->createManager();

        $faker = $this->faker;
        $account = Account::factory()->setDto(
            new AccountDto($manager, $this->faker->name, $this->faker->name, AccountingTypeEnum::from($this->faker->randomElement(AccountingTypeEnum::toValues())))
        )->create();
        $transactions = Transaction::factory()->count($this->faker->numberBetween(1, 100))->state(function () use ($faker, $account) {
            return [
                'account_id' => $account->id,
                'creator_id' => $account->creator_id,
                'organization_id' => $account->organization_id,
                'amount' => $faker->randomFloat(),
                'type' => $faker->randomElement(AccountingTypeEnum::toValues()),
                'container_id' => 0
            ];
        })->create();

        $debitAmount = $transactions->where('type',AccountingTypeEnum::debit())->sum('amount');;
        $creditAmount = $transactions->where('type',AccountingTypeEnum::credit())->sum('amount');;
        $expectedBalance = $account->type == 'debit' ? $debitAmount - $creditAmount : $creditAmount - $debitAmount;
        $actualBalance = $this->accountRepositoryContract->getAccountBalance($account);
        $this->assertEquals($expectedBalance,$actualBalance);


    }

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->accountRepositoryContract = app(AccountRepositoryContract::class);
    }
}
