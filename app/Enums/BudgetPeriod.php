<?php

namespace App\Enums;

enum BudgetPeriod: string {

    use EnumToArray;

    case weekly = "weekly";

    case monthly = "monthly";

}
