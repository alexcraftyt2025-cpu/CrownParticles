<?php

namespace CrownParticles;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ParticleCommand extends Command {
    
    /** @var Main */
    private $plugin;

    public function __construct(Main $plugin) {
        parent::__construct("particulas", "Controla las partículas del jugador (corona de fuego, etc.).", "/particulas [tipo]");
        $this->plugin = $plugin;
        // Permiso actualizado
        $this->setPermission("crownparticles.command"); 
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : bool {
        // ... (El resto de la función execute es igual)
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "Este comando solo puede ser usado por un jugador.");
            return false;
        }

        if (empty($args[0])) {
            $sender->sendMessage(TextFormat::YELLOW . "Uso: /particulas [tipo]");
            $sender->sendMessage(TextFormat::YELLOW . "Tipos disponibles: " . TextFormat::AQUA . "fire" . TextFormat::YELLOW . ", " . TextFormat::GRAY . "none");
            return false;
        }

        $type = strtolower($args[0]);

        switch ($type) {
            case "fire":
                $this->plugin->setPlayerParticleType($sender, "fire");
                break;
            case "none":
                $this->plugin->setPlayerParticleType($sender, "none");
                break;
            default:
                $sender->sendMessage(TextFormat::RED . "Tipo de partícula desconocido: " . TextFormat::YELLOW . $type);
                $sender->sendMessage(TextFormat::YELLOW . "Tipos disponibles: " . TextFormat::AQUA . "fire" . TextFormat::YELLOW . ", " . TextFormat::GRAY . "none");
                return false;
        }

        return true;
    }
}
