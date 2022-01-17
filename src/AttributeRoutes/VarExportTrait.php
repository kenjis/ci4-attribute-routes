<?php

declare(strict_types=1);

namespace Kenjis\CI4\AttributeRoutes;

use RuntimeException;

use function array_keys;
use function array_values;
use function preg_replace;
use function var_export;

trait VarExportTrait
{
    /**
     * PHP var_export() with short array syntax (square brackets) indented 2 spaces.
     *
     * NOTE: The only issue is when a string value has `=>\n[`, it will get converted to `=> [`
     *
     * @param mixed $expression
     *
     * @see https://www.php.net/manual/en/function.var-export.php
     */
    private function varExport($expression): string
    {
        $export = var_export($expression, true);

        $patterns = [
            '/array \(/'                            => '[',
            '/^([ ]*)\)(,?)$/m'                     => '$1]$2',
            "/=>[ ]?\n[ ]+\\[/"                     => '=> [',
            "/([ ]*)(\\'[^\\']+\\') => ([\\[\\'])/" => '$1$2 => $3',
        ];
        $export = preg_replace(array_keys($patterns), array_values($patterns), $export);

        if ($export === null) {
            throw new RuntimeException('Failed to convert to short array syntax');
        }

        return $export;
    }
}
