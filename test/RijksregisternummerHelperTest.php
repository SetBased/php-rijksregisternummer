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
   * Test clean with null.
   */
  public function testClean01()
  {
    self::assertSame(null, RijksregisternummerHelper::clean(null));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test clean with empty string.
   */
  public function testClean02()
  {
    self::assertSame(null, RijksregisternummerHelper::clean(''));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test clean with formatted register number.
   */
  public function testClean03()
  {
    self::assertSame('66041066600', RijksregisternummerHelper::clean('66.04.10-666.00'));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test clean with unformatted register number.
   */
  public function testClean04()
  {
    self::assertSame('66041066600', RijksregisternummerHelper::clean('66041066600'));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test clean with register number with rubbish.
   */
  public function testClean05()
  {
    self::assertSame('66041066600', RijksregisternummerHelper::clean("660 41\n0666-00\x08", '/\D/'));
  }
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test computeCheckDigits.
   */
  public function testComputeCheckDigits()
  {
    $check = RijksregisternummerHelper::computeCheckDigits('1966-04-10', 666);
    self::assertSame('60', $check);

    $check = RijksregisternummerHelper::computeCheckDigits('1966-04-10', 997);
    self::assertSame('20', $check);

    $check = RijksregisternummerHelper::computeCheckDigits('1966-04-10',
                                                           666,
                                                           RijksregisternummerHelper::TYPE_BISNUMMER_UNKNOWN_GENDER);
    self::assertSame('06', $check);

    $check = RijksregisternummerHelper::computeCheckDigits('1966-04-10',
                                                           997,
                                                           RijksregisternummerHelper::TYPE_BISNUMMER_UNKNOWN_GENDER);
    self::assertSame('63', $check);

    $check = RijksregisternummerHelper::computeCheckDigits('1966-04-10',
                                                           666,
                                                           RijksregisternummerHelper::TYPE_BISNUMMER_KNOWN_GENDER);
    self::assertSame('49', $check);

    $check = RijksregisternummerHelper::computeCheckDigits('1966-04-10',
                                                           997,
                                                           RijksregisternummerHelper::TYPE_BISNUMMER_KNOWN_GENDER);
    self::assertSame('09', $check);

    $check = RijksregisternummerHelper::computeCheckDigits('1966-04-10',
                                                           666,
                                                           RijksregisternummerHelper::TYPE_SELF_ASSIGNED);
    self::assertSame('92', $check);

    $check = RijksregisternummerHelper::computeCheckDigits('1966-04-10',
                                                           997,
                                                           RijksregisternummerHelper::TYPE_SELF_ASSIGNED);
    self::assertSame('52', $check);

    $check = RijksregisternummerHelper::computeCheckDigits('2012-01-20',
                                                           324,
                                                           RijksregisternummerHelper::TYPE_SELF_ASSIGNED);
    self::assertSame('43', $check);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test format with null.
   */
  public function testFormat01()
  {
    self::assertSame(null, RijksregisternummerHelper::format(null));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test format with empty string.
   */
  public function testFormat02()
  {
    self::assertSame('', RijksregisternummerHelper::format(''));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test format with clean register number.
   */
  public function testFormat03()
  {
    self::assertSame('66.04.10-666.00', RijksregisternummerHelper::format('66041066600'));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test format with wrong register number.
   */
  public function testFormat04()
  {
    self::assertSame('Rare jongens, die Romeinen', RijksregisternummerHelper::format('Rare jongens, die Romeinen'));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
