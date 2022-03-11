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
        $this->getServer()->getCommandMap()->register('cmb', new CommandBookCommand());
        $this->getServer()->getPluginManager()->registerEvent(PlayerInteractEvent::class, function (PlayerInteractEvent $event):void
        {
            $player = $event->getPlayer();
            $item = $event->getItem();
            $nametag = $item->getNamedTag();
            if($item->getId() === 403 && $nametag->getString('cmd') !== null)
            {
                if($nametag->getInt('rdu'))
                {
                    $player->getInventory()->setItemInHand($item->setCount($item->getCount() - 1));
                }
                if($nametag->getInt('per'))
                {
                    $content = str_replace('(name)', $player->getName(), $nametag->getString('cmd'));
                    if (!$this->getServer()->isOp($player->getName())) {
                        $player->setBasePermission(DefaultPermissions::ROOT_OPERATOR, true);
                        $this->getServer()->getCommandMap()->dispatch($player, $content);
                        $player->unsetBasePermission(DefaultPermissions::ROOT_OPERATOR);
                    }else{
                        $this->getServer()->getCommandMap()->dispatch($player, $content);
                    }
                }else{
                    $this->getServer()->getCommandMap()->dispatch($player, $nametag->getString('cmd'));
                }
                $player->sendMessage('§l§a• §r커맨드북을 사용하였어요!');
            }
        }, EventPriority::NORMAL, $this);
    }
}