<?php

namespace Tests\Http\Controllers\App\API;

use App\Dto\AccountDto;
use App\Enums\AccountingTypeEnum;
use App\Models\Account;
use App\Notifications\Daily\IssuedDailyTransferNotification;
use App\Notifications\Daily\TransferWalletTransactionCanceledNotification;
use App\Notifications\Daily\TransferWalletTransactionConfirmedNotification;
use App\Repository\ManagerDailyWalletRepositoryContract;
use App\ValueObjects\MoneyValueObject;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class DailyControllerTest extends TestCase
{


    public function testItCanCreateWalletTransferPendingTransaction()
    {
        Notification::fake();
        list($walletAccount, $targetWallet, $receiverManager) = $this->getData();
        $this->postJson(route('api.daily.wallet.issue_transfer'), [
            "amount" => $this->faker->randomFloat(),
            'gateway_id' => $walletAccount->id,
            'receiver_gateway_id' => $targetWallet->id
        ])->assertOk()->assertJsonFragment([
            "transaction_type" => 'transfer',
            "id" => 1
        ]);
        Notification::assertSentTo($receiverManager, IssuedDailyTransferNotification::class);
    }

    private function getData(): array
    {
        $manager = $this->createManager();
        $this->actingAs($manager);
        $walletAccount = Account::factory()->setDto(
            new AccountDto($manager, $this->faker->name, $this->faker->name, AccountingTypeEnum::debit(), null, 0, true, true)
        )->create();
        $manager->gateways()->attach($walletAccount->id, ['organization_id' => $manager->organization_id]);
        $receiverManager = $this->createManager([
            'organization' => $manager->organization
        ]);
        $targetWallet = Account::factory()->setDto(
            new AccountDto($manager, $this->faker->name, $this->faker->name, AccountingTypeEnum::debit(), null, 0, true, true)
        )->create();
        $receiverManager->gateways()->attach($targetWallet->id, ['organization_id' => $manager->organization_id]);
        return [
            $walletAccount,
            $targetWallet,
            $receiverManager,
            $manager
        ];
    }

    public function testItCanNotCreateWalletTransferPendingTransactionExistingPendingTransaction()
    {
        Notification::fake();
        list($walletAccount, $targetWallet, $receiverManager) = $this->getData();
        $this->postJson(route('api.daily.wallet.issue_transfer'), [
            "amount" => $this->faker->randomFloat(),
            'gateway_id' => $walletAccount->id,
            'receiver_gateway_id' => $targetWallet->id
        ])->assertOk();
        Notification::assertSentTo($receiverManager, IssuedDailyTransferNotification::class);


        $this->postJson(route('api.daily.wallet.issue_transfer'), [
            "amount" => $this->faker->randomFloat(),
            'gateway_id' => $walletAccount->id,
            'receiver_gateway_id' => $targetWallet->id
        ])->assertStatus(422)
            ->assertJsonPath(
                'errors.exists_pending_transactions.0', 'You have to remove your existing pending transactions first');
    }


    public function testItCanCreateWalletTransferPendingTransactionAfterRemovingExistingPendingTransaction()
    {
        Notification::fake();
        list($walletAccount, $targetWallet, $receiverManager) = $this->getData();
        $this->postJson(route('api.daily.wallet.issue_transfer'), [
            "amount" => $this->faker->randomFloat(),
            'gateway_id' => $walletAccount->id,
            'receiver_gateway_id' => $targetWallet->id
        ])->assertOk();
        Notification::assertSentTo($receiverManager, IssuedDailyTransferNotification::class);


        $this->postJson(route('api.daily.wallet.issue_transfer'), [
            "amount" => $this->faker->randomFloat(),
            'gateway_id' => $walletAccount->id,
            'receiver_gateway_id' => $targetWallet->id,
            'remove_existing_pending_transactions' => true
        ])->assertOk();
        Notification::assertSentTo($receiverManager, IssuedDailyTransferNotification::class);
    }


    public function testItCanConfirmPendingWalletTransaction()
    {
        Notification::fake();
        $managerDailyWalletRepository = app(ManagerDailyWalletRepositoryContract::class);
        list($walletAccount, $targetWallet, $receiverManager, $issuerManager) = $this->getData();
        $amount = $this->faker->randomFloat();
        $pendingTransaction = $managerDailyWalletRepository->createPendingWalletTransferTransaction(
            $walletAccount,
            $targetWallet,
            $amount
        );
        $this->actingAs($receiverManager)->getJson(route('api.daily.wallet.confirm_transfer', $pendingTransaction->id))->assertOk();
        $this->assertTrue((new MoneyValueObject($amount * -1))->isEqual($issuerManager->fresh()->remaining_accounts_balance));
        Notification::assertSentTo($issuerManager, TransferWalletTransactionConfirmedNotification::class);
    }

    public function testItCanCancelPendingWalletTransaction()
    {
        Notification::fake();
        $managerDailyWalletRepository = app(ManagerDailyWalletRepositoryContract::class);
        list($walletAccount, $targetWallet, $receiverManager, $issuerManager) = $this->getData();
        $amount = $this->faker->randomFloat();
        $pendingTransaction = $managerDailyWalletRepository->createPendingWalletTransferTransaction(
            $walletAccount,
            $targetWallet,
            $amount
        );
        $this->actingAs($receiverManager)->getJson(route('api.daily.wallet.cancel_transfer', $pendingTransaction->id))->assertOk();
        Notification::assertSentTo($issuerManager, TransferWalletTransactionCanceledNotification::class);
    }


}
