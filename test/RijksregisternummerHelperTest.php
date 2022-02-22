<?php
declare(strict_types=1);

namespace SetBased\Rijksregisternummer\Test;

use PHPUnit\Framework\TestCase;
use SetBased\Rijksregisternummer\RijksregisternummerHelper;

/**
 * Test cases for RijksregisternummerHelper.
 */
class RijksregisternummerHelperTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns test cases for method clean().
   *
   * @return array[]
   */
  public function getCleanTestCases(): array
  {
    return [// Null register number.
            ['value'    => null,
             'expected' => ''],
            // Empty register number.
            ['value'    => '',
             'expected' => ''],
            // Formatted register number.
            ['value'    => '66.04.10-666.00',
             'expected' => '66041066600'],
            // Unformatted register number.
            ['value'    => '66041066600',
             'expected' => '66041066600'],
            // Number with '0'.
            ['value'    => '0',
             'expected' => '0']];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns test cases for method clean().
   *
   * @return array[]
   */
  public function getFormatTestCases(): array
  {
    return [// Null register number.
            ['value'    => null,
             'expected' => null],
            // Empty register number.
            ['value'    => '',
             'expected' => ''],
            // Formatted register number.
            ['value'    => '66041066600',
             'expected' => '66.04.10-666.00'],
            // To many digits.
            ['value'    => '660410666000',
             'expected' => '660410666000'],
            // Not enough digits.
            ['value'    => '6041066600',
             'expected' => '6041066600'],
            // Wrong register number.
            ['value'    => 'Rare jongens, die Romeinen',
             'expected' => 'Rare jongens, die Romeinen']];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test method clean.
   *
   * @dataProvider getCleanTestCases
   */
  public function testClean01(?string $value, string $expected): void
  {
    self::assertSame($expected, RijksregisternummerHelper::clean($value));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test clean with register number with rubbish.
   */
  public function testClean02(): void
  {
    self::assertSame('66041066600', RijksregisternummerHelper::clean("660 41\n0666-00\x08", '/\D/'));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test format.
   *
   * @dataProvider getFormatTestCases
   */
  public function testFormat(?string $value, ?string $expected): void
  {
    self::assertSame($expected, RijksregisternummerHelper::format($value));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
