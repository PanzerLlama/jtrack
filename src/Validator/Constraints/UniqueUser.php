<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 1/4/20
 * Time: 4:42 PM
 */
declare(strict_types=1);

namespace App\Validator\Constraints;


use App\Validator\UniqueUserValidator;
use Symfony\Component\Validator\Constraint;

class UniqueUser extends Constraint
{
    public $message = "Użytkownik o tym adresie e-mail ({{ email }}) już istnieje.";

    public function validatedBy()
    {
        return UniqueUserValidator::class;
    }
}