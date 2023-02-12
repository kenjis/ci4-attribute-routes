<?php

declare(strict_types=1);

namespace Kenjis\CI4\AttributeRoutes\Commands;

use CodeIgniter\Test\Filters\CITestStreamFilter;
use Kenjis\CI4\AttributeRoutes\TestCase;

use function is_file;
use function str_replace;
use function unlink;

/**
 * @internal
 */
final class AttributeRoutesTest extends TestCase
{
    private string $routesFile = 'Config/RoutesFromAttribute.php';

    protected function setUp(): void
    {
        parent::setUp();

        CITestStreamFilter::registration();
        CITestStreamFilter::addOutputFilter();
        CITestStreamFilter::addErrorFilter();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        CITestStreamFilter::removeOutputFilter();
        CITestStreamFilter::removeErrorFilter();

        if (is_file(APPPATH . $this->routesFile)) {
            unlink(APPPATH . $this->routesFile);
        }
    }

    public function testRouteUpdate(): void
    {
        command('route:update');

        $result = str_replace(
            ["\033[0;32m", "\033[0m", "\n"],
            '',
            CITestStreamFilter::$buffer
        );
        $this->assertStringContainsString('APPPATH/' . $this->routesFile . ' generated.', $result);
        $this->assertFileExists(APPPATH . $this->routesFile);
    }
}
