<?php
declare(strict_types=1);

namespace SetBased\Rijksregisternummer\Test;

use PHPUnit\Framework\TestCase;
use SetBased\Rijksregisternummer\Rijksregisternummer;

/**
 * Test cases for Rijksregisternummer.
 */
class RijksregisternummerTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests for extracting birthday
   */
  public function testBirthday(): void
  {
    $rijksregisternummer = new Rijksregisternummer('66.64.10-666.92');
    self::assertTrue($rijksregisternummer->isKnownBirthday());
    self::assertSame('1966-04-10', $rijksregisternummer->getBirthday());

    $rijksregisternummer = new Rijksregisternummer('66.04.10-666.60');
    self::assertTrue($rijksregisternummer->isKnownBirthday());
    self::assertSame('1966-04-10', $rijksregisternummer->getBirthday());

    $rijksregisternummer = new Rijksregisternummer('01.02.03-005.66');
    self::assertTrue($rijksregisternummer->isKnownBirthday());
    self::assertSame('2001-02-03', $rijksregisternummer->getBirthday());

    $rijksregisternummer = new Rijksregisternummer('40.00.00-953.81');
    self::assertFalse($rijksregisternummer->isKnownBirthday());
    self::assertNull($rijksregisternummer->getBirthday());

    $rijksregisternummer = new Rijksregisternummer('40.00.01-001.33');
    self::assertFalse($rijksregisternummer->isKnownBirthday());
    self::assertNull($rijksregisternummer->getBirthday());

    $rijksregisternummer = new Rijksregisternummer('65.00.03-131.77');
    self::assertFalse($rijksregisternummer->isKnownBirthday());
    self::assertNull($rijksregisternummer->getBirthday());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests for extracting gender.
   */
  public function testGender(): void
  {
    $rijksregisternummer = new Rijksregisternummer('66.04.10-666.60');
    self::assertSame('F', $rijksregisternummer->getGender());

    $rijksregisternummer = new Rijksregisternummer('66.04.10-997.20');
    self::assertSame('M', $rijksregisternummer->getGender());

    $rijksregisternummer = new Rijksregisternummer('66.24.10-666.06');
    self::assertSame('', $rijksregisternummer->getGender());

    $rijksregisternummer = new Rijksregisternummer('66.24.10-997.63');
    self::assertSame('', $rijksregisternummer->getGender());

    $rijksregisternummer = new Rijksregisternummer('66.44.10-666.49');
    self::assertSame('F', $rijksregisternummer->getGender());

    $rijksregisternummer = new Rijksregisternummer('66.44.10-997.09');
    self::assertSame('M', $rijksregisternummer->getGender());

    $rijksregisternummer = new Rijksregisternummer('66.64.10-666.92');
    self::assertSame('F', $rijksregisternummer->getGender());

    $rijksregisternummer = new Rijksregisternummer('66.64.10-997.52');
    self::assertSame('M', $rijksregisternummer->getGender());

    $rijksregisternummer = new Rijksregisternummer('01.02.03-005.66');
    self::assertSame('M', $rijksregisternummer->getGender());

    $rijksregisternummer = new Rijksregisternummer('40.00.00-953.81');
    self::assertSame('M', $rijksregisternummer->getGender());

    $rijksregisternummer = new Rijksregisternummer('40.00.01-001.33');
    self::assertSame('M', $rijksregisternummer->getGender());

    $rijksregisternummer = new Rijksregisternummer('65.00.03-131.77');
    self::assertSame('M', $rijksregisternummer->getGender());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests for humanFormat.
   */
  public function testHumanFormat(): void
  {
    $rijksregisternummer = new Rijksregisternummer('66.04.10-666.60');
    self::assertSame('66.04.10-666.60', $rijksregisternummer->humanFormat());

    $rijksregisternummer = new Rijksregisternummer('93051822361');
    self::assertSame('93.05.18-223.61', $rijksregisternummer->humanFormat());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with an invalid rijksregisternummer (invalid check).
   */
  public function testInvalid01(): void
  {
    $this->expectException(\UnexpectedValueException::class);

    new Rijksregisternummer('66.04.10-666.00');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with an invalid rijksregisternummer (invalid check).
   */
  public function testInvalid02(): void
  {
    $this->expectException(\UnexpectedValueException::class);

    new Rijksregisternummer('66.04.10-000.47');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with an invalid rijksregisternummer (invalid check).
   */
  public function testInvalid03(): void
  {
    $this->expectException(\UnexpectedValueException::class);

    new Rijksregisternummer('66.04.10-999.18');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with an invalid rijksregisternummer (invalid birthday).
   */
  public function testInvalid04(): void
  {
    $this->expectException(\UnexpectedValueException::class);

    new Rijksregisternummer('66.02.30-001.14');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with an invalid rijksregisternummer (not a number).
   */
  public function testInvalid05(): void
  {
    $this->expectException(\UnexpectedValueException::class);

    new Rijksregisternummer('Rare jongens, die Romeinen');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with an invalid rijksregisternummer (to many digits).
   */
  public function testInvalid06(): void
  {
    $this->expectException(\UnexpectedValueException::class);

    new Rijksregisternummer('660508123456');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests for is bisnummer.
   */
  public function testIsBis(): void
  {
    $rijksregisternummer = new Rijksregisternummer('66.64.10-666.92');
    self::assertFalse($rijksregisternummer->isBis());

    $rijksregisternummer = new Rijksregisternummer('66.04.10-666.60');
    self::assertFalse($rijksregisternummer->isBis());

    $rijksregisternummer = new Rijksregisternummer('01.02.03-005.66');
    self::assertFalse($rijksregisternummer->isBis());

    $rijksregisternummer = new Rijksregisternummer('66.24.10-666.06');
    self::assertTrue($rijksregisternummer->isBis());

    $rijksregisternummer = new Rijksregisternummer('66.44.10-666.49');
    self::assertTrue($rijksregisternummer->isBis());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests for self assigned.
   */
  public function testIsSelfAssigned(): void
  {
    $rijksregisternummer = new Rijksregisternummer('66.64.10-666.92');
    self::assertTrue($rijksregisternummer->isSelfAssigned());

    $rijksregisternummer = new Rijksregisternummer('66.04.10-666.60');
    self::assertFalse($rijksregisternummer->isSelfAssigned());

    $rijksregisternummer = new Rijksregisternummer('66.24.10-666.06');
    self::assertFalse($rijksregisternummer->isSelfAssigned());

    $rijksregisternummer = new Rijksregisternummer('66.44.10-666.49');
    self::assertFalse($rijksregisternummer->isSelfAssigned());

    $rijksregisternummer = new Rijksregisternummer('01.02.03-005.66');
    self::assertFalse($rijksregisternummer->isSelfAssigned());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests for machineFormat.
   */
  public function testMachineFormat(): void
  {
    $rijksregisternummer = new Rijksregisternummer('66.04.10-666.60');
    self::assertSame('66041066660', $rijksregisternummer->machineFormat());

    $rijksregisternummer = new Rijksregisternummer('93051822361');
    self::assertSame('93051822361', $rijksregisternummer->machineFormat());

    $rijksregisternummer = new Rijksregisternummer('01.02.03-005.66');
    self::assertSame('01020300566', $rijksregisternummer->machineFormat());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests for machineFormat.
   */
  public function testToString(): void
  {
    $rijksregisternummer = new Rijksregisternummer('66.04.10-666.60');
    self::assertSame('66.04.10-666.60', (string)$rijksregisternummer);

    $rijksregisternummer = new Rijksregisternummer('93051822361');
    self::assertSame('93.05.18-223.61', (string)$rijksregisternummer);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with a valid rijksregisternummer.
   */
  public function testValid01(): void
  {
    new Rijksregisternummer('66.04.10-666.60');
    self::assertTrue(true);

    new Rijksregisternummer('93051822361');
    self::assertTrue(true);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
