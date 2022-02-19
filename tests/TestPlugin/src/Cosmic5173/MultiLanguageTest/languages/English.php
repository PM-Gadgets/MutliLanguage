<?php

namespace Cosmic5173\MultiLanguageTest\languages;

use Cosmic5173\MultiLanguage\language\Language;

final class English extends Language {

    public function getID(): string {
        return "en_us";
    }
}