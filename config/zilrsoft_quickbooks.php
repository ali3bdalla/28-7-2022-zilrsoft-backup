<?php

return [
    'tax_account_id' => env('QUICKBOOKS_TAX_ACCOUNT_ID', null),
    "incomes_account_id" => env('QUICKBOOKS_INCOMES_ACCOUNT_ID', 159),
    "expenses_account_id" => env('QUICKBOOKS_EXPENSES_ACCOUNT_ID', 191),
    "inventory_account_id" => env('QUICKBOOKS_INVENTORY_ACCOUNT_ID', 192),
];
