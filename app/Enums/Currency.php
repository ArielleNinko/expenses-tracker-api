<?php

namespace App\Enums;

enum Currency: string {

    use EnumToArray;

    case XOF = "FCFA";

    case EURO = "EURO";

    case USD = "USD";

    case CAD = "CAD";

}