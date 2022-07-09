<?php
/**
 *  Copyright (c) 2022 hachkingtohach1
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is
 *  furnished to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 *  SOFTWARE.
 */
namespace hachkingtohach1\reallycheat\utils;

use hachkingtohach1\reallycheat\components\block\BlockLegacyIds;
use pocketmine\entity\Location;
use pocketmine\math\Vector3;
use pocketmine\world\Position;

class BlockUtil{

    public static function isOnGround(Location $location, int $down) :bool{
        $id = [BlockLegacyIds::AIR];
        $posX = $location->getX();
        $posZ = $location->getZ();
        $fracX = (fmod($posX, 1.0) > 0.0) ? abs(fmod($posX, 1.0)) : (1.0 - abs(fmod($posX, 1.0)));
        $fracZ = (fmod($posZ, 1.0) > 0.0) ? abs(fmod($posZ, 1.0)) : (1.0 - abs(fmod($posZ, 1.0)));
        $blockX = $location->getX();
        $blockY = $location->getY() - $down;
        $blockZ = $location->getZ();
        $world = $location->getWorld();
        if(!in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ))->getId(), $id)) return true;
        if($fracX < 0.3){
            if(!in_array($world->getBlock(new Vector3($blockX - 1, $blockY, $blockZ))->getId(), $id)) return true;
            if($fracZ < 0.3){
                if(!in_array($world->getBlock(new Vector3($blockX - 1, $blockY, $blockZ - 1))->getId(), $id)) return true;
                if(!in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ - 1))->getId(), $id)) return true;
                if(!in_array($world->getBlock(new Vector3($blockX + 1, $blockY, $blockZ - 1))->getId(), $id)) return true;
            }elseif($fracZ > 0.7){
                if(!in_array($world->getBlock(new Vector3($blockX - 1, $blockY, $blockZ + 1))->getId(), $id)) return true;
                if(!in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ + 1))->getId(), $id)) return true;
                if(!in_array($world->getBlock(new Vector3($blockX + 1, $blockY, $blockZ + 1))->getId(), $id)) return true;
            }
        }elseif($fracX > 0.7){
            if(!in_array($world->getBlock(new Vector3($blockX + 1, $blockY, $blockZ))->getId(), $id)) return true;
            if($fracZ < 0.3){
                if(!in_array($world->getBlock(new Vector3($blockX - 1, $blockY, $blockZ - 1))->getId(), $id)) return true;
                if(!in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ - 1))->getId(), $id)) return true;
                if(!in_array($world->getBlock(new Vector3($blockX + 1, $blockY, $blockZ - 1))->getId(), $id)) return true;
            }elseif($fracZ > 0.7){
                if(!in_array($world->getBlock(new Vector3($blockX - 1, $blockY, $blockZ + 1))->getId(), $id)) return true;
                if(!in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ + 1))->getId(), $id)) return true;
                if(!in_array($world->getBlock(new Vector3($blockX + 1, $blockY, $blockZ + 1))->getId(), $id)) return true;
            }
        }elseif($fracZ < 0.3){
            if(!in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ - 1))->getId(), $id)) return true;
        }elseif($fracZ > 0.7 && !in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ + 1))->getId(), $id)) return true;
        return false;
    }

    public static function isUnderBlock(Location $location, array $id, int $down) :bool{
        $posX = $location->getX();
        $posZ = $location->getZ();
        $fracX = (fmod($posX, 1.0) > 0.0) ? abs(fmod($posX, 1.0)) : (1.0 - abs(fmod($posX, 1.0)));
        $fracZ = (fmod($posZ, 1.0) > 0.0) ? abs(fmod($posZ, 1.0)) : (1.0 - abs(fmod($posZ, 1.0)));
        $blockX = $location->getX();
        $blockY = $location->getY() - $down;
        $blockZ = $location->getZ();
        $world = $location->getWorld();
        if(in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ))->getId(), $id)) return true;
        if($fracX < 0.3){
            if(in_array($world->getBlock(new Vector3($blockX - 1, $blockY, $blockZ))->getId(), $id)) return true;
            if($fracZ < 0.3){
                if(in_array($world->getBlock(new Vector3($blockX - 1, $blockY, $blockZ - 1))->getId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ - 1))->getId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX + 1, $blockY, $blockZ - 1))->getId(), $id)) return true;
            }elseif($fracZ > 0.7){
                if(in_array($world->getBlock(new Vector3($blockX - 1, $blockY, $blockZ + 1))->getId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ + 1))->getId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX + 1, $blockY, $blockZ + 1))->getId(), $id)) return true;
            }
        }elseif($fracX > 0.7){
            if(in_array($world->getBlock(new Vector3($blockX + 1, $blockY, $blockZ))->getId(), $id)) return true;
            if($fracZ < 0.3){
                if(in_array($world->getBlock(new Vector3($blockX - 1, $blockY, $blockZ - 1))->getId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ - 1))->getId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX + 1, $blockY, $blockZ - 1))->getId(), $id)) return true;
            }elseif($fracZ > 0.7){
                if(in_array($world->getBlock(new Vector3($blockX - 1, $blockY, $blockZ + 1))->getId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ + 1))->getId(), $id)) return true;
                if(in_array($world->getBlock(new Vector3($blockX + 1, $blockY, $blockZ + 1))->getId(), $id)) return true;
            }
        }elseif($fracZ < 0.3){
            if(in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ - 1))->getId(), $id)) return true;
        }elseif($fracZ > 0.7 && in_array($world->getBlock(new Vector3($blockX, $blockY, $blockZ + 1))->getId(), $id)) return true;
        return false;
    }

    public static function isOnStairs(Location $location, int $down) :bool{
        $stairs = [
            BlockLegacyIds::STONE_STAIRS,
            BlockLegacyIds::OAK_STAIRS,
            BlockLegacyIds::BIRCH_STAIRS,
            BlockLegacyIds::BRICK_STAIRS,
            BlockLegacyIds::STONE_BRICK_STAIRS,
            BlockLegacyIds::ACACIA_STAIRS,
            BlockLegacyIds::JUNGLE_STAIRS,
            BlockLegacyIds::PURPUR_STAIRS,
            BlockLegacyIds::QUARTZ_STAIRS,
            BlockLegacyIds::SPRUCE_STAIRS,
            BlockLegacyIds::WOODEN_STAIRS,
            BlockLegacyIds::DIORITE_STAIRS,
            BlockLegacyIds::GRANITE_STAIRS,
            BlockLegacyIds::ANDESITE_STAIRS,
            BlockLegacyIds::DARK_OAK_STAIRS,
            BlockLegacyIds::END_BRICK_STAIRS,
            BlockLegacyIds::SANDSTONE_STAIRS,
            BlockLegacyIds::PRISMARINE_STAIRS,
            BlockLegacyIds::COBBLESTONE_STAIRS,
            BlockLegacyIds::NETHER_BRICK_STAIRS,
            BlockLegacyIds::NORMAL_STONE_STAIRS,
            BlockLegacyIds::RED_SANDSTONE_STAIRS,
            BlockLegacyIds::SMOOTH_QUARTZ_STAIRS,
            BlockLegacyIds::DARK_PRISMARINE_STAIRS,
            BlockLegacyIds::POLISHED_DIORITE_STAIRS,
            BlockLegacyIds::POLISHED_GRANITE_STAIRS,
            BlockLegacyIds::RED_NETHER_BRICK_STAIRS,
            BlockLegacyIds::SMOOTH_SANDSTONE_STAIRS,
            BlockLegacyIds::MOSSY_COBBLESTONE_STAIRS,
            BlockLegacyIds::MOSSY_STONE_BRICK_STAIRS,
            BlockLegacyIds::POLISHED_ANDESITE_STAIRS,
            BlockLegacyIds::PRISMARINE_BRICKS_STAIRS,
            BlockLegacyIds::SMOOTH_RED_SANDSTONE_STAIRS
        ];
        return self::isUnderBlock($location, $stairs, $down);
    }

    public static function isOnIce(Location $location, int $down) :bool{
        $ice = [
            BlockLegacyIds::ICE,
            BlockLegacyIds::BLUE_ICE,
            BlockLegacyIds::PACKED_ICE,
            BlockLegacyIds::FROSTED_ICE
        ];
        return self::isUnderBlock($location, $ice, $down);
    }

    public static function isOnLiquid(Location $location, int $down) :bool{
        $liquid = [
            BlockLegacyIds::WATER,
            BlockLegacyIds::LAVA,
            BlockLegacyIds::FLOWING_WATER,
            BlockLegacyIds::FLOWING_LAVA
        ];
        return self::isUnderBlock($location, $liquid, $down);
    }

    public static function isOnAdhesion(Location $location, int $down) :bool{
        $adhesion = [
            BlockLegacyIds::LADDER,
            BlockLegacyIds::VINES,
            BlockLegacyIds::SCAFFOLDING
        ];
        return self::isUnderBlock($location, $adhesion, $down);
    }

    public static function isOnPlant(Location $location, int $down) :bool{
        $plants = [
            BlockLegacyIds::GRASS_PATH,
            BlockLegacyIds::CARROT_BLOCK,
            BlockLegacyIds::SUGARCANE_BLOCK,
            BlockLegacyIds::PUMPKIN_STEM,
            BlockLegacyIds::POTATO_BLOCK,
            BlockLegacyIds::DEAD_BUSH,
            BlockLegacyIds::SWEET_BERRY_BUSH,
            BlockLegacyIds::SAPLING,
            BlockLegacyIds::SEAGRASS,
            BlockLegacyIds::WHEAT_BLOCK,
            BlockLegacyIds::TALL_GRASS,
            BlockLegacyIds::RED_FLOWER,
            BlockLegacyIds::CHORUS_FLOWER,
            BlockLegacyIds::YELLOW_FLOWER,
            BlockLegacyIds::DOUBLE_PLANT,
            BlockLegacyIds::FLOWER_POT_BLOCK,
            BlockLegacyIds::NETHER_WART_PLANT
        ];
        return self::isUnderBlock($location, $plants, $down);
    }

    public static function isOnDoor(Location $location, int $down) :bool{
        $doors = [
            BlockLegacyIds::OAK_DOOR_BLOCK,
            BlockLegacyIds::IRON_DOOR_BLOCK,
            BlockLegacyIds::DARK_OAK_DOOR_BLOCK,
            BlockLegacyIds::BIRCH_DOOR_BLOCK,
            BlockLegacyIds::ACACIA_DOOR_BLOCK,
            BlockLegacyIds::JUNGLE_DOOR_BLOCK,
            BlockLegacyIds::SPRUCE_DOOR_BLOCK,
            BlockLegacyIds::WOODEN_DOOR_BLOCK,
            BlockLegacyIds::DARK_OAK_TRAPDOOR,
            BlockLegacyIds::TRAPDOOR,
            BlockLegacyIds::IRON_TRAPDOOR,
            BlockLegacyIds::BIRCH_TRAPDOOR,
            BlockLegacyIds::ACACIA_TRAPDOOR,
            BlockLegacyIds::JUNGLE_TRAPDOOR,
            BlockLegacyIds::SPRUCE_TRAPDOOR,
            BlockLegacyIds::WOODEN_TRAPDOOR,
            BlockLegacyIds::DARK_OAK_TRAPDOOR
        ];
        return self::isUnderBlock($location, $doors, $down);
    }

    public static function isOnCarpet(Location $location, int $down) :bool{
        $carpets = [
            BlockLegacyIds::CARPET
        ];
        return self::isUnderBlock($location, $carpets, $down);
    }

    public static function isOnPlate(Location $location, int $down) :bool{
        $plates = [
            BlockLegacyIds::CARPET,
            BlockLegacyIds::BIRCH_PRESSURE_PLATE,
            BlockLegacyIds::STONE_PRESSURE_PLATE,
            BlockLegacyIds::ACACIA_PRESSURE_PLATE,
            BlockLegacyIds::JUNGLE_PRESSURE_PLATE,
            BlockLegacyIds::SPRUCE_PRESSURE_PLATE,
            BlockLegacyIds::WOODEN_PRESSURE_PLATE,
            BlockLegacyIds::DARK_OAK_PRESSURE_PLATE,
            BlockLegacyIds::HEAVY_WEIGHTED_PRESSURE_PLATE,
            BlockLegacyIds::LIGHT_WEIGHTED_PRESSURE_PLATE
        ];
        return self::isUnderBlock($location, $plates, $down);
    }

    public static function isOnSnow(Location $location, int $down) :bool{
        $snow = [
            BlockLegacyIds::SNOW_BLOCK,
            BlockLegacyIds::SNOW_LAYER
        ];
        return self::isUnderBlock($location, $snow, $down);
    }

    public static function onSlimeBlock(Location $location, int $down) :bool{
        return self::isUnderBlock($location, [BlockLegacyIds::SLIME_BLOCK], $down);
    }

    public static function distance(Position $a, Position $b){
        return sqrt(pow($a->getX() - $b->getX(), 2) + pow($a->getY() - $b->getY(), 2) + pow($a->getZ() - $b->getZ(), 2));
    }
    
}