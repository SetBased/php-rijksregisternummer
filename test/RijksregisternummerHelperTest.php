<?php
//----------------------------------------------------------------------------------------------------------------------
namespace Helper;

use SetBased\Rijksregisternummer\RijksregisternummerHelper;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Test cases for BelgiumRegister.
 */
class RijksregisternummerHelperTest extends \PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test clean with null.
   */
  public function testClean01()
  {
    $this->assertSame(null, RijksregisternummerHelper::clean(null));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test clean with empty string.
   */
  public function testClean02()
  {
    $this->assertSame(null, RijksregisternummerHelper::clean(''));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test clean with formatted register number.
   */
  public function testClean03()
  {
    $this->assertSame('66041066600', RijksregisternummerHelper::clean('66.04.10-666.00'));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test clean with unformatted register number.
   */
  public function testClean04()
  {
    $this->assertSame('66041066600', RijksregisternummerHelper::clean('66041066600'));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test clean with register number with rubbish.
   */
  public function testClean05()
  {
    $this->assertSame('66041066600', RijksregisternummerHelper::clean("660 41\n0666-00\x08", '/\D/'));
  }
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test computeCheckDigits.
   */
  public function testComputeCheckDigits()
  {
    $check = RijksregisternummerHelper::computeCheckDigits('1966-04-10', 666);
    $this->assertSame('60', $check);

    $check = RijksregisternummerHelper::computeCheckDigits('1966-04-10', 997);
    $this->assertSame('20', $check);

    $check = RijksregisternummerHelper::computeCheckDigits('1966-04-10',
                                                           666,
                                                           RijksregisternummerHelper::TYPE_BISNUMMER_UNKNOWN_GENDER);
    $this->assertSame('06', $check);

    $check = RijksregisternummerHelper::computeCheckDigits('1966-04-10',
                                                           997,
                                                           RijksregisternummerHelper::TYPE_BISNUMMER_UNKNOWN_GENDER);
    $this->assertSame('63', $check);

    $check = RijksregisternummerHelper::computeCheckDigits('1966-04-10',
                                                           666,
                                                           RijksregisternummerHelper::TYPE_BISNUMMER_KNOWN_GENDER);
    $this->assertSame('49', $check);

    $check = RijksregisternummerHelper::computeCheckDigits('1966-04-10',
                                                           997,
                                                           RijksregisternummerHelper::TYPE_BISNUMMER_KNOWN_GENDER);
    $this->assertSame('09', $check);

    $check = RijksregisternummerHelper::computeCheckDigits('1966-04-10',
                                                           666,
                                                           RijksregisternummerHelper::TYPE_SELF_ASSIGNED);
    $this->assertSame('92', $check);

    $check = RijksregisternummerHelper::computeCheckDigits('1966-04-10',
                                                           997,
                                                           RijksregisternummerHelper::TYPE_SELF_ASSIGNED);
    $this->assertSame('52', $check);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test format with null.
   */
  public function testFormat01()
  {
    $this->assertSame(null, RijksregisternummerHelper::format(null));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test format with empty string.
   */
  public function testFormat02()
  {
    $this->assertSame('', RijksregisternummerHelper::format(''));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test format with clean register number.
   */
  public function testFormat03()
  {
    $this->assertSame('66.04.10-666.00', RijksregisternummerHelper::format('66041066600'));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test format with wrong register number.
   */
  public function testFormat04()
  {
    $this->assertSame('Rare jongens, die Romeinen', RijksregisternummerHelper::format('Rare jongens, die Romeinen'));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
