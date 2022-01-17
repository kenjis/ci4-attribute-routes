<?php

declare(strict_types=1);

namespace Kenjis\CI4\AttributeRoutes\Commands;

use CodeIgniter\Test\Filters\CITestStreamFilter;
use Kenjis\CI4\AttributeRoutes\TestCase;

use function assert;
use function is_file;
use function is_resource;
use function str_replace;
use function stream_filter_append;
use function stream_filter_remove;
use function unlink;

use const STDERR;
use const STDOUT;

/**
 * @internal
 */
final class AttributeRoutesTest extends TestCase
{
    /**
     * @var false|resource
     */
    protected $streamFilter;

    private string $routesFile = 'Config/RoutesFromAttribute.php';

    protected function setUp(): void
    {
        parent::setUp();

        CITestStreamFilter::$buffer = '';

        $this->streamFilter = stream_filter_append(STDOUT, 'CITestStreamFilter');
        $this->streamFilter = stream_filter_append(STDERR, 'CITestStreamFilter');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        assert(is_resource($this->streamFilter));

        stream_filter_remove($this->streamFilter);

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
