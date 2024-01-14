<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Banking()
 * @method static static Cash()
 * @method static static Card()
 */
final class AccountCategory extends Enum
{
    const Banking = 0;
    const Cash = 1;
    const Card = 2;
}
