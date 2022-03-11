<?php
declare(strict_types=1);

namespace Flugins\CommandBook\Form;

use pocketmine\form\Form;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use function trim;

final class CommandBookForm implements Form
{
    public function jsonSerialize(): array
    {
        $prefix = '§r§l§a▶ §r§f';
        return [
            'type' => 'custom_form',
            'title' => '§lCOMMAND BOOK',
            'content' => [
                [
                    'type' => 'label',
                    'text' => $prefix . "클릭한 플레이어 이름 : (name)\n$prefix( / ) 빼고 입력하세요!"
                ],
                [
                    'type' => 'label',
                    'text' => $prefix . '아이템이 소모되게 하시겠습니까?'
                ],
                [
                    'type' => 'toggle',
                    'text' => '',
                    'default' => true,
                ],
                [
                    'type' => 'label',
                    'text' => $prefix . '명령어를 관리자 권한으로 실행하시겠습니까?'
                ],
                [
                    'type' => 'toggle',
                    'text' => '',
                    'default' => false,
                ],
                [
                    'type' => 'input',
                    'text' => $prefix . '명령어를 입력해주세요!',
                    'placeholder' => 'ex) 워프 스폰'
                ]
            ]
        ];
    }

    public function handleResponse(Player $player, $data): void
    {
        if ($data === null) return;
        if (trim($data[5]) === '' || $data[5] === null) {
            $player->sendMessage('§l§a• §r빈 칸을 정확히 입력해주세요!');
            return;
        }
        $com = CompoundTag::create()
            ->setInt('rdu', $data[2] ? 1 : 0)
            ->setInt('per', $data[4] ? 1 : 0)
            ->setString('cmd', $data[5]);
        $data1 = $data[2] ? 'O' : 'X';
        $data2 = $data[4] ? 'OP' : 'USER';
        $item = ItemFactory::getInstance()->get(ItemIds::ENCHANTED_BOOK, 0, 1, $com);
        $item->setCustomName('§l§fCOMMAND BOOK');
        $item->setLore(["소모여부 : $data1", "권한 : $data2", '명령어 : ' . $data[5]]);
        $player->getInventory()->addItem($item);
    }
}