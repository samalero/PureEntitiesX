<?php

/*  PureEntitiesX: Mob AI Plugin for PMMP
    Copyright (C) 2017 RevivalPMMP

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>. */

namespace revivalpmmp\pureentities\entity\monster\walking;

use revivalpmmp\pureentities\entity\monster\WalkingMonster;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\entity\Creature;
use pocketmine\Player;
use revivalpmmp\pureentities\data\Data;
use revivalpmmp\pureentities\utils\MobDamageCalculator;

class IronGolem extends WalkingMonster {
    const NETWORK_ID = Data::IRON_GOLEM;

    public $height = 2.688;
    public $width = 1.625;
    public $length = 0.906;
    public $speed = 0.8;

    public function getSpeed(): float {
        return $this->speed;
    }

    public function initEntity() {
        parent::initEntity();
        $this->setMaxHealth(20);
        $this->setHealth(20);

        $this->setFriendly(true);
        $this->setDamage([0, 21, 21, 21]);
        $this->setMinDamage([0, 7, 7, 7]);
    }

    public function getName() : string {
        return "IronGolem";
    }

    /**
     * Attack a player
     *
     * @param Entity $player
     */
    public function attackEntity(Entity $player) {
        if ($this->attackDelay > 10 && $this->distanceSquared($player) < 4) {
            $this->attackDelay = 0;

            $ev = new EntityDamageByEntityEvent($this, $player, EntityDamageEvent::CAUSE_ENTITY_ATTACK,
                MobDamageCalculator::calculateFinalDamage($player, $this->getDamage()));
            $player->attack($ev->getFinalDamage(), $ev);
            $player->setMotion(new Vector3(0, 0.7, 0));

            $this->checkTamedMobsAttack($player);
        }
    }

    public function targetOption(Creature $creature, float $distance): bool {
        if (!($creature instanceof Player)) {
            return $creature->isAlive() && $distance <= 60;
        }
        return false;
    }

    public function getDrops() : array {
        $drops = [];
        if ($this->isLootDropAllowed()) {
            array_push($drops, Item::get(Item::IRON_INGOT, 0, mt_rand(3, 5)));
            array_push($drops, Item::get(Item::POPPY, 0, mt_rand(0, 2)));
        }
        return $drops;
    }

    public function getMaxHealth() {
        return 20;
    }

}
