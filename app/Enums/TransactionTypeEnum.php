<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Credit()
 * @method static static Debit()
 * @method static static Transfer()
 */
final class TransactionTypeEnum extends Enum
{
    const Credit = "credit";
    const Debit = "debit";
    const Transfer = "transfer";
}
