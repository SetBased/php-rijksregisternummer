<?php
declare(strict_types=1);

namespace SetBased\Rijksregisternummer\Test;

use PHPUnit\Framework\Attributes\DataProvider;
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
  public static function getInvalidValues(): array
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
  public static function getValidTestCases(): array
  {
    return [// Rijksregisternummer: voorbeeld uit IT000_Rijksregisternummer.pdf.
            ['value'                 => '42.01.22-051.81',
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
             'getCheckDigits'        => 81,
             'getType'               => RijksregisternummerHelper::TYPE_RIJKSREGISTERNUMMER],
            // Rijksregisternummer: voorbeeld uit IT000_Rijksregisternummer.pdf.
            ['value'                 => '40.00.00-953.81',
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
             'getCheckDigits'        => 81,
             'getType'               => RijksregisternummerHelper::TYPE_RIJKSREGISTERNUMMER],
            // Rijksregisternummer: voorbeeld uit IT000_Rijksregisternummer.pdf.
            ['value'                 => '40.00.01-001.33',
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
             'getCheckDigits'        => 33,
             'getType'               => RijksregisternummerHelper::TYPE_RIJKSREGISTERNUMMER],
            // Rijksregisternummer: female, known birthday in 19xx.
            ['value'                 => '66.04.10-666.60',
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
             'getCheckDigits'        => 60,
             'getType'               => RijksregisternummerHelper::TYPE_RIJKSREGISTERNUMMER],
            // Rijksregisternummer: male, known birthday in 19xx.
            ['value'                 => '66.04.10-997.20',
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
             'getCheckDigits'        => 20,
             'getType'               => RijksregisternummerHelper::TYPE_RIJKSREGISTERNUMMER],
            // Rijksregisternummer: male, known birthday in 20xx.
            ['value'                 => '01.02.03-005.66',
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
             'getCheckDigits'        => 66,
             'getType'               => RijksregisternummerHelper::TYPE_RIJKSREGISTERNUMMER],
            // Rijksregisternummer: male, only year birth year is known.
            ['value'                 => '40.00.00-953.81',
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
             'getCheckDigits'        => 81,
             'getType'               => RijksregisternummerHelper::TYPE_RIJKSREGISTERNUMMER],
            // Rijksregisternummer: male, only year birth year is known.
            ['value'                 => '40.00.01-001.33',
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
             'getCheckDigits'        => 33,
             'getType'               => RijksregisternummerHelper::TYPE_RIJKSREGISTERNUMMER],
            // Rijksregisternummer: male, only year birth year is known.
            ['value'                 => '65.00.03-131.77',
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
             'getCheckDigits'        => 77,
             'getType'               => RijksregisternummerHelper::TYPE_RIJKSREGISTERNUMMER],
            // Rijksregisternummer: male, unknown year of birth.
            ['value'                 => '00.00.01-123.41',
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
             'getCheckDigits'        => 41,
             'getType'               => RijksregisternummerHelper::TYPE_RIJKSREGISTERNUMMER],
            // Rijksregisternummer: female, unknown year of birth.
            ['value'                 => '00.00.01-124.69',
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
             'getCheckDigits'        => 69,
             'getType'               => RijksregisternummerHelper::TYPE_RIJKSREGISTERNUMMER],
            // Bisnummer: unknown gender, known birthday.
            ['value'                 => '66.24.10-666.06',
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
             'getCheckDigits'        => 6,
             'getType'               => RijksregisternummerHelper::TYPE_BISNUMMER_UNKNOWN_GENDER],
            // Bisnummer: unknown gender, known birthday.
            ['value'                 => '66.24.10-666.06',
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
             'getCheckDigits'        => 6,
             'getType'               => RijksregisternummerHelper::TYPE_BISNUMMER_UNKNOWN_GENDER],
            // Bisnummer: unknown gender, known year of birth.
            ['value'                 => '52.20.01-043.92',
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
             'getCheckDigits'        => 92,
             'getType'               => RijksregisternummerHelper::TYPE_BISNUMMER_UNKNOWN_GENDER],
            // Bisnummer: unknown gender, unknown year of birth.
            ['value'                 => '00.20.01-016.26',
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
             'getCheckDigits'        => 26,
             'getType'               => RijksregisternummerHelper::TYPE_BISNUMMER_UNKNOWN_GENDER],
            // Bisnummer: female, known birthday.
            ['value'                 => '66.44.10-666.49',
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
             'getCheckDigits'        => 49,
             'getType'               => RijksregisternummerHelper::TYPE_BISNUMMER_KNOWN_GENDER],
            // Bisnummer: male, known birthday.
            ['value'                 => '66.44.10-997.09',
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
             'getCheckDigits'        => 9,
             'getType'               => RijksregisternummerHelper::TYPE_BISNUMMER_KNOWN_GENDER],
            // Bisnummer: male, unknown year of birth.
            ['value'                 => '00.40.01-033.52',
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
             'getCheckDigits'        => 52,
             'getType'               => RijksregisternummerHelper::TYPE_BISNUMMER_KNOWN_GENDER],
            // Bisnummer: female, unknown birthday.
            ['value'                 => '00.40.01-008.77',
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
             'getCheckDigits'        => 77,
             'getType'               => RijksregisternummerHelper::TYPE_BISNUMMER_KNOWN_GENDER],
            // Self-assigned: female, known birthday.
            ['value'                 => '66.64.10-666.92',
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
             'getCheckDigits'        => 92,
             'getType'               => RijksregisternummerHelper::TYPE_SELF_ASSIGNED],
            // Self-assigned: male, known birthday.
            ['value'                 => '66.64.10-997.52',
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
             'getCheckDigits'        => 52,
             'getType'               => RijksregisternummerHelper::TYPE_SELF_ASSIGNED],
            // Self-assigned: female, known year of birth.
            ['value'                 => '52.60.01-004.23',
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
             'getCheckDigits'        => 23,
             'getType'               => RijksregisternummerHelper::TYPE_SELF_ASSIGNED],
            // Self-assigned: male, unknown year of birth.
            ['value'                 => '00.60.01-013.18',
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
             'getCheckDigits'        => 18,
             'getType'               => RijksregisternummerHelper::TYPE_SELF_ASSIGNED],
            // Self-assigned: female, unknown year of birth.
            ['value'                 => '00.60.01-014.17',
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
             'getCheckDigits'        => 17,
             'getType'               => RijksregisternummerHelper::TYPE_SELF_ASSIGNED],
            // Self-assigned: female, unknown year of birth, cycled through sequence number.
            ['value'                 => '00.60.02-049.49',
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
             'getCheckDigits'        => 49,
             'getType'               => RijksregisternummerHelper::TYPE_SELF_ASSIGNED],];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for creating rijksregisternummers.
   */
  public function testCreate(): void
  {
    $rijksregisternummer = Rijksregisternummer::create('1966-04-10',
                                                       666,
                                                       RijksregisternummerHelper::TYPE_SELF_ASSIGNED);
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
   */
  #[DataProvider('getInvalidValues')]
  public function testInvalid($value): void
  {
    $this->expectException(\UnexpectedValueException::class);

    new Rijksregisternummer($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests with valid identification numbers
   */
  #[DataProvider('getValidTestCases')]
  public function testValid(string  $value,
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
                            int     $getCheckDigits,
                            int     $getType): void
  {
    $rijksregisternummer = new Rijksregisternummer($value);
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
    self::assertSame($getCheckDigits, $rijksregisternummer->getCheckDigits());
    self::assertSame($getType, $rijksregisternummer->getType());
    self::assertSame($value, $rijksregisternummer->humanFormat());
    self::assertSame(strtr($value, ['-' => '', '.' => '']), $rijksregisternummer->machineFormat());
    self::assertSame($value, (string)$rijksregisternummer);
    if ($getBirthday!==null)
    {
      self::assertSame($value,
                       (string)Rijksregisternummer::create($getBirthday,
                                                           $getSequenceNumber,
                                                           $getType));
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
