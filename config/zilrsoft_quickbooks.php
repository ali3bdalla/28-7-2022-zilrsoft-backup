<?php

return [
    'tax_account_id' => env('QUICKBOOKS_TAX_ACCOUNT_ID', null),
    "incomes_account_id" => env('QUICKBOOKS_INCOMES_ACCOUNT_ID', 159),
    "expenses_account_id" => env('QUICKBOOKS_EXPENSES_ACCOUNT_ID', 191),
    "inventory_account_id" => env('QUICKBOOKS_INVENTORY_ACCOUNT_ID', 192),
    "tax_code_account_id" => env('QUICKBOOKS_TAX_CODE_ACCOUNT_ID', 28),
    "cash_equivalents_account_id" => env('QUICKBOOKS_CASH_EQUIVALENTS_ACCOUNT_ID', 460),
];
