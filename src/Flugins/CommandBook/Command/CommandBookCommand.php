<?php
declare(strict_types=1);

namespace Flugins\CommandBook\Command;

use Flugins\CommandBook\Form\CommandBookForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

final class CommandBookCommand extends Command
{
    public function __construct()
    {
        $this->setPermission('cmb.op');
        parent::__construct('커맨드북', '커맨드북 관련 명령어 입니다', '/커맨드북', ['commandbook', '명령어책', '커맨드책', '명령어북']);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$this->testPermission($sender)) return;
        if(!$sender instanceof Player) return;
        $sender->sendForm(new CommandBookForm());
    }
}
