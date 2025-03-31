<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Infrastructure;

use Jp\ArcsDesigner\Domain\Cards\Card;
use Jp\ArcsDesigner\Domain\Cards\CardId;
use Jp\ArcsDesigner\Domain\Cards\CardNotFoundException;
use Jp\ArcsDesigner\Domain\Cards\CardRepositoryInterface;
use Jp\ArcsDesigner\Domain\Cards\Status;
use PDO;

final readonly class DatabaseCardRepository implements CardRepositoryInterface
{
    public function __construct(
        private PDO $db,
    ) {}

    /**
     * @inheritDoc
     */
    public function getById(CardId $cardId): Card
    {
        $stmt = $this->db->prepare(
            'SELECT
                `status`
            FROM `cards`
            WHERE id = :card_id
            LIMIT 1'
        );
        $stmt->bindValue('card_id', $cardId->value, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new CardNotFoundException("No card exists with id $cardId->value.");
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        return new Card(
            $cardId,
            new Status($result['status']),
        );
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        $stmt = $this->db->prepare(
            'SELECT
                `id`,
                `status`
            FROM `cards`'
        );
        $stmt->execute();

        $cards = [];

        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $card = new Card(
                new CardId($result['id']),
                new Status($result['status']),
            );

            $cards[$result['id']] = $card;
        }

        return $cards;
    }

    public function save(Card $card): void
    {
        if ($card->id->isNew) {
            $this->create($card);
        } else {
            $this->update($card);
        }
    }

    private function create(Card $card): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO `cards` (
                `status`
            ) VALUES (
                :status
            )'
        );
        $stmt->bindValue(':status', $card->status->value);
        $stmt->execute();

        /** @noinspection PhpUnhandledExceptionInspection */
        $card->id->set(
            (int) $this->db->lastInsertId()
        );
    }

    private function update(Card $card): void
    {
        $stmt = $this->db->prepare(
            'UPDATE `cards` SET
                `status` = :status
            WHERE `id` = :card_id'
        );
        $stmt->bindValue(':status', $card->status->value);
        $stmt->bindValue(':card_id', $card->id, PDO::PARAM_INT);
        $stmt->execute();
    }
}