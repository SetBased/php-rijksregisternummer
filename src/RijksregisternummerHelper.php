<?php
declare(strict_types=1);

namespace SetBased\Rijksregisternummer;

use SetBased\Exception\FallenException;

/**
 * Utility class for identification numbers of the National Register (Rijksregisternummer), bisnummers, and
 * self-assigned identification numbers, see @link https://nl.wikipedia.org/wiki/Rijksregisternummer.
 *
 * This is a low level utility class in the sense that all methods except method isValid() require valid values for
 * arguments $rijksregisternummer and $birthday.
 */
class RijksregisternummerHelper
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Type of identification number is Rijksregisternummer.
   */
  const TYPE_RIJKSREGISTERNUMMER = 1;

  /**
   * Type of identification number is bisnummer with unknown gender.
   */
  const TYPE_BISNUMMER_UNKNOWN_GENDER = 2;

  /**
   * Type of identification number is bisnummer with known gender.
   */
  const TYPE_BISNUMMER_KNOWN_GENDER = 4;

  /**
   * Type of identification number is self-assigned identification number.
   */
  const TYPE_SELF_ASSIGNED = 5;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Cleans an identification number, i.e. removes all non digits.
   *
   * @param string|null $rijksregisternummer  The unclean identification number.
   * @param string      $formattingCharacters A regular expression with allowed formatting characters the must be
   *                                          filtered out. Use '/\D/' the remove all none digits.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public static function clean(?string $rijksregisternummer, string $formattingCharacters = '/[\.\-\ ]/'): string
  {
    return preg_replace($formattingCharacters, '', $rijksregisternummer ?? '') ?? '';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Computes the check digits for an identification number.
   *
   * @param string $birthday       The birthday in [ISO 8601 format](https://en.wikipedia.org/wiki/ISO_8601).
   * @param int    $sequenceNumber The sequence number.
   * @param int    $type           The type of identification number.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public static function computeCheckDigits(string $birthday,
                                            int    $sequenceNumber,
                                            int    $type = self::TYPE_RIJKSREGISTERNUMMER): string
  {
    $year  = (int)substr($birthday, 0, 4);
    $month = (int)substr($birthday, 5, 2);
    $day   = (int)substr($birthday, 8, 2);

    $month = self::adjustMonth($month, $type);

    $number = $year % 100;
    $number = 100 * $number + $month;
    $number = 100 * $number + $day;

    if ($year>=2000)
    {
      $number += 2000000;
    }

    $number = 1000 * $number + $sequenceNumber;
    $check  = 97 - $number % 97;

    return sprintf('%02d', $check);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates and returns an identification number.
   *
   * @param string $birthday       The birthday in ISO 8601 format.
   * @param int    $sequenceNumber The sequence number.
   * @param int    $type           The type of identification number.
   *
   * @return string
   */
  public static function create(string $birthday,
                                int    $sequenceNumber,
                                int    $type = self::TYPE_RIJKSREGISTERNUMMER): string
  {
    $year2 = (int)substr($birthday, 2, 2);
    $month = (int)substr($birthday, 5, 2);
    $day   = (int)substr($birthday, 8, 2);
    $month = self::adjustMonth($month, $type);
    $check = self::computeCheckDigits($birthday, $sequenceNumber, $type);

    return sprintf('%02d%02d%02d%03d%02d', $year2, $month, $day, $sequenceNumber, $check);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Formats an identification number.
   *
   * @param string|null $rijksregisternummer The clean and valid identification number in machine format.
   *
   * @return string|null
   *
   * @since 1.0.0
   * @api
   */
  public static function format(?string $rijksregisternummer): ?string
  {
    if ($rijksregisternummer===null || preg_match('/^\d{11}$/', $rijksregisternummer)!==1)
    {
      return $rijksregisternummer;
    }

    $year    = substr($rijksregisternummer, 0, 2);
    $month   = substr($rijksregisternummer, 2, 2);
    $day     = substr($rijksregisternummer, 4, 2);
    $counter = substr($rijksregisternummer, 6, 3);
    $check   = substr($rijksregisternummer, 9, 2);

    return sprintf('%s.%s.%s-%s.%s', $year, $month, $day, $counter, $check);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Extracts and returns the day of the month of birth from an identification number. If and only if the day of the
   * month of birth is unknown returns null.
   *
   * @param string $rijksregisternummer The clean and valid identification number in machine format.
   *
   * @return int|null
   *
   * @since 1.0.0
   * @api
   */
  public static function getBirthDayOfMonth(string $rijksregisternummer): ?int
  {
    [$year, $month, $day] = self::extractBirthdayParts($rijksregisternummer);

    if ($month===0)
    {
      return null;
    }

    return $day;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Extracts and returns the month of birth from an identification number. If and only if the month birth is unknown
   * returns null.
   *
   * @param string $rijksregisternummer The clean and valid identification number in machine format.
   *
   * @return int|null
   *
   * @since 1.0.0
   * @api
   */
  public static function getBirthMonth(string $rijksregisternummer): ?int
  {
    [$year, $month, $day] = self::extractBirthdayParts($rijksregisternummer);

    if ($month===0)
    {
      return null;
    }

    return self::reAdjustMonth($month);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Extracts and returns the year of birth from an identification number. If and only if the year birth is unknown
   * returns null.
   *
   * @param string $rijksregisternummer The clean and valid identification number in machine format.
   *
   * @return int|null
   *
   * @since 1.0.0
   * @api
   */
  public static function getBirthYear(string $rijksregisternummer): ?int
  {
    [$year, $month, $day] = self::extractBirthdayParts($rijksregisternummer);

    if (($year===1900 || $year===2000) && $month===0)
    {
      return null;
    }

    return $year;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Extracts and returns the birthday in ISO 8601 format from an identification number. If and only if the birthday is
   * unknown returns null.
   *
   * @param string $rijksregisternummer The clean and valid identification number in machine format.
   *
   * @return string|null
   *
   * @since 1.0.0
   * @api
   */
  public static function getBirthday(string $rijksregisternummer): ?string
  {
    [$year, $month, $day] = self::extractBirthdayParts($rijksregisternummer);

    if ($month===0)
    {
      return null;
    }

    return sprintf('%04d-%02d-%02d', $year, $month, $day);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the check digits of an identification number.
   *
   * @param string $rijksregisternummer The clean and valid identification number in machine format.
   *
   * @return int
   *
   * @since 1.3.0
   * @api
   */
  public static function getCheckDigits(string $rijksregisternummer): int
  {
    return (int)substr($rijksregisternummer, 9, 2);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Extracts and returns the gender from an identification number.
   * <ul>
   * <li> 'M': Male
   * <li> 'F': Female
   * <li> '': Unknown
   * </ul>
   *
   * @param string $rijksregisternummer The clean and valid identification number in machine format.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public static function getGender(string $rijksregisternummer): string
  {
    $month          = (int)substr($rijksregisternummer, 2, 2);
    $sequenceNumber = (int)substr($rijksregisternummer, 6, 3);

    if (20<=$month && $month<=32)
    {
      return '';
    }

    return (($sequenceNumber % 2)===0) ? 'F' : 'M';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the sequence number of an identification number.
   *
   * @param string $rijksregisternummer The clean and valid identification number in machine format.
   *
   * @return int
   *
   * @since 1.2.0
   * @api
   */
  public static function getSequenceNumber(string $rijksregisternummer): int
  {
    return (int)substr($rijksregisternummer, 6, 3);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the type of identification number of an identification number.
   *
   * @param string $rijksregisternummer The clean and valid identification number in machine format.
   *
   * @return int
   *
   * @since 1.2.0
   * @api
   */
  public static function getType(string $rijksregisternummer): int
  {
    $month = (int)substr($rijksregisternummer, 2, 2);
    switch (true)
    {
      case 0<=$month && $month<=12:
        $type = self::TYPE_RIJKSREGISTERNUMMER;
        break;

      case 20<=$month && $month<=32:
        $type = self::TYPE_BISNUMMER_UNKNOWN_GENDER;
        break;

      case 40<=$month && $month<=52:
        $type = self::TYPE_BISNUMMER_KNOWN_GENDER;
        break;

      case 60<=$month && $month<=72:
        $type = self::TYPE_SELF_ASSIGNED;
        break;

      default:
        throw new FallenException('month', $month);
    }

    return $type;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns whether an identification number is a bisnummer.
   *
   * @param string $rijksregisternummer The clean and valid identification number in machine format.
   *
   * @return bool
   *
   * @since 1.0.0
   * @api
   */
  public static function isBis(string $rijksregisternummer): bool
  {
    $month = (int)substr($rijksregisternummer, 2, 2);

    return (20<=$month && $month<=52);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns whether an identification number is based on a known birthday.
   *
   * @param string $rijksregisternummer The clean and valid identification number in machine format.
   *
   * @return bool
   */
  public static function isKnownBirthday(string $rijksregisternummer): bool
  {
    [$year, $month, $day] = self::extractBirthdayParts($rijksregisternummer);

    return ($month!==0);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns whether an identification number is a rijksregisternummer.
   *
   * @param string $rijksregisternummer The clean and valid identification number in machine format.
   *
   * @return bool
   *
   * @since 1.0.0
   * @api
   */
  public static function isRijksregisternummer(string $rijksregisternummer): bool
  {
    $month = (int)substr($rijksregisternummer, 2, 2);

    return (0<=$month && $month<=12);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns whether an identification number is self-assigned.
   *
   * @param string $rijksregisternummer The clean and valid identification number in machine format.
   *
   * @return bool
   *
   * @since 1.0.0
   * @api
   */
  public static function isSelfAssigned(string $rijksregisternummer): bool
  {
    $month = (int)substr($rijksregisternummer, 2, 2);

    return (60<=$month && $month<=72);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns whether a string is a valid identification number.
   *
   * @param string $rijksregisternummer The clean and valid identification number in machine format.
   *
   * @return bool
   *
   * @since 1.0.0
   * @api
   */
  public static function isValid(string $rijksregisternummer): bool
  {
    // Test the rijksregisternummer has exactly 11 digits.
    if (preg_match('/^\d{11}$/', $rijksregisternummer)!==1)
    {
      return false;
    }

    // Test check part.
    $part1 = (int)substr($rijksregisternummer, 0, 9);
    $part2 = (int)substr($rijksregisternummer, 9, 2);

    $check = 97 - ($part1 % 97);
    if ($check!==$part2)
    {
      $check = 97 - ((2000000000 + $part1) % 97);
      if ($check!==$part2)
      {
        return false;
      }
    }

    // Test birthday is valid.
    [$year, $month, $day] = self::extractBirthdayParts($rijksregisternummer);
    if ($month!==0 && !checkdate($month, $day, $year))
    {
      return false;
    }

    // Test counter. The counter must be between 1 and 998.
    $counter = (int)substr($rijksregisternummer, 6, 3);
    if (!(1<=$counter && $counter<=998))
    {
      return false;
    }

    return true;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adjust a month by taking into account modifications for bisnummers and self-assigned identification numbers.
   *
   * @param int $month The month number (1..12).
   * @param int $type  The type of identification number.
   *
   * @return int
   */
  private static function adjustMonth(int $month, int $type): int
  {
    switch ($type)
    {
      case self::TYPE_RIJKSREGISTERNUMMER:
        // Nothing to do.
        break;

      case self::TYPE_BISNUMMER_UNKNOWN_GENDER:
        $month += 20;
        break;

      case self::TYPE_BISNUMMER_KNOWN_GENDER:
        $month += 40;
        break;

      case self::TYPE_SELF_ASSIGNED:
        $month += 60;
        break;

      default:
        throw new FallenException('type', $type);
    }

    return $month;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Extracts the raw year, month, and day of the birthday of an identification number.
   *
   * @param string $rijksregisternummer The clean and valid identification number in machine format.
   *
   * @return int[]
   */
  private static function extractBirthdayParts(string $rijksregisternummer): array
  {
    $part1 = (int)substr($rijksregisternummer, 0, 9);
    $part2 = (int)substr($rijksregisternummer, 9, 2);

    $check    = 97 - ($part1 % 97);
    $born19xx = ($check===$part2);

    $year  = (int)((($born19xx) ? '19' : '20').substr($rijksregisternummer, 0, 2));
    $month = (int)substr($rijksregisternummer, 2, 2);
    $day   = (int)substr($rijksregisternummer, 4, 2);

    $month = self::reAdjustMonth($month);

    return [$year, $month, $day];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adjusts a month by taking into account modifications for bisnummers and self-assigned identification numbers.
   *
   * @param int $month The month.
   *
   * @return int
   */
  private static function reAdjustMonth(int $month): int
  {
    switch (true)
    {
      case 0<=$month && $month<=12:
        // A normal rijksregisternummer.
        break;

      case 20<=$month && $month<=32:
        // The rijksregisternummer is a bisnummer and gender is unknown.
        $month -= 20;
        break;

      case 40<=$month && $month<=52:
        // The rijksregisternummer is a bisnummer and gender is known.
        $month -= 40;
        break;

      case 60<=$month && $month<=72:
        // The rijksregisternummer is self assigned.
        $month -= 60;
        break;

      default:
        throw new FallenException('month', $month);
    }

    return $month;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
