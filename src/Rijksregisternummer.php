<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Rijksregisternummer;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Class for identification number of the National Register (Rijksregisternummer), see @link
 * https://nl.wikipedia.org/wiki/Rijksregisternummer
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
   * @param string $rijksregisternummer  The identification number of the National Register.
   * @param string $formattingCharacters A regular expression with allowed formatting characters the must be filtered
   *                                     out. Use '/\D/' the remove all none digits.
   *
   * @since 1.0.0
   * @api
   */
  public function __construct($rijksregisternummer, $formattingCharacters = '/[\.\-\ ]/')
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
  public function __toString()
  {
    return RijksregisternummerHelper::format($this->rijksregisternummer);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the birthday of this identification number of the National Register.ister.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function getBirthday()
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
  public function getGender()
  {
    return RijksregisternummerHelper::getGender($this->rijksregisternummer);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns this identification number of the National Register in human readable format (yy.mm.dd-nnn.cc).
   *
   * @since 1.0.0
   * @api
   */
  public function humanFormat()
  {
    return RijksregisternummerHelper::format($this->rijksregisternummer);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if this identification number of the National Register is a bisnummer. Otherwise, returns false.
   *
   * @return bool
   *
   * @since 1.0.0
   * @api
   */
  public function isBis()
  {
    return RijksregisternummerHelper::isBis($this->rijksregisternummer);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if this identification number of the National Register is self assigned. Otherwise, returns false.
   *
   * @return bool
   *
   * @since 1.0.0
   * @api
   */
  public function isSelfAssigned()
  {
    return RijksregisternummerHelper::isSelfAssigned($this->rijksregisternummer);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns this identification number of the National Register in machine format, i.e. digits only.
   *
   * @since 1.0.0
   * @api
   */
  public function machineFormat()
  {
    return $this->rijksregisternummer;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
