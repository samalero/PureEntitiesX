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

use revivalpmmp\pureentities\components\BreedingComponent;
use revivalpmmp\pureentities\entity\animal\WalkingAnimal;
use pocketmine\item\Item;
use revivalpmmp\pureentities\features\IntfCanBreed;
use revivalpmmp\pureentities\data\Data;
use revivalpmmp\pureentities\features\IntfCanInteract;

class Mooshroom extends WalkingAnimal implements IntfCanBreed, IntfCanInteract {
    const NETWORK_ID = Data::MOOSHROOM;

    public $height = 1.875;
    public $width = 0.891;
    public $length = 1.781;
    public $speed = 1.0;

    private $feedableItems = array(Item::WHEAT);

    /**
     * Is needed for breeding functionality
     *
     * @var BreedingComponent
     */
    private $breedableClass;

    public function initEntity() {
        parent::initEntity();
        $this->breedableClass = new BreedingComponent($this);
        $this->breedableClass->init();
    }

    public function getSpeed(): float {
        return $this->speed;
    }

    public function getName() : string {
        return "Mooshroom";
    }

    public function saveNBT() {
        parent::saveNBT();
        $this->breedableClass->saveNBT();
    }

    /**
     * Returns the breedable class or NULL if not configured
     *
     * @return BreedingComponent
     */
    public function getBreedingComponent() {
        return $this->breedableClass;
    }

    /**
     * Returns the appropriate NetworkID associated with this entity
     * @return int
     */
    public function getNetworkId() {
        return self::NETWORK_ID;
    }

    /**
     * Returns the items that can be fed to the entity
     *
     * @return array
     */
    public function getFeedableItems() {
        return $this->feedableItems;
    }

    public function getDrops() : array {
        $drops = [];
        if ($this->isLootDropAllowed()) {
            array_push($drops, Item::get(Item::LEATHER, 0, mt_rand(0, 2)));
            if ($this->isOnFire()) {
                array_push($drops, Item::get(Item::COOKED_BEEF, 0, mt_rand(1, 3)));
            } else {
                array_push($drops, Item::get(Item::RAW_BEEF, 0, mt_rand(1, 3)));
            }
        }
        return $drops;
    }

    public function getMaxHealth() {
        return 10;
    }

    public function getKillExperience(): int {
        if ($this->getBreedingComponent()->isBaby()) {
            return mt_rand(1, 7);
        }
        return mt_rand(1, 3);
    }

}
