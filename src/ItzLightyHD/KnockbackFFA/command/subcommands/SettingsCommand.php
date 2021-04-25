<?php

namespace ItzLightyHD\KnockbackFFA\command\subcommands;

use ItzLightyHD\KnockbackFFA\Loader;
use ItzLightyHD\KnockbackFFA\utils\KnockbackPlayer;
use ItzLightyHD\KnockbackFFA\utils\GameSettings;
use CortexPE\Commando\BaseSubCommand;
use ItzLightyHD\KnockbackFFA\utils\KnockbackKit;
use jojoe77777\FormAPI\CustomForm;
use pocketmine\command\CommandSender;
use pocketmine\level\Level;
use pocketmine\item\Item;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\Player;
use pocketmine\Server;

class SettingsCommand extends BaseSubCommand {

    private $plugin;

    public function __construct(Loader $plugin)
    {
        $this->plugin = $plugin;
        parent::__construct("settings", "Customize the minigame directly from the game");
    }

    protected function prepare(): void
    {
        $this->setPermission("knockbackffa.customize");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if(!$sender instanceof Player) {
            $sender->sendMessage("§cOnly players are allowed to use this subcommand!");
            return;
        }
        $this->customizeGame($sender);
    }

    public function customizeGame(Player $player): void
    {
        $form = new CustomForm(function (Player $player, $data) {
            if($data == null) {
                return;
            }
            if($data[1] == true) {
                GameSettings::getInstance()->massive_knockback = true;
            } else {
                GameSettings::getInstance()->massive_knockback = false;
            }
            if($data[2] == true) {
                GameSettings::getInstance()->bow = true;
            } else {
                GameSettings::getInstance()->bow = false;
            }
            if($data[3] == true) {
                GameSettings::getInstance()->snowballs = true;
            } else {
                GameSettings::getInstance()->snowballs = false;
            }
            if($data[4] == true) {
                GameSettings::getInstance()->leap = true;
            } else {
                GameSettings::getInstance()->leap = false;
            }
            GameSettings::getInstance()->enchant_level = intval($data[5]);
            GameSettings::getInstance()->speed_level = intval($data[6]);
            GameSettings::getInstance()->jump_boost_level = intval($data[7]);
            $this->reloadGame(GameSettings::getInstance()->getConfig()->get("arena"));
        });
        $form->setTitle(GameSettings::getInstance()->getConfig()->get("prefix") . "§r§8Settings");
        $form->addLabel("Customize the game options here. If a value is blank, the effect will be disabled. After the server restart, the values will be the same as those from the configuration file.");
        $form->addToggle("Massive knockback", $this->isMassiveKnockbackEnabled());
        $form->addToggle("Bow", $this->isBowEnabled());
        $form->addToggle("Snowballs", $this->isSnowballsEnabled());
        $form->addToggle("Leap", $this->isLeapEnabled());
        $form->addInput("Stick's knockback level", GameSettings::getInstance()->enchant_level);
        $form->addInput("Bow's knockback level", GameSettings::getInstance()->knockback_level);
        $form->addInput("Speed level", GameSettings::getInstance()->speed_level);
        $form->addInput("Jump boost level", GameSettings::getInstance()->jump_boost_level);
        $player->sendForm($form);
    }

    public function reloadGame(string $world): void
    {
        if(Server::getInstance()->getLevelByName($world) instanceof Level) {
            foreach(Server::getInstance()->getLevelByName($world)->getPlayers() as $player) {
                $player->teleport(Server::getInstance()->getLevelByName($world)->getSpawnLocation());
                new KnockbackKit($player);
            }
        }
    }

    private function isMassiveKnockbackEnabled(): bool
    {
        if(GameSettings::getInstance()->massive_knockback == true) {
            return true;
        } else {
            return false;
        }
    }

    private function isBowEnabled(): bool
    {
        if(GameSettings::getInstance()->bow == true) {
            return true;
        } else {
            return false;
        }
    }

    private function isSnowballsEnabled(): bool
    {
        if(GameSettings::getInstance()->snowballs == true) {
            return true;
        } else {
            return false;
        }
    }

    private function isLeapEnabled(): bool
    {
        if(GameSettings::getInstance()->leap == true) {
            return true;
        } else {
            return false;
        }
    }

}