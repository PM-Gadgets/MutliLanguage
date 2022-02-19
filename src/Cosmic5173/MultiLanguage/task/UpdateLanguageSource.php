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

namespace Cosmic5173\MultiLanguage\task;

use Cosmic5173\MultiLanguage\language\Language;
use pocketmine\scheduler\Task;

final class UpdateLanguageSource extends Task {

    public function onRun(): void {
        foreach (Language::getRegisteredLanguages() as $language) {
            $language->checkForUpdate();
        }
    }
}