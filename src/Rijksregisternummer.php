<?php
declare(strict_types=1);

namespace SetBased\Rijksregisternummer;

/**
 * Class for identification number of the National Register (Rijksregisternummer), see {@link
 * https://nl.wikipedia.org/wiki/Rijksregisternummer}
 */
class Rijksregisternummer
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The identification number.
   *
   * @var string
   */
  private string $rijksregisternummer;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string|null $rijksregisternummer  The identification number.
   * @param string      $formattingCharacters A regular expression with allowed formatting characters the must be
   *                                          filtered out. Use <code>'/\\\\D/'</code> to remove all none digits.
   *
   * @since 1.0.0
   * @api
   */
  public function __construct(?string $rijksregisternummer, string $formattingCharacters = '/[\.\-\ ]/')
  {
    $cleaned = RijksregisternummerHelper::clean($rijksregisternummer, $formattingCharacters);
    if (!RijksregisternummerHelper::isValid($cleaned))
    {
      $message = sprintf("'%s' is not a valid identification number", $rijksregisternummer);
      throw new \UnexpectedValueException($message);
    }

    $this->rijksregisternummer = $cleaned;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates and returns a Rijksregisternummer object.
   *
   * @param string $birthday       The birthday in ISO 8601 format.
   * @param int    $sequenceNumber The sequence number (between 1 and 998, odd for men, even for women).
   * @param int    $type           The type of identification number.
   *
   * @return Rijksregisternummer
   */
  public static function create(string $birthday,
                                int    $sequenceNumber,
                                int    $type = RijksregisternummerHelper::TYPE_RIJKSREGISTERNUMMER): Rijksregisternummer
  {
    return new self(RijksregisternummerHelper::create($birthday, $sequenceNumber, $type));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns this identification number as a string in yy.mm.dd-nnn.cc format.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function __toString(): string
  {
    return RijksregisternummerHelper::format($this->rijksregisternummer);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Extracts and returns the day of the month of birth of this identification number. If the day of the month of birth
   * is unknown returns null.
   *
   * @return int|null
   *
   * @since 1.0.0
   * @api
   */
  public function getBirthDayOfMonth(): ?int
  {
    return RijksregisternummerHelper::getBirthDayOfMonth($this->rijksregisternummer);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Extracts and returns the month of birth of this identification number. If the month of birth is unknown returns
   * null.
   *
   * @return int|null
   *
   * @since 1.0.0
   * @api
   */
  public function getBirthMonth(): ?int
  {
    return RijksregisternummerHelper::getBirthMonth($this->rijksregisternummer);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Extracts and returns the year of birthday of this identification number. If the year of birth is unknown returns
   * null.
   *
   * @return int|null
   *
   * @since 1.0.0
   * @api
   */
  public function getBirthYear(): ?int
  {
    return RijksregisternummerHelper::getBirthYear($this->rijksregisternummer);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Extracts and returns the birthday in ISO 8601 format of this identification number. If the birthday is unknown
   * returns null.
   *
   * @return string|null
   *
   * @since 1.0.0
   * @api
   */
  public function getBirthday(): ?string
  {
    return RijksregisternummerHelper::getBirthday($this->rijksregisternummer);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Extracts and returns the gender from this identification number.
   * <ul>
   * <li> 'M': Male
   * <li> 'F': Female
   * <li> '': Unknown
   * </ul>
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function getGender(): string
  {
    return RijksregisternummerHelper::getGender($this->rijksregisternummer);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the sequence number.
   *
   * @return int
   *
   * @since 1.2.0
   * @api
   */
  public function getSequenceNumber(): int
  {
    return RijksregisternummerHelper::getSequenceNumber($this->rijksregisternummer);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the type of identification number.
   *
   * @return int
   *
   * @since 1.2.0
   * @api
   */
  public function getType(): int
  {
    return RijksregisternummerHelper::getType($this->rijksregisternummer);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns this identification number in human-readable format (yy.mm.dd-nnn.cc).
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function humanFormat(): string
  {
    return RijksregisternummerHelper::format($this->rijksregisternummer);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns whether this identification number is a bisnummer.
   *
   * @return bool
   *
   * @since 1.0.0
   * @api
   */
  public function isBis(): bool
  {
    return RijksregisternummerHelper::isBis($this->rijksregisternummer);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns whether this identification number is based on a known birthday.
   *
   * @return bool
   */
  public function isKnownBirthday(): bool
  {
    return RijksregisternummerHelper::isKnownBirthday($this->rijksregisternummer);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns whether this identification number is a rijksregisternummer.
   *
   * @return bool
   *
   * @since 1.2.0
   * @api
   */
  public function isRijksregisternummer(): bool
  {
    return RijksregisternummerHelper::isRijksregisternummer($this->rijksregisternummer);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns whether this identification number is self-assigned.
   *
   * @return bool
   *
   * @since 1.0.0
   * @api
   */
  public function isSelfAssigned(): bool
  {
    return RijksregisternummerHelper::isSelfAssigned($this->rijksregisternummer);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns this identification number in machine format, i.e. digits only.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function machineFormat(): string
  {
    return $this->rijksregisternummer;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
