# Rijksregisternummer / Numéro de Registre National

<table>
<thead>
<tr>
<th>Social</th>
<th>Legal</th>
<th>Release</th>
<th>Tests</th>
</tr>
</thead>
<tbody>
<tr>
<td>
<a href="https://gitter.im/SetBased/php-rijksregisternummer?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge"><img src="https://badges.gitter.im/SetBased/php-rijksregisternummer.svg" alt="Gitter"/></a>
</td>
<td>
<a href="https://packagist.org/packages/setbased/rijksregisternummer"><img src="https://poser.pugx.org/setbased/rijksregisternummer/license" alt="License"/></a>
</td>
<td>
<a href="https://packagist.org/packages/setbased/rijksregisternummer"><img src="https://poser.pugx.org/setbased/rijksregisternummer/v/stable" alt="Latest Stable Version"/></a>
</td>
<td>
<a href="https://github.com/SetBased/php-rijksregisternummer/actions/workflows/unit.yml"><img src="https://github.com/SetBased/php-rijksregisternummer/actions/workflows/unit.yml/badge.svg" alt="Build Status"/></a><br/>
<a href="https://codecov.io/gh/SetBased/php-rijksregisternummer"><img src="https://codecov.io/gh/SetBased/php-rijksregisternummer/branch/master/graph/badge.svg" alt="Code Coverage"/></a>
</td>
</tr>
</tbody>
</table>

Detailed information about identification number of the National Register (NL: rijksregisternummer, FR: numéro de registre national) can be found at:
* https://nl.wikipedia.org/wiki/Rijksregisternummer
* https://fr.wikipedia.org/wiki/Numéro_de_registre_national
* https://www.ibz.rrn.fgov.be/fileadmin/user_upload/nl/rr/instructies/IT-lijst/IT000_Rijksregisternummer.pdf
* https://www.ibz.rrn.fgov.be/fileadmin/user_upload/fr/rn/fichier-rn/fichier-RN.pdf
 
## Usage

### Validating a national registry number

Validate check digits and whether the first digits form a valid date. 

```php
echo RijksregisternummerHelper::isValid('66041066600'); // true
echo RijksregisternummerHelper::isValid('66041066601'); // false
```

Extract the date of birth from a registry number.

```php
echo RijksregisternummerHelper::getBirthDay('66.64.10-666.92'); // '1966-04-10'
echo RijksregisternummerHelper::getBirthDay('40.00.01-001.33'); // null
```

Also constructing a new `Rijksregisternummer` will throw a `\UnexpectedValueException` if the number is invalid.

### Formatting a national registry number

Use the Helper to do simple string formatting. Invalid numbers will be returned as is.

```php
echo RijksregisternummerHelper::format('66041066600'); // '66.04.10-666.00'
```

Or create an instance.

```php
$rijksregisternummer = new Rijksregisternummer('93051822361');
echo $rijksregisternummer->humanFormat(); // '93.05.18-223.61'
```

Clean formatting characters from user input.

```php
echo RijksregisternummerHelper::clean('66.04.10-666.00'); // '66041066600'
```

##  License
  
The project is licensed under the MIT license.
