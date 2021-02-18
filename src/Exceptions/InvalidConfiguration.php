<?php

namespace Rslanzi\NovaTranslatable\Exceptions;

use Exception;

class InvalidConfiguration extends Exception
{
    public static function defaultLocalesNotSet()
    {
        return new static("There are no default locales set. Make sure you call `Rslanzi\NovaTranslatable\Translatable::defaultLocales` in a service provider and pass it an array of locales.");
    }
}
