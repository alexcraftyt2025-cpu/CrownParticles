<?php

namespace CrownParticles;

use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\level\particle\FlameParticle;
use pocketmine\Player;
use pocketmine\math\Vector3;

class Main extends PluginBase {

    /** @var array<string, string> Almacena el tipo de partícula activa por jugador (ej. "fire", "none") */
    private $activeParticlesType = [];

    public function onEnable() : void {
        // Mensaje de activación actualizado
        $this->getLogger()->info("§aPlugin CrownParticles Activado."); 
        $this->getServer()->getCommandMap()->register("particulas", new ParticleCommand($this));
        
        // Ejecuta la tarea de partículas cada 3 "ticks" para un efecto más fluido
        $this->getScheduler()->scheduleRepeatingTask(new ClosureTask(
            function(int $currentTick) : void {
                $this->spawnParticles();
            }
        ), 3);
    }

    // --- Lógica del Plugin ---

    public function setPlayerParticleType(Player $player, string $type) : void {
        $name = $player->getName();
        if ($type === "none") {
            unset($this->activeParticlesType[$name]);
            $player->sendMessage("§ePartículas §cDesactivadas.");
        } else {
            $this->activeParticlesType[$name] = $type;
            $player->sendMessage("§ePartículas de tipo '§b{$type}§e' §aActivadas.");
        }
    }

    private function spawnParticles() : void {
        // ... (El resto de la función spawnParticles es igual, pero usa el nuevo namespace en el objeto Player)
        foreach ($this->activeParticlesType as $playerName => $type) {
            $player = $this->getServer()->getPlayerExact($playerName); 
            if ($player instanceof Player && $player->isOnline() && $player->getLevel() !== null) {
                switch ($type) {
                    case "fire":
                        $this->spawnFireCrown($player);
                        break;
                    default:
                        break;
                }
            } else {
                unset($this->activeParticlesType[$playerName]);
            }
        }
    }

    private function spawnFireCrown(Player $player) : void {
        $center = $player->asVector3()->add(0, $player->getEyeHeight() + 0.5, 0); 
        $radius = 0.6; 
        $numParticles = 12; 
        $heightOffset = 0.2;
        $level = $player->getLevel();

        for ($i = 0; $i < $numParticles; $i++) {
            $angle = ($i / $numParticles) * M_PI * 2; 
            
            $x = $center->x + $radius * cos($angle);
            $y = $center->y + $heightOffset; 
            $z = $center->z + $radius * sin($angle);
            
            $pos = new Vector3($x, $y, $z);
            $particle = new FlameParticle($pos); 

            $level->addParticle($particle);
        }
    }
}
