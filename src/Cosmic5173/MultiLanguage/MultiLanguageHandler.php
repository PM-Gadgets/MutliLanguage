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

namespace Cosmic5173\MultiLanguage;

use Cosmic5173\MultiLanguage\task\UpdateLanguageSource;
use pocketmine\plugin\PluginBase;

final class MultiLanguageHandler extends PluginBase {

    /** @var MultiLanguageHandler */
    private static MultiLanguageHandler $instance;

    /** @var bool */
    private bool $registered = false;
    /** @var string */
    private string $sourceUrl;


    /**
     * Get the main instance of the library.
     * @return MultiLanguageHandler
     */
    public static function getInstance(): MultiLanguageHandler {
        return self::$instance;
    }

    protected function onLoad(): void {
        self::$instance = $this;
    }

    /**
     * Register the library to a plugin instance.
     * This method should only be ever called once.
     * @param PluginBase $plugin
     * @param int $updateInterval - Seconds between calling an update for language source.
     * @param string $sourceUrl - URL to the source DIRECTORY of language files.
     * @return void
     *
     * @throws \RuntimeException - Thrown if the method has already been called.
     */
    public function register(PluginBase $plugin, int $updateInterval, string $sourceUrl): void {
        if(!$this->isRegistered()) {
            $this->sourceUrl = $sourceUrl;
            $this->registered = true;

            if ($updateInterval !== -1) $plugin->getScheduler()->scheduleDelayedTask(new UpdateLanguageSource(), $updateInterval*20);
        } else {
            throw new \RuntimeException("Library is already registered.");
        }
    }

    /**
     * Check to see of Loader::register() has been called.
     * @return bool
     */
    public function isRegistered(): bool {
        return $this->registered;
    }

    /**
     * Get the source URL for language source files.
     * @return string
     */
    public function getSourceUrl(): string {
        return $this->sourceUrl;
    }
}