<?php

namespace ItzLightyHD\KnockbackFFA\event;

use ItzLightyHD\KnockbackFFA\Loader;
use pocketmine\event\Event;
use pocketmine\Player;

class PlayerKilledEvent extends Event {

    protected $plugin;
    protected $player;
    protected $damager;

    public function __construct(Player $player, Player $damager)
    {
        $this->plugin = Loader::getInstance();
        $this->player = $player;
        $this->damager = $damager;
    }

    public function getPlugin(): Loader
    {
        return $this->plugin;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getDamager(): Player
    {
        return $this->damager;
    }

}