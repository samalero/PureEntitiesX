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

namespace revivalpmmp\pureentities\entity\animal\walking;

use revivalpmmp\pureentities\entity\animal\WalkingAnimal;
use pocketmine\entity\Rideable;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\entity\Creature;
use revivalpmmp\pureentities\data\Data;

class Donkey extends WalkingAnimal implements Rideable {
    const NETWORK_ID = Data::DONKEY;

    public $width = 0.75;
    public $height = 1.562;
    public $length = 1.2;
    public $speed = 1.0;

    public function getName() : string {
        return "Donkey";
    }

    public function getSpeed(): float {
        return $this->speed;
    }

    public function targetOption(Creature $creature, float $distance): bool {
        if ($creature instanceof Player) {
            return $creature->spawned && $creature->isAlive() && !$creature->closed && $creature->getInventory()->getItemInHand()->getId() == Item::WHEAT && $distance <= 49;
        }
        return false;
    }

    public function getDrops() : array {
        if ($this->isLootDropAllowed()) {
            return [Item::get(Item::LEATHER, 0, mt_rand(0, 2))];
        } else {
            return [];
        }
    }

    public function getMaxHealth() {
        return 20;
    }

    public function getKillExperience(): int {
        // babies drop more, to be implemented
        return mt_rand(1, 3);
    }

    /**
     * Just for Bluelight ...
     * @return null
     */
    public function getRidePosition() {
        return null;
    }

}
