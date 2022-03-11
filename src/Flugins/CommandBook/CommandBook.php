<?php
declare(strict_types=1);

namespace Flugins\CommandBook;

use Flugins\CommandBook\Command\CommandBookCommand;
use pocketmine\event\EventPriority;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\permission\DefaultPermissions;
use pocketmine\plugin\PluginBase;
use function str_replace;

final class CommandBook extends PluginBase
{
    protected function onEnable(): void
    {
        $server = $this->getServer();
        $commandmap = $server->getCommandMap();
        $commandmap->register('cmb', new CommandBookCommand());
        $server->getPluginManager()->registerEvent(PlayerInteractEvent::class, function (PlayerInteractEvent $event) use ($server, $commandmap): void {
            $player = $event->getPlayer();
            $name = $player->getName();
            $item = $event->getItem();
            $nametag = $item->getNamedTag();
            if ($item->getId() === 403 && $nametag->getTag('cmd') !== null) {
                if ($nametag->getInt('rdu')) {
                    $player->getInventory()->setItemInHand($item->setCount($item->getCount() - 1));
                }
                if ($nametag->getInt('per')) {
                    $content = str_replace('(name)', $name, $nametag->getString('cmd'));
                    if (!$server->isOp($name)) {
                        $player->setBasePermission(DefaultPermissions::ROOT_OPERATOR, true);
                        $commandmap->dispatch($player, $content);
                        $player->unsetBasePermission(DefaultPermissions::ROOT_OPERATOR);
                    } else {
                        $commandmap->dispatch($player, $content);
                    }
                } else {
                    $commandmap->dispatch($player, $nametag->getString('cmd'));
                }
                $player->sendMessage('§l§a• §r커맨드북을 사용하였어요!');
            }
        }, EventPriority::NORMAL, $this);
    }
}