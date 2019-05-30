<?php
declare(strict_types=1);

namespace SetBased\Rijksregisternummer;

use SetBased\Exception\FallenException;

/**
 * Utility class for identification number of the National Register (Rijksregisternummer), see @link
 * https://nl.wikipedia.org/wiki/Rijksregisternummer
 */
class RijksregisternummerHelper
{
  //--------------------------------------------------------------------------------------------------------------------
  const TYPE_RIJKSREGISTERNUMMER = 1;
  const TYPE_BISNUMMER_UNKNOWN_GENDER = 2;
  const TYPE_BISNUMMER_KNOWN_GENDER = 4;
  const TYPE_SELF_ASSIGNED = 5;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Cleans a identification number of the National Register, i.e. removes all non digits.
   *
   * @param string|null $rijksregisternummer  The unclean identification number of the National Register.
   * @param string      $formattingCharacters A regular expression with allowed formatting characters the must be
   *                                          filtered out. Use '/\D/' the remove all none digits.
   *
   * @return string|null
   *
   * @since 1.0.0
   * @api
   */
  public static function clean(?string $rijksregisternummer, string $formattingCharacters = '/[\.\-\ ]/'): ?string
  {
    $ret = preg_replace($formattingCharacters, '', $rijksregisternummer);

    if ($ret==='') return null;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Computes the check digits for a identification number of the National Register.
   *
   * @param string $birthday       The birthday in [ISO 8601 format](https://en.wikipedia.org/wiki/ISO_8601).
   * @param int    $sequenceNumber The sequence number.
   * @param int    $type
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public static function computeCheckDigits(string $birthday,
                                            int $sequenceNumber,
                                            int $type = self::TYPE_RIJKSREGISTERNUMMER): string
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
      $number += 2000000000;
    }

    $number = 1000 * $number + $sequenceNumber;

    $check = 97 - $number % 97;

    return sprintf('%02d', $check);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Formats an identification number of the National Register.
   *
   * @param string|null $rijksregisternummer The clean and valid identification number of the National Register.
   *
   * @return string|null
   *
   * @since 1.0.0
   * @api
   */
  public static function format(?string $rijksregisternummer): ?string
  {
    if ($rijksregisternummer===null || $rijksregisternummer==='' || mb_strlen($rijksregisternummer)<>11)
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
   * Extracts and returns the birthday in ISO 8601 format from an identification number of the National Register. If and
   * only if the birthday is unknown returns null.
   *
   * @param string $rijksregisternummer The clean and valid identification number of the National Register.
   *
   * @return string|null
   *
   * @since 1.0.0
   * @api
   */
  public static function getBirthday(string $rijksregisternummer): ?string
  {
    // Extract the birthday and sequence parts.
    $part1 = substr($rijksregisternummer, 0, 9);
    $part2 = substr($rijksregisternummer, 9, 2);

    $check    = 97 - (((int)$part1) % 97);
    $born1900 = ($check==$part2);

    // Test birthday is valid.
    $year  = (($born1900) ? '19' : '20').substr($rijksregisternummer, 0, 2);
    $month = (int)substr($rijksregisternummer, 2, 2);
    $day   = (int)substr($rijksregisternummer, 4, 2);

    if ($month==0)
    {
      // Birthday is unknown.
      return null;
    }

    $month = self::readjustMonth($month);

    return sprintf('%04d-%02d-%02d', $year, $month, $day);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Extracts and returns the gender from an identification number of the National Register.
   * <ul>
   * <li> 'M': Male
   * <li> 'F': Female
   * <li> '': Unknown
   * </ul>
   *
   * @param string $rijksregisternummer The clean and valid identification number of the National Register.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public static function getGender(string $rijksregisternummer): string
  {
    $month          = (int)substr($rijksregisternummer, 2, 2);
    $sequenceNumber = substr($rijksregisternummer, 6, 3);

    if (21<=$month && $month<=32)
    {
      return '';
    }

    return (($sequenceNumber % 2)==0) ? 'F' : 'M';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if an identification number of the National Register is a bisnummer, false otherwise.
   *
   * @param string $rijksregisternummer The clean and valid identification number of the National Register.
   *
   * @return bool
   *
   * @since 1.0.0
   * @api
   */
  public static function isBis(string $rijksregisternummer): bool
  {
    $month = (int)substr($rijksregisternummer, 2, 2);
    if (21<=$month && $month<=52)
    {
      return true;
    }

    return false;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if an identification number of the National Register is based on a known birthday.
   *
   * @param string $rijksregisternummer The clean and valid identification number of the National Register.
   *
   * @return bool
   */
  public static function isKnownBirthday(string $rijksregisternummer): bool
  {
    return (substr($rijksregisternummer, 2, 2)!='00');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if an identification number of the National Register is self assigned, false otherwise.
   *
   * @param string $rijksregisternummer The clean and valid identification number of the National Register.
   *
   * @return bool
   *
   * @since 1.0.0
   * @api
   */
  public static function isSelfAssigned(string $rijksregisternummer): bool
  {
    $month = (int)substr($rijksregisternummer, 2, 2);
    if (61<=$month && $month<=72)
    {
      return true;
    }

    return false;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if a string is a valid identification number of the National Register, false otherwise.
   *
   * @param string $rijksregisternummer The clean and valid identification number of the National Register.
   *
   * @return bool
   *
   * @since 1.0.0
   * @api
   */
  public static function isValid(string $rijksregisternummer): bool
  {
    // Test the rijksregisternummer has only digits.
    if (preg_match('/^\d+$/', $rijksregisternummer)!=1)
    {
      return false;
    }

    // Test length is 11.
    if (strlen($rijksregisternummer)<>11)
    {
      return false;
    }

    // Test check part.
    $part1 = (int)substr($rijksregisternummer, 0, 9);
    $part2 = (int)substr($rijksregisternummer, 9, 2);

    $check = 97 - ($part1 % 97);
    if ($check==$part2)
    {
      $born1900 = true;
    }
    else
    {
      $check = 97 - ((2000000000 + $part1) % 97);
      if ($check!=$part2)
      {
        return false;
      }
      $born1900 = false;
    }

    // Test birthday is valid.
    $year  = (int)((($born1900) ? '19' : '20').substr($rijksregisternummer, 0, 2));
    $month = (int)substr($rijksregisternummer, 2, 2);
    $day   = (int)substr($rijksregisternummer, 4, 2);

    if ($month==0)
    {
      return ((0<=$day && $day<=31) && (1900<=$year && $year<=idate('Y')));
    }

    $month = self::readjustMonth($month);

    if (!checkdate($month, $day, $year))
    {
      return false;
    }

    // Test counter. The counter must between 1 and 998.
    $counter = (int)substr($rijksregisternummer, 6, 3);
    if (!(1<=$counter && $counter<=998))
    {
      return false;
    }

    return true;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adjust the month by taking into account modifications for bisnummers and self assigned rijksregisternummers.
   *
   * @param int $month The month number (1..12).
   * @param int $type  The type of number.
   *
   * @return int
   */
  private static function adjustMonth(int $month, int $type): int
  {
    switch ($type)
    {
      case self::TYPE_RIJKSREGISTERNUMMER:
        // Nothing to do.
        ;
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
   * Adjust the month by taking into account modifications for bisnummers and self assigned rijksregisternummers.
   *
   * @param int $month The month.
   *
   * @return int
   */
  private static function readjustMonth(int $month): int
  {
    switch (true)
    {
      case 1<=$month && $month<=12:
        // A normal rijksregisternummer.
        ;
        break;

      case 21<=$month && $month<=32:
        // The rijksregisternummer is a bisnummer and gender is unknown.
        $month -= 20;
        break;

      case 41<=$month && $month<=52:
        // The rijksregisternummer is a bisnummer and gender is known.
        $month -= 40;
        break;

      case 61<=$month && $month<=72:
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
