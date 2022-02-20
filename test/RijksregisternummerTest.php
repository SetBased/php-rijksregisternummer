<?php
declare(strict_types=1);

namespace SetBased\Rijksregisternummer\Test;

use PHPUnit\Framework\TestCase;
use SetBased\Exception\FallenException;
use SetBased\Rijksregisternummer\Rijksregisternummer;
use SetBased\Rijksregisternummer\RijksregisternummerHelper;

/**
 * Test cases for Rijksregisternummer.
 */
class RijksregisternummerTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns invalid identification numbers.
   *
   * @return array[]
   */
  public function getInvalidValues(): array
  {
    return [['value' => '66.04.10-666.00'],            // invalid check (born 19xx)
            ['value' => '10.02.18-066.33'],            // invalid check (born 20xx)
            ['value' => '66.04.10-000.47'],            // invalid counter (to low)
            ['value' => '66.04.10-999.18'],            // invalid counter (to high)
            ['value' => '66.02.30-001.14'],            // invalid birthday
            ['value' => 'Rare jongens, die Romeinen'], // not a number
            ['value' => '42.01.22-051.081'],           // to many digits
            ['value' => '42.01.22-051.1'],             // to enough digits
            ['value' => null]];                        // null

  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns valid test vases.
   *
   * @return array[]
   */
  public function getValidTestCases(): array
  {
    return [
      // Rijksregisternummer: voorbeeld uit IT000_Rijksregisternummer.pdf.
      ['rijksregisternummer'   => '42.01.22-051.81',
       'isBis'                 => false,
       'isRijksregisternummer' => true,
       'isSelfAssigned'        => false,
       'isKnownBirthday'       => true,
       'getBirthday'           => '1942-01-22',
       'getBirthYear'          => 1942,
       'getBirthMonth'         => 1,
       'getBirthDayOfMonth'    => 22,
       'getGender'             => 'M',
       'getSequenceNumber'     => 51,
       'getType'               => RijksregisternummerHelper::TYPE_RIJKSREGISTERNUMMER],
      // Rijksregisternummer: voorbeeld uit IT000_Rijksregisternummer.pdf.
      ['rijksregisternummer'   => '40.00.00-953.81',
       'isBis'                 => false,
       'isRijksregisternummer' => true,
       'isSelfAssigned'        => false,
       'isKnownBirthday'       => false,
       'getBirthday'           => null,
       'getBirthYear'          => 1940,
       'getBirthMonth'         => null,
       'getBirthDayOfMonth'    => null,
       'getGender'             => 'M',
       'getSequenceNumber'     => 953,
       'getType'               => RijksregisternummerHelper::TYPE_RIJKSREGISTERNUMMER],
      // Rijksregisternummer: voorbeeld uit IT000_Rijksregisternummer.pdf.
      ['rijksregisternummer'   => '40.00.01-001.33',
       'isBis'                 => false,
       'isRijksregisternummer' => true,
       'isSelfAssigned'        => false,
       'isKnownBirthday'       => false,
       'getBirthday'           => null,
       'getBirthYear'          => 1940,
       'getBirthMonth'         => null,
       'getBirthDayOfMonth'    => null,
       'getGender'             => 'M',
       'getSequenceNumber'     => 1,
       'getType'               => RijksregisternummerHelper::TYPE_RIJKSREGISTERNUMMER],
      // Rijksregisternummer: female, known birthday in 19xx.
      ['rijksregisternummer'   => '66.04.10-666.60',
       'isBis'                 => false,
       'isRijksregisternummer' => true,
       'isSelfAssigned'        => false,
       'isKnownBirthday'       => true,
       'getBirthday'           => '1966-04-10',
       'getBirthYear'          => 1966,
       'getBirthMonth'         => 4,
       'getBirthDayOfMonth'    => 10,
       'getGender'             => 'F',
       'getSequenceNumber'     => 666,
       'getType'               => RijksregisternummerHelper::TYPE_RIJKSREGISTERNUMMER],
      // Rijksregisternummer: male, known birthday in 19xx.
      ['rijksregisternummer'   => '66.04.10-997.20',
       'isBis'                 => false,
       'isRijksregisternummer' => true,
       'isSelfAssigned'        => false,
       'isKnownBirthday'       => true,
       'getBirthday'           => '1966-04-10',
       'getBirthYear'          => 1966,
       'getBirthMonth'         => 4,
       'getBirthDayOfMonth'    => 10,
       'getGender'             => 'M',
       'getSequenceNumber'     => 997,
       'getType'               => RijksregisternummerHelper::TYPE_RIJKSREGISTERNUMMER],
      // Rijksregisternummer: male, known birthday in 20xx.
      ['rijksregisternummer'   => '01.02.03-005.66',
       'isBis'                 => false,
       'isRijksregisternummer' => true,
       'isSelfAssigned'        => false,
       'isKnownBirthday'       => true,
       'getBirthday'           => '2001-02-03',
       'getBirthYear'          => 2001,
       'getBirthMonth'         => 2,
       'getBirthDayOfMonth'    => 3,
       'getGender'             => 'M',
       'getSequenceNumber'     => 5,
       'getType'               => RijksregisternummerHelper::TYPE_RIJKSREGISTERNUMMER],
      // Rijksregisternummer: male, only year birth year is known.
      ['rijksregisternummer'   => '40.00.00-953.81',
       'isBis'                 => false,
       'isRijksregisternummer' => true,
       'isSelfAssigned'        => false,
       'isKnownBirthday'       => false,
       'getBirthday'           => null,
       'getBirthYear'          => 1940,
       'getBirthMonth'         => null,
       'getBirthDayOfMonth'    => null,
       'getGender'             => 'M',
       'getSequenceNumber'     => 953,
       'getType'               => RijksregisternummerHelper::TYPE_RIJKSREGISTERNUMMER],
      // Rijksregisternummer: male, only year birth year is known.
      ['rijksregisternummer'   => '40.00.01-001.33',
       'isBis'                 => false,
       'isRijksregisternummer' => true,
       'isSelfAssigned'        => false,
       'isKnownBirthday'       => false,
       'getBirthday'           => null,
       'getBirthYear'          => 1940,
       'getBirthMonth'         => null,
       'getBirthDayOfMonth'    => null,
       'getGender'             => 'M',
       'getSequenceNumber'     => 1,
       'getType'               => RijksregisternummerHelper::TYPE_RIJKSREGISTERNUMMER],
      // Rijksregisternummer: male, only year birth year is known.
      ['rijksregisternummer'   => '65.00.03-131.77',
       'isBis'                 => false,
       'isRijksregisternummer' => true,
       'isSelfAssigned'        => false,
       'isKnownBirthday'       => false,
       'getBirthday'           => null,
       'getBirthYear'          => 1965,
       'getBirthMonth'         => null,
       'getBirthDayOfMonth'    => null,
       'getGender'             => 'M',
       'getSequenceNumber'     => 131,
       'getType'               => RijksregisternummerHelper::TYPE_RIJKSREGISTERNUMMER],
      // Rijksregisternummer: male, unknown year of birth.
      ['rijksregisternummer'   => '00.00.01-123.41',
       'isBis'                 => false,
       'isRijksregisternummer' => true,
       'isSelfAssigned'        => false,
       'isKnownBirthday'       => false,
       'getBirthday'           => null,
       'getBirthYear'          => null,
       'getBirthMonth'         => null,
       'getBirthDayOfMonth'    => null,
       'getGender'             => 'M',
       'getSequenceNumber'     => 123,
       'getType'               => RijksregisternummerHelper::TYPE_RIJKSREGISTERNUMMER],
      // Rijksregisternummer: female, unknown year of birth.
      ['rijksregisternummer'   => '00.00.01-124.69',
       'isBis'                 => false,
       'isRijksregisternummer' => true,
       'isSelfAssigned'        => false,
       'isKnownBirthday'       => false,
       'getBirthday'           => null,
       'getBirthYear'          => null,
       'getBirthMonth'         => null,
       'getBirthDayOfMonth'    => null,
       'getGender'             => 'F',
       'getSequenceNumber'     => 124,
       'getType'               => RijksregisternummerHelper::TYPE_RIJKSREGISTERNUMMER],
      // Bisnummer: unknown gender, known birthday.
      ['rijksregisternummer'   => '66.24.10-666.06',
       'isBis'                 => true,
       'isRijksregisternummer' => false,
       'isSelfAssigned'        => false,
       'isKnownBirthday'       => true,
       'getBirthday'           => '1966-04-10',
       'getBirthYear'          => 1966,
       'getBirthMonth'         => 4,
       'getBirthDayOfMonth'    => 10,
       'getGender'             => '',
       'getSequenceNumber'     => 666,
       'getType'               => RijksregisternummerHelper::TYPE_BISNUMMER_UNKNOWN_GENDER],
      // Bisnummer: unknown gender, known birthday.
      ['rijksregisternummer'   => '66.24.10-666.06',
       'isBis'                 => true,
       'isRijksregisternummer' => false,
       'isSelfAssigned'        => false,
       'isKnownBirthday'       => true,
       'getBirthday'           => '1966-04-10',
       'getBirthYear'          => 1966,
       'getBirthMonth'         => 4,
       'getBirthDayOfMonth'    => 10,
       'getGender'             => '',
       'getSequenceNumber'     => 666,
       'getType'               => RijksregisternummerHelper::TYPE_BISNUMMER_UNKNOWN_GENDER],
      // Bisnummer: unknown gender, known year of birth.
      ['rijksregisternummer'   => '52.20.01-043.92',
       'isBis'                 => true,
       'isRijksregisternummer' => false,
       'isSelfAssigned'        => false,
       'isKnownBirthday'       => false,
       'getBirthday'           => null,
       'getBirthYear'          => 1952,
       'getBirthMonth'         => null,
       'getBirthDayOfMonth'    => null,
       'getGender'             => '',
       'getSequenceNumber'     => 43,
       'getType'               => RijksregisternummerHelper::TYPE_BISNUMMER_UNKNOWN_GENDER],
      // Bisnummer: unknown gender, unknown year of birth.
      ['rijksregisternummer'   => '00.20.01-016.26',
       'isBis'                 => true,
       'isRijksregisternummer' => false,
       'isSelfAssigned'        => false,
       'isKnownBirthday'       => false,
       'getBirthday'           => null,
       'getBirthYear'          => null,
       'getBirthMonth'         => null,
       'getBirthDayOfMonth'    => null,
       'getGender'             => '',
       'getSequenceNumber'     => 16,
       'getType'               => RijksregisternummerHelper::TYPE_BISNUMMER_UNKNOWN_GENDER],
      // Bisnummer: female, known birthday.
      ['rijksregisternummer'   => '66.44.10-666.49',
       'isBis'                 => true,
       'isRijksregisternummer' => false,
       'isSelfAssigned'        => false,
       'isKnownBirthday'       => true,
       'getBirthday'           => '1966-04-10',
       'getBirthYear'          => 1966,
       'getBirthMonth'         => 4,
       'getBirthDayOfMonth'    => 10,
       'getGender'             => 'F',
       'getSequenceNumber'     => 666,
       'getType'               => RijksregisternummerHelper::TYPE_BISNUMMER_KNOWN_GENDER],
      // Bisnummer: male, known birthday.
      ['rijksregisternummer'   => '66.44.10-997.09',
       'isBis'                 => true,
       'isRijksregisternummer' => false,
       'isSelfAssigned'        => false,
       'isKnownBirthday'       => true,
       'getBirthday'           => '1966-04-10',
       'getBirthYear'          => 1966,
       'getBirthMonth'         => 4,
       'getBirthDayOfMonth'    => 10,
       'getGender'             => 'M',
       'getSequenceNumber'     => 997,
       'getType'               => RijksregisternummerHelper::TYPE_BISNUMMER_KNOWN_GENDER],
      // Bisnummer: male, unknown year of birth.
      ['rijksregisternummer'   => '00.40.01-033.52',
       'isBis'                 => true,
       'isRijksregisternummer' => false,
       'isSelfAssigned'        => false,
       'isKnownBirthday'       => false,
       'getBirthday'           => null,
       'getBirthYear'          => null,
       'getBirthMonth'         => null,
       'getBirthDayOfMonth'    => null,
       'getGender'             => 'M',
       'getSequenceNumber'     => 33,
       'getType'               => RijksregisternummerHelper::TYPE_BISNUMMER_KNOWN_GENDER],
      // Bisnummer: female, unknown birthday.
      ['rijksregisternummer'   => '00.40.01-008.77',
       'isBis'                 => true,
       'isRijksregisternummer' => false,
       'isSelfAssigned'        => false,
       'isKnownBirthday'       => false,
       'getBirthday'           => null,
       'getBirthYear'          => null,
       'getBirthMonth'         => null,
       'getBirthDayOfMonth'    => null,
       'getGender'             => 'F',
       'getSequenceNumber'     => 8,
       'getType'               => RijksregisternummerHelper::TYPE_BISNUMMER_KNOWN_GENDER],
      // Self-assigned: female, known birthday.
      ['rijksregisternummer'   => '66.64.10-666.92',
       'isBis'                 => false,
       'isRijksregisternummer' => false,
       'isSelfAssigned'        => true,
       'isKnownBirthday'       => true,
       'getBirthday'           => '1966-04-10',
       'getBirthYear'          => 1966,
       'getBirthMonth'         => 4,
       'getBirthDayOfMonth'    => 10,
       'getGender'             => 'F',
       'getSequenceNumber'     => 666,
       'getType'               => RijksregisternummerHelper::TYPE_SELF_ASSIGNED],
      // Self-assigned: male, known birthday.
      ['rijksregisternummer'   => '66.64.10-997.52',
       'isBis'                 => false,
       'isRijksregisternummer' => false,
       'isSelfAssigned'        => true,
       'isKnownBirthday'       => true,
       'getBirthday'           => '1966-04-10',
       'getBirthYear'          => 1966,
       'getBirthMonth'         => 4,
       'getBirthDayOfMonth'    => 10,
       'getGender'             => 'M',
       'getSequenceNumber'     => 997,
       'getType'               => RijksregisternummerHelper::TYPE_SELF_ASSIGNED],
      // Self-assigned: female, known year of birth.
      ['rijksregisternummer'   => '52.60.01-004.23',
       'isBis'                 => false,
       'isRijksregisternummer' => false,
       'isSelfAssigned'        => true,
       'isKnownBirthday'       => false,
       'getBirthday'           => null,
       'getBirthYear'          => 1952,
       'getBirthMonth'         => null,
       'getBirthDayOfMonth'    => null,
       'getGender'             => 'F',
       'getSequenceNumber'     => 4,
       'getType'               => RijksregisternummerHelper::TYPE_SELF_ASSIGNED],
      // Self-assigned: male, unknown year of birth.
      ['rijksregisternummer'   => '00.60.01-013.18',
       'isBis'                 => false,
       'isRijksregisternummer' => false,
       'isSelfAssigned'        => true,
       'isKnownBirthday'       => false,
       'getBirthday'           => null,
       'getBirthYear'          => null,
       'getBirthMonth'         => null,
       'getBirthDayOfMonth'    => null,
       'getGender'             => 'M',
       'getSequenceNumber'     => 13,
       'getType'               => RijksregisternummerHelper::TYPE_SELF_ASSIGNED],
      // Self-assigned: female, unknown year of birth.
      ['rijksregisternummer'   => '00.60.01-014.17',
       'isBis'                 => false,
       'isRijksregisternummer' => false,
       'isSelfAssigned'        => true,
       'isKnownBirthday'       => false,
       'getBirthday'           => null,
       'getBirthYear'          => null,
       'getBirthMonth'         => null,
       'getBirthDayOfMonth'    => null,
       'getGender'             => 'F',
       'getSequenceNumber'     => 14,
       'getType'               => RijksregisternummerHelper::TYPE_SELF_ASSIGNED],
      // Self-assigned: female, unknown year of birth, cycled through sequence number.
      ['rijksregisternummer'   => '00.60.02-049.49',
       'isBis'                 => false,
       'isRijksregisternummer' => false,
       'isSelfAssigned'        => true,
       'isKnownBirthday'       => false,
       'getBirthday'           => null,
       'getBirthYear'          => null,
       'getBirthMonth'         => null,
       'getBirthDayOfMonth'    => null,
       'getGender'             => 'M',
       'getSequenceNumber'     => 49,
       'getType'               => RijksregisternummerHelper::TYPE_SELF_ASSIGNED],
    ];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for creating rijksregisternummers.
   */
  public function testCreate(): void
  {
    $rijksregisternummer = Rijksregisternummer::create('1966-04-10', 666, RijksregisternummerHelper::TYPE_SELF_ASSIGNED);
    self::assertSame($rijksregisternummer->humanFormat(), '66.64.10-666.92');

    $rijksregisternummer = Rijksregisternummer::create('1966-04-10', 666);
    self::assertSame($rijksregisternummer->humanFormat(), '66.04.10-666.60');

    $rijksregisternummer = Rijksregisternummer::create('2001-02-03', 5);
    self::assertSame($rijksregisternummer->humanFormat(), '01.02.03-005.66');

    $rijksregisternummer = Rijksregisternummer::create('1940-00-00', 953);
    self::assertSame($rijksregisternummer->humanFormat(), '40.00.00-953.81');

    $rijksregisternummer = Rijksregisternummer::create('1940-00-01', 1);
    self::assertSame($rijksregisternummer->humanFormat(), '40.00.01-001.33');

    $rijksregisternummer = Rijksregisternummer::create('1965-00-03', 131);
    self::assertSame($rijksregisternummer->humanFormat(), '65.00.03-131.77');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test create with invalid type.
   */
  public function testCreateInvalidType(): void
  {
    $this->expectException(FallenException::class);

    Rijksregisternummer::create(date('Y-m-d'), 1, -1);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with invalid rijksregisternummers.
   *
   * @dataProvider getInvalidValues
   */
  public function testInvalid($identificationNumber): void
  {
    $this->expectException(\UnexpectedValueException::class);

    new Rijksregisternummer($identificationNumber);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests with valid identification numbers
   *
   * @dataProvider getValidTestCases
   */
  public function testValid(string  $identificationNumber,
                            bool    $isBis,
                            bool    $isRijksregisternummer,
                            bool    $isSelfAssigned,
                            bool    $isKnownBirthday,
                            ?string $getBirthday,
                            ?int    $getBirthYear,
                            ?int    $getBirthMonth,
                            ?int    $getBirthDayOfMonth,
                            string  $getGender,
                            int     $getSequenceNumber,
                            int     $getType): void
  {
    $rijksregisternummer = new Rijksregisternummer($identificationNumber);
    self::assertSame($isBis, $rijksregisternummer->isBis());
    self::assertSame($isRijksregisternummer, $rijksregisternummer->isRijksregisternummer());
    self::assertSame($isSelfAssigned, $rijksregisternummer->isSelfAssigned());
    self::assertSame($isKnownBirthday, $rijksregisternummer->isKnownBirthday());
    self::assertSame($getBirthday, $rijksregisternummer->getBirthday());
    self::assertSame($getBirthYear, $rijksregisternummer->getBirthYear());
    self::assertSame($getBirthMonth, $rijksregisternummer->getBirthMonth());
    self::assertSame($getBirthDayOfMonth, $rijksregisternummer->getBirthDayOfMonth());
    self::assertSame($getGender, $rijksregisternummer->getGender());
    self::assertSame($getSequenceNumber, $rijksregisternummer->getSequenceNumber());
    self::assertSame($getType, $rijksregisternummer->getType());
    self::assertSame($identificationNumber, $rijksregisternummer->humanFormat());
    self::assertSame(strtr($identificationNumber, ['-' => '', '.' => '']), $rijksregisternummer->machineFormat());
    self::assertSame($identificationNumber, (string)$rijksregisternummer);
    if ($getBirthday!==null)
    {
      self::assertSame($identificationNumber, (string)Rijksregisternummer::create($getBirthday,
                                                                                  $getSequenceNumber,
                                                                                  $getType));
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
