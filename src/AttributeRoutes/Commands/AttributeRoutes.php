<?php

declare(strict_types=1);

namespace Kenjis\CI4\AttributeRoutes\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Kenjis\CI4\AttributeRoutes\RouteFileGenerator;

class AttributeRoutes extends BaseCommand
{
    /**
     * The group the command is lumped under
     * when listing commands.
     *
     * @var string
     */
    protected $group = 'AttributeRoutes';

    /**
     * The Command's name
     *
     * @var string
     */
    protected $name = 'route:update';

    /**
     * The Command's usage
     *
     * @var string
     */
    protected $usage = 'route:update';

    /**
     * The Command's short description.
     *
     * @var string
     */
    protected $description = 'Generate Routes file from Route Attributes.';

    /**
     * @param array<string, mixed> $params
     */
    public function run(array $params): void
    {
        $generator = new RouteFileGenerator();
        $message   = $generator->generate();

        CLI::write($message);
        CLI::newLine();
        CLI::write(
            'Check your routes with the `'
            . CLI::color('php spark routes', 'green')
            . '` command.'
        );
    }
}
