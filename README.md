# MultiLanguage
 
MultiLanguage is a library plugin for [PocketMine-MP](https://github.com/pmmp/PocketMine-MP) servers. It allows easy translations of any text, message, or content on your server. Language translations can both be fetched from files and links (outside sources).
 
## Features
 
- [x] Custom language creation.
- [x] Simple parameter system.
- [x] File or web address sources.
- [x] Per-language source address.
- [x] File versioning system.
- [x] Automatic updating system.
 
# Support
 
Join Support Server [Discord Invite](https://discord.cosmic5173.com) \
Email: [Mail To](mailto:support@cosmic5173.com)
 
# Usage
 
## Initialize Library
 
In order to start using the library you must first register the instance. The following is an example of how to do this when your plugin is first enabled. NOTE: This library can only be registered once per runtime, if another plugin has already registered it, you may skip this step and still use it.
 
$plugin -> Your plugin instance. \
$updateInterval -> amount of time (seconds) between each language source update. \
$updateUrl -> The base source endpoint for language source files. (Directory) Example: https://raw.githubusercontent.com/PocketMine-MP-Libraries/MutliLanguage/master/tests/TestFiles/
 
```php
use Cosmic5173\MultiLanguage\MultiLanguageHandler;
 
protected function onEnable(): void {
   MultiLanguageHandler::getInstance()->register($plugin, $updateInterval, $updateUrl);
}
```
 
## Managing Custom Languages
 
You can create an infinite amount of languages, as long as each language has a unique identifier. We will use the English language with id: ``en_us`` for this example.
 
### Language Class
 
Each class must have a class extending ``Language`` to be used later down the road. The most simple way to do this is to create a new file and class, however anonymous classes do work.
 
```php
use Cosmic5173\MultiLanguage\language\Language;
 
class English extends Language {
 
   public const IDENTIFIER = "en_us";
 
   public function getId(): string {
       return self::IDENTIFIER;
   }
}
```
 
### Registering Languages
 
Once you have created your language class, you must register it to the registry. This will allow it to be automatically updated and accessed from anywhere.
```php
use Cosmic5173\MultiLanguage\language\Language;
 
if(!Language::isLanguageRegistered(English::IDENTIFIER)) {
   Language::registerLanguage(new English());
}
```
 
### Accessing Languages
 
Any registered languages can be accessed at any time, from and place.
```php
/** @var English $english */
$english = Language::getLanguage(English::IDENTIFIER);
```
 
### Unregistering Languages
Languages can be removed from the registry at any point. This can be useful if you need to redefine an existing language.
```php
Language::unregisterLanguage(English::IDENTIFIER);
```
 
### Language API
 
Getting the Language Identifier:
```php
/** @var Language $language */
$language->getID();
```
 
Getting the Language Version: \
NOTE: ``getVersion()`` will return null if no language file has been loaded yet.
```php
/** @var Language $language */
$language->getVersion();
```
 
## Language Source
 
Each language has a source file where it pulls translations from. These files are a .json file where the key is the name and the value is the translation.
 
### File Format
```json
{
   "version": "This is the language version. Example: '1.0.0'",
   "translation-name": "Translation content.",
   "translation2-name": "Translation #1 content.",
   "translation-with-param": "Translation %0 content."
}
```
 
### Parameters
 
Language translations can have an infinite amount of parameters. These parameters are replaced with values when translated. Parameters are defined using ``%`` followed by a number. Examples: ``%0, %1, %2, %5, %12, $101``
 
### Translating Translations
 
To translate a translation you must first get the Language instance, then invoke the ``translate()`` method.
 
$translationName -> Name of the translation defined in the source file.
$parameterArray -> Array of parameters (index number defines the parameter (``%{index}``) that is to be replaced by the value)
 
```php
/** @var English $english */
$english = Language::getLanguage(English::IDENTIFIER);
$translatedText = $english->translate($translationName, $parameterArray);
```
 
### Source URLs
 
Source URLs are file paths or links to source file directories. Links and paths must have a forward or backward slash at the end. If the URL is an override (URL defined in class) then that must be a direct path to a file named ``{language identifier}.json``.
 
Examples:
Directory URL: https://raw.githubusercontent.com/PocketMine-MP-Libraries/MutliLanguage/master/tests/TestFiles/
Directory Path: ``resources/language/language-data/``
 
## Example Plugin
 
If you need some more help and want to look yourself, feel free to check out the test plugin attached to this project. [Test Plugin Link](https://github.com/PocketMine-MP-Libraries/MutliLanguage/tree/master/tests/TestPlugin)