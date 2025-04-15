<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Infrastructure;

use Jp\ArcsDesigner\Domain\Cards\CardId;
use Jp\ArcsDesigner\Domain\SetCards\SetCard;
use Jp\ArcsDesigner\Domain\SetCards\SetCardRepositoryInterface;
use Jp\ArcsDesigner\Domain\Sets\SetId;
use PDO;

final readonly class DatabaseSetCardRepository implements SetCardRepositoryInterface
{
    public function __construct(
        private PDO $db,
    ) {}

    /**
     * @inheritDoc
     */
    public function getByCard(CardId $cardId): array
    {
        $stmt = $this->db->prepare(
            'SELECT
                `set_id`
            FROM `set_cards`
            WHERE `card_id` = :card_id'
        );
        $stmt->bindValue(':card_id', $cardId->value, PDO::PARAM_INT);
        $stmt->execute();

        $setCards = [];

        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $setCard = new SetCard(
                new SetId($result['set_id']),
                $cardId,
            );

            $setCards[] = $setCard;
        }

        return $setCards;
    }

    public function save(SetCard $setCard): void
    {
        if (!$this->exists($setCard)) {
            $this->create($setCard);
        }
    }

    private function exists(SetCard $setCard): bool
    {
        $stmt = $this->db->prepare(
            'SELECT
                1
            FROM `set_cards`
            WHERE `set_id` = :set_id
                AND `card_id` = :card_id
            LIMIT 1'
        );
        $stmt->bindValue(':set_id', $setCard->setId->value, PDO::PARAM_INT);
        $stmt->bindValue(':card_id', $setCard->cardId->value, PDO::PARAM_INT);
        $stmt->execute();
        return (bool) $stmt->fetchColumn();
    }

    private function create(SetCard $setCard) : void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO `set_cards` (
                `set_id`,
                `card_id`
            ) VALUES (
                :set_id,
                :card_id
            )'
        );
        $stmt->bindValue(':set_id', $setCard->setId->value, PDO::PARAM_INT);
        $stmt->bindValue(':card_id', $setCard->cardId->value, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function deleteByCard(CardId $cardId): void
    {
        $stmt = $this->db->prepare(
            'DELETE
            FROM `set_cards`
            WHERE `card_id` = :card_id'
        );
        $stmt->bindValue(':card_id', $cardId->value, PDO::PARAM_INT);
        $stmt->execute();
    }
}
