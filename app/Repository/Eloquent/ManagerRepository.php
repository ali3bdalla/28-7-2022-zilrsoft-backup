<?php

namespace App\Repository\Eloquent;

use App\Models\Manager;
use App\Repository\ManagerRepositoryContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class ManagerRepository extends BaseRepository implements ManagerRepositoryContract
{


    public function getResellers()
    {
        return Manager::permission("");
    }
    public function getCurrentManagerBanks(): Collection
    {
        return $this->user()->gateways()->get();
    }

    public function user(): Manager
    {
        return Auth::user();
    }

    public function getAllManagersBanksExcept(array $managersId): array
    {
        $managers =Manager::query()->whereNotIn('id', $managersId)->with('gateways')->get();
        $managerBanks = [];
        foreach ($managers as $manager) {
            foreach ($manager->gateways()->get() as $gateway) {
                if ($gateway->is_gateway) {
                    $gateway['receiver_id'] = $manager['id'];
                    $managerBanks[] = $gateway;
                }
            }
        }
        return $managerBanks;
    }
}
