<?php
//----------------------------------------------------------------------------------------------------------------------
namespace Helper;

use SetBased\Rijksregisternummer\Rijksregisternummer;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Test cases for Rijksregisternummer.
 */
class RijksregisternummerTest extends \PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests for extracting birthday
   */
  public function testBirthday()
  {
    $rijksregisternummer = new Rijksregisternummer('66.64.10-666.92');
    $this->assertSame('1966-04-10', $rijksregisternummer->getBirthday());

    $rijksregisternummer = new Rijksregisternummer('66.04.10-666.60');
    $this->assertSame('1966-04-10', $rijksregisternummer->getBirthday());
  }
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests for extracting gender.
   */
  public function testGender()
  {
    $rijksregisternummer = new Rijksregisternummer('66.04.10-666.60');
    $this->assertSame('F', $rijksregisternummer->getGender());

    $rijksregisternummer = new Rijksregisternummer('66.04.10-997.20');
    $this->assertSame('M', $rijksregisternummer->getGender());

    $rijksregisternummer = new Rijksregisternummer('66.24.10-666.06');
    $this->assertSame('', $rijksregisternummer->getGender());

    $rijksregisternummer = new Rijksregisternummer('66.24.10-997.63');
    $this->assertSame('', $rijksregisternummer->getGender());

    $rijksregisternummer = new Rijksregisternummer('66.44.10-666.49');
    $this->assertSame('F', $rijksregisternummer->getGender());

    $rijksregisternummer = new Rijksregisternummer('66.44.10-997.09');
    $this->assertSame('M', $rijksregisternummer->getGender());

    $rijksregisternummer = new Rijksregisternummer('66.64.10-666.92');
    $this->assertSame('F', $rijksregisternummer->getGender());

    $rijksregisternummer = new Rijksregisternummer('66.64.10-997.52');
    $this->assertSame('M', $rijksregisternummer->getGender());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests for humanFormat.
   */
  public function testHumanFormat()
  {
    $rijksregisternummer = new Rijksregisternummer('66.04.10-666.60');
    $this->assertSame('66.04.10-666.60', $rijksregisternummer->humanFormat());

    $rijksregisternummer = new Rijksregisternummer('93051822361');
    $this->assertSame('93.05.18-223.61', $rijksregisternummer->humanFormat());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with an invalid rijksregisternummer (invalid check).
   *
   * @expectedException \UnexpectedValueException
   */
  public function testInvalid01()
  {
    new Rijksregisternummer('66.04.10-666.00');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with an invalid rijksregisternummer (invalid check).
   *
   * @expectedException \UnexpectedValueException
   */
  public function testInvalid02()
  {
    new Rijksregisternummer('66.04.10-000.47');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with an invalid rijksregisternummer (invalid check).
   *
   * @expectedException \UnexpectedValueException
   */
  public function testInvalid03()
  {
    new Rijksregisternummer('66.04.10-999.18');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with an invalid rijksregisternummer (invalid birthday).
   *
   * @expectedException \UnexpectedValueException
   */
  public function testInvalid04()
  {
    new Rijksregisternummer('66.02.30-001.14');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with an invalid rijksregisternummer (not a number).
   *
   * @expectedException \UnexpectedValueException
   */
  public function testInvalid05()
  {
    new Rijksregisternummer('Rare jongens, die Romeinen');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests for is bisnummer.
   */
  public function testIsBis()
  {
    $rijksregisternummer = new Rijksregisternummer('66.64.10-666.92');
    $this->assertFalse($rijksregisternummer->isBis());

    $rijksregisternummer = new Rijksregisternummer('66.04.10-666.60');
    $this->assertFalse($rijksregisternummer->isBis());

    $rijksregisternummer = new Rijksregisternummer('66.24.10-666.06');
    $this->assertTrue($rijksregisternummer->isBis());

    $rijksregisternummer = new Rijksregisternummer('66.44.10-666.49');
    $this->assertTrue($rijksregisternummer->isBis());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests for self assigned.
   */
  public function testIsSelfAssigned()
  {
    $rijksregisternummer = new Rijksregisternummer('66.64.10-666.92');
    $this->assertTrue($rijksregisternummer->isSelfAssigned());

    $rijksregisternummer = new Rijksregisternummer('66.04.10-666.60');
    $this->assertFalse($rijksregisternummer->isSelfAssigned());

    $rijksregisternummer = new Rijksregisternummer('66.24.10-666.06');
    $this->assertFalse($rijksregisternummer->isSelfAssigned());

    $rijksregisternummer = new Rijksregisternummer('66.44.10-666.49');
    $this->assertFalse($rijksregisternummer->isSelfAssigned());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests for machineFormat.
   */
  public function testMachineFormat()
  {
    $rijksregisternummer = new Rijksregisternummer('66.04.10-666.60');
    $this->assertSame('66041066660', $rijksregisternummer->machineFormat());

    $rijksregisternummer = new Rijksregisternummer('93051822361');
    $this->assertSame('93051822361', $rijksregisternummer->machineFormat());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests for machineFormat.
   */
  public function testToString()
  {
    $rijksregisternummer = new Rijksregisternummer('66.04.10-666.60');
    $this->assertSame('66.04.10-666.60', (string)$rijksregisternummer);

    $rijksregisternummer = new Rijksregisternummer('93051822361');
    $this->assertSame('93.05.18-223.61', (string)$rijksregisternummer);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with a valid rijksregisternummer.
   */
  public function testValid01()
  {
    new Rijksregisternummer('66.04.10-666.60');
    $this->assertTrue(true);

    new Rijksregisternummer('93051822361');
    $this->assertTrue(true);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
