<?php

declare(strict_types=1);

namespace CalebDW\LarastanLivewire\Tests\Integration;

use PHPStan\Analyser\Analyser;
use PHPStan\Analyser\Error;
use PHPStan\File\FileHelper;
use PHPStan\Testing\PHPStanTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Throwable;

final class IntegrationTest extends PHPStanTestCase
{
    /** @return iterable<array{0: string, 1?: array<int, array<int, string>>}> */
    public static function dataIntegrationTests(): iterable
    {
        yield [__DIR__.'/stubs/UnusedComputedMethod.php', [
            12 => ['Method CalebDW\LarastanLivewire\Tests\Integration\stubs\UnusedComputedMethod::notAComputedProperty() is unused.'],
        ]];
    }

    /**
     * @param  array<int, array<int, string>>|null  $expectedErrors
     *
     * @throws Throwable
     */
    #[DataProvider('dataIntegrationTests')]
    public function testIntegration(string $file, ?array $expectedErrors = null): void
    {
        $errors = $this->runAnalyse($file);

        if ($expectedErrors === null) {
            $this->assertNoErrors($errors);
        } else {
            if (count($expectedErrors) > 0) {
                $this->assertNotEmpty($errors);
            }

            $this->assertSameErrorMessages($file, $expectedErrors, $errors);
        }
    }

    /**
     * @see https://github.com/phpstan/phpstan-src/blob/c9772621c0bd6eab7e02fdaa03714bea239b372d/tests/PHPStan/Analyser/AnalyserIntegrationTest.php#L604-L622
     * @see https://github.com/phpstan/phpstan/discussions/6888#discussioncomment-2423613
     *
     * @param  string[]|null  $allAnalysedFiles
     * @return Error[]
     *
     * @throws Throwable
     */
    private function runAnalyse(string $file, ?array $allAnalysedFiles = null): array
    {
        $file = $this->getFileHelper()->normalizePath($file);

        /** @var Analyser $analyser */
        $analyser = self::getContainer()->getByType(Analyser::class); // @phpstan-ignore-line

        /** @var FileHelper $fileHelper */
        $fileHelper = self::getContainer()->getByType(FileHelper::class);

        $errors = $analyser->analyse([$file], null, null, true, $allAnalysedFiles)->getErrors(); // @phpstan-ignore-line

        foreach ($errors as $error) {
            $this->assertSame($fileHelper->normalizePath($file), $error->getFilePath());
        }

        return $errors;
    }

    /**
     * @param  array<int, array<int, string>>  $expectedErrors
     * @param  Error[]  $errors
     */
    private function assertSameErrorMessages(string $file, array $expectedErrors, array $errors): void
    {
        foreach ($errors as $error) {
            $errorLine = $error->getLine() ?? 0;

            $this->assertArrayHasKey(
                $errorLine,
                $expectedErrors,
                sprintf('File %s has unexpected error "%s" at line %d.', $file, $error->getMessage(), $errorLine),
            );
            $this->assertContains(
                $error->getMessage(),
                $expectedErrors[$errorLine],
                sprintf("File %s has unexpected error \"%s\" at line %d.\n\nExpected \"%s\"", $file, $error->getMessage(), $errorLine, implode("\n\t", $expectedErrors[$errorLine])),
            );
        }
    }

    /** @return string[] */
    public static function getAdditionalConfigFiles(): array
    {
        return [
            __DIR__.'/../phpstan-tests.neon',
        ];
    }
}
