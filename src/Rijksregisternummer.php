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
   * The identification number of the National Register.
   *
   * @var string
   */
  private $rijksregisternummer;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string|null $rijksregisternummer  The identification number of the National Register.
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
      $message = sprintf("'%s' is not a valid identification number of the National Register", $rijksregisternummer);
      throw new \UnexpectedValueException($message);
    }

    $this->rijksregisternummer = $cleaned;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns this identification number of the National Register as a string in yy.mm.dd-nnn.cc format.
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
   * Extracts and returns the birthday in ISO 8601 format of this identification number of the National Register. If and
   * only if the birthday is unknown returns null.
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
   * Extracts and returns the gender from this identification number of the National Register.
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
   * Returns this identification number of the National Register in human readable format (yy.mm.dd-nnn.cc).
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
   * Returns true if this identification number of the National Register is a bisnummer, false otherwise.
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
   * Returns true if this identification number is based on a known birthday.
   *
   * @return bool
   */
  public function isKnownBirthday(): bool
  {
    return RijksregisternummerHelper::isKnownBirthday($this->rijksregisternummer);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if this identification number of the National Register is self assigned, false otherwise.
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
   * Returns this identification number of the National Register in machine format, i.e. digits only.
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
