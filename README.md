# Rijksregisternummer

<table>
<thead>
<tr>
<th>Social</th>
<th>Legal</th>
<th>Release</th>
<th>Tests</th>
<th>Code</th>
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
<a href="https://travis-ci.org/SetBased/php-rijksregisternummer"><img src="https://travis-ci.org/SetBased/php-rijksregisternummer.svg?branch=master" alt="Build Status"/></a><br/>
<a href="https://scrutinizer-ci.com/g/SetBased/php-rijksregisternummer/?branch=master"><img src="https://scrutinizer-ci.com/g/SetBased/php-rijksregisternummer/badges/coverage.png?b=master" alt="Code Coverage"/></a>
</td>
<td>
<a href="https://scrutinizer-ci.com/g/SetBased/php-rijksregisternummer/?branch=master"><img src="https://scrutinizer-ci.com/g/SetBased/php-rijksregisternummer/badges/quality-score.png?b=master" alt="Scrutinizer Code Quality"/></a>
</td>
</tr>
</tbody>
</table>



#  License
  
The project is licensed under the MIT license.
 
# Usage

### Validating a national registry number

Constructing a new `Rijksregisternummer` will throw a `\UnexpectedValueException` if the number is invalid.

```php
try {
  $rijksregisternummer = new Rijksregisternummer('wrong-value');
} catch(\UnexpectedValueException $e){
  // That was invalid
}
```

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
