<?php
declare(strict_types=1);

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class TriviaTest extends TestCase
{
    #[DataProvider('provideRunner')]
    public function test_runner(int $seed): void
    {
        srand($seed);
        ob_start();
        require __DIR__.'/../GameRunner.php';
        $actual = ob_get_clean();

        $filename = __DIR__."/snapshot/output-$seed.txt";
        if (!file_exists($filename)) {
            file_put_contents($filename, $actual);
        }
        $expected = file_get_contents($filename);

        self::assertSame($expected, $actual);
    }

    public static function provideRunner(): iterable
    {
        for ($i = 0; $i < 10000; $i++) {
            yield [$i];
        }
    }
}
