<?php

$incomes = 677;
return [
    "incomes_account_id" => env('QUICKBOOKS_INCOMES_ACCOUNT_ID', $incomes),
    "expenses_account_id" => env('QUICKBOOKS_EXPENSES_ACCOUNT_ID', $incomes + 1),
    "inventory_account_id" => env('QUICKBOOKS_INVENTORY_ACCOUNT_ID', $incomes + 2),
    "tax_code_account_id" => env('QUICKBOOKS_TAX_CODE_ACCOUNT_ID', "28"),
    "cash_equivalents_account_id" => env('QUICKBOOKS_CASH_EQUIVALENTS_ACCOUNT_ID', "460"),
];
