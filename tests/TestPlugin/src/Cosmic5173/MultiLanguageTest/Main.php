<?php

namespace Cosmic5173\MultiLanguageTest;

use Cosmic5173\MultiLanguage\language\Language;
use Cosmic5173\MultiLanguage\MultiLanguageHandler;
use Cosmic5173\MultiLanguageTest\languages\English;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\plugin\PluginBase;

final class Main extends PluginBase implements Listener {

    protected function onEnable(): void {
        MultiLanguageHandler::getInstance()->register($this, 10, "https://raw.githubusercontent.com/PocketMine-MP-Libraries/MutliLanguage/master/tests/TestFiles/");

        Language::registerLanguage(new English());

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onJoin(PlayerJoinEvent $event) {
        $event->getPlayer()->sendMessage(Language::getLanguage("en_us")->translate("test-translation"));
    }
}