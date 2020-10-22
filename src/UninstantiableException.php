<?php
declare(strict_types=1);

namespace Falgun\Fountain;

use RuntimeException;
use InvalidArgumentException;

final class UninstantiableException extends RuntimeException
{

    public function __construct(string $message, int $code = 500, \Throwable $previous = NULL)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function fromClassName(string $className): UninstantiableException
    {
        return new static($className . ' is Uninstantiable!');
    }

    public static function fallbackToDefaultValueFailed(
        UninstantiableException $uninstantiableException,
        InvalidArgumentException $noDefaultExeption
    ): UninstantiableException
    {
        $message = $uninstantiableException->getMessage() . ' And, ' . $noDefaultExeption->getMessage();
        return new static($message);
    }
}
