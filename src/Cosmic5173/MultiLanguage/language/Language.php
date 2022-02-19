<?php

/*
 *   Copyright (C) 2022  Cosmic5173
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *
 *   ----------------------------------------------------------------------
 *
 *
 *   Discord: Cosmic#8011
 *   Email: contact@cosmic5173.com
 *   Website: https://www.cosmic5173.com
 *   GitHub: https://github.cosmic5173.com
 *   Community Server: https://discord.cosmic5173.com
 *
 *   Thank you for using my work, I really do appreciate it!
 */

namespace Cosmic5173\MultiLanguage\language;

use Cosmic5173\MultiLanguage\MultiLanguageHandler;

abstract class Language {

    /**
     * Registry array of registered languages.
     * @var Language[]
     */
    private static array $registeredLanguages = [];

    /**
     * Check to see if a language has been registered.
     * @param string $languageID - ID of the language.
     * @return bool
     */
    public final static function isLanguageRegistered(string $languageID): bool {
        return isset(self::$registeredLanguages[$languageID]);
    }

    /**
     * Register a new language to the registry.
     * @param Language $language - Custom language instance extending Language.
     * @return void
     *
     * @throws \RuntimeException - Thrown if a language with the same ID already is registered.
     */
    public final static function registerLanguage(Language $language): void {
        if (!self::isLanguageRegistered($language->getID())) {
            self::$registeredLanguages[$language->getID()] = $language;
        } else {
            throw new \RuntimeException("Language with id: '{$language->getID()}' already exists.");
        }
    }

    /**
     * Unregister an existing language from the registry.
     * @param string $languageID - Existing language ID.
     * @return void
     *
     * @throws \RuntimeException - Thrown if language with the ID is not registered.
     */
    public final static function unregisterLanguage(string $languageID): void {
        if (self::isLanguageRegistered($languageID)) {
            unset(self::$registeredLanguages[$languageID]);
        } else {
            throw new \RuntimeException("Language with id: '{$languageID}' does not exist.");
        }
    }

    /**
     * Get a language by ID from the registry.
     * @param string $languageID
     * @return Language
     *
     * @throws \RuntimeException - Thrown if the language with the ID is not registered.
     */
    public final static function getLanguage(string $languageID): Language {
        if(self::isLanguageRegistered($languageID)) {
            return self::$registeredLanguages[$languageID];
        } else {
            throw new \RuntimeException("Language with id: '{$languageID}' does not exist.");
        }
    }

    /**
     * Get all registered languages.
     * @return Language[]
     */
    public final static function getRegisteredLanguages(): array {
        return self::$registeredLanguages;
    }

    // Individual Language Class Elements

    /** @var string|null */
    private string|null $version = null;
    /** @var array */
    private array $translations = [];


    /**
     * Translate a specified translation.
     * @param string $translationName
     * @param array $params
     * @return string
     *
     * @throws \RuntimeException - Thrown if the language source file is empty.
     * @throws \RuntimeException - Thrown if the translation does not exist in the language source.
     */
    public function translate(string $translationName, array $params = []): string {
        if (empty($this->translations) || !isset($this->version)) {
            $this->translations = $this->fetchFromSource();
            $this->version = $this->translations["version"];
            unset($this->translations["version"]);
            if (empty($this->translations)) {
                throw new \RuntimeException("Cannot use a blank source file for translations.");
            }
        }

        if (!isset($this->translations[$translationName]))
            throw new \RuntimeException("Translation '$translationName' does not exist in {$this->getID()} source file.");

        $translation = $this->translations[$translationName];

        foreach ($params as $i=>$value) {
            $translation = str_replace("%$i", $value, $translation);
        }

        return $translation;
    }

    /**
     * Check for language source file updates.
     * @internal
     *
     * @throws \RuntimeException - Thrown if the language source file is empty.
     */
    public function checkForUpdate(): void {
        $data = $this->fetchFromSource();
        if ($data["version"] !== $this->version) {
            $this->version = $data["version"];
            unset($data["version"]);

            if (empty($data)) {
                throw new \RuntimeException("Cannot use a blank source file for translations.");
            }

            $this->translations = $data;
        }
    }

    /**
     * Fetch language files from a language source file.
     * @internal
     *
     * @return array
     *
     * @throws \RuntimeException - Thrown if the language source file does not exist or could not be loaded.
     * @throws \RuntimeException - Thrown if the JSON Syntax is invalid.
     * @throws \RuntimeException - Thrown if the language source does not include a version field.
     */
    private function fetchFromSource(): array {
        $file = file_get_contents($this->getSourceLink() ?? MultiLanguageHandler::getInstance()->getSourceUrl().$this->getID().".json");

        if (!$file) {
            throw new \RuntimeException("Language source file could not be found or loaded.");
        } elseif (($translations = json_decode($file, true)) === null) {
            throw new \RuntimeException("Language source fine is invalid (syntax error).");
        } elseif (!isset($translations["version"]))
            throw new \RuntimeException("Language source does not include a version field.");

        return $translations;
    }

    /**
     * Get the language identifier.
     * @return string
     */
    public abstract function getID(): string;

    /**
     * Get the languages override source link.
     * @return string|null
     */
    public function getSourceLink(): ?string {
        return null;
    }
}