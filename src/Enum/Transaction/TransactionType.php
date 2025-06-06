<?php

namespace App\Enum\Transaction;

enum TransactionType: string
{
    case INCOME = 'income';
    case EXPENSE = 'expense';

}