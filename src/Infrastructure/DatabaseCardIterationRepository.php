<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Infrastructure;

use DateTimeImmutable;
use Jp\ArcsDesigner\Domain\Affinities\AffinityId;
use Jp\ArcsDesigner\Domain\CardIterations\CardIteration;
use Jp\ArcsDesigner\Domain\CardIterations\CardIterationId;
use Jp\ArcsDesigner\Domain\CardIterations\CardIterationNotFoundException;
use Jp\ArcsDesigner\Domain\CardIterations\CardIterationRepositoryInterface;
use Jp\ArcsDesigner\Domain\CardIterations\CardType;
use Jp\ArcsDesigner\Domain\CardIterations\SpeedModifier;
use Jp\ArcsDesigner\Domain\CardIterations\ZoneModifier;
use Jp\ArcsDesigner\Domain\Cards\CardId;
use Jp\ArcsDesigner\Domain\Users\UserId;
use PDO;

final readonly class DatabaseCardIterationRepository implements CardIterationRepositoryInterface
{
    public function __construct(
        private PDO $db,
    ) {}

    /**
     * @inheritDoc
     */
    public function getById(CardIterationId $iterationId): CardIteration
    {
        $stmt = $this->db->prepare(
            'SELECT
                `card_id`,
                `name`,
                `affinity_id`,
                `cost`,
                `enflowable`,
                `speed_modifier`,
                `zone_modifier`,
                `starting_life`,
                `card_type`,
                `rules_text`,
                `power`,
                `health`,
                `created_by`,
                `created_at`
            FROM `card_iterations`
            WHERE `id` = :iteration_id
            LIMIT 1'
        );
        $stmt->bindValue(':iteration_id', $iterationId->value, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new CardIterationNotFoundException("No card iteration exists with id $iterationId->value.");
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        return new CardIteration(
            $iterationId,
            new CardId($result['card_id']),
            $result['name'],
            $result['affinity_id'] !== null
                ? new AffinityId($result['affinity_id'])
                : null,
            $result['cost'],
            $result['enflowable'] !== null
                ? (bool) $result['enflowable']
                : null,
            $result['speed_modifier'] !== null
                ? new SpeedModifier($result['speed_modifier'])
                : null,
            $result['zone_modifier'] !== null
                ? new ZoneModifier($result['zone_modifier'])
                : null,
            $result['starting_life'],
            $result['card_type'] !== null
                ? new CardType($result['card_type'])
                : null,
            $result['rules_text'],
            $result['power'],
            $result['health'],
            new UserId($result['created_by']),
            new DateTimeImmutable($result['created_at']),
        );
    }

    /**
     * @inheritDoc
     */
    public function getByCard(CardId $cardId): array
    {
        $stmt = $this->db->prepare(
            'SELECT
                `id`,
                `name`,
                `affinity_id`,
                `cost`,
                `enflowable`,
                `speed_modifier`,
                `zone_modifier`,
                `starting_life`,
                `card_type`,
                `rules_text`,
                `power`,
                `health`,
                `created_by`,
                `created_at`
            FROM `card_iterations`
            WHERE `card_id` = :card_id
            ORDER BY `created_at` DESC'
        );
        $stmt->bindValue(':card_id', $cardId->value, PDO::PARAM_INT);
        $stmt->execute();

        $iterations = [];

        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $iteration = new CardIteration(
                new CardIterationId($result['id']),
                $cardId,
                $result['name'],
                $result['affinity_id'] !== null
                    ? new AffinityId($result['affinity_id'])
                    : null,
                $result['cost'],
                $result['enflowable'] !== null
                    ? (bool) $result['enflowable']
                    : null,
                $result['speed_modifier'] !== null
                    ? new SpeedModifier($result['speed_modifier'])
                    : null,
                $result['zone_modifier'] !== null
                    ? new ZoneModifier($result['zone_modifier'])
                    : null,
                $result['starting_life'],
                $result['card_type'] !== null
                    ? new CardType($result['card_type'])
                    : null,
                $result['rules_text'],
                $result['power'],
                $result['health'],
                new UserId($result['created_by']),
                new DateTimeImmutable($result['created_at']),
            );

            $iterations[$result['id']] = $iteration;
        }

        return $iterations;
    }

    public function save(CardIteration $iteration): void
    {
        if ($iteration->id->isNew) {
            $this->create($iteration);
        }
    }

    private function create(CardIteration $iteration): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO `card_iterations` (
                `card_id`,
                `name`,
                `affinity_id`,
                `cost`,
                `enflowable`,
                `speed_modifier`,
                `zone_modifier`,
                `starting_life`,
                `card_type`,
                `rules_text`,
                `power`,
                `health`,
                `created_by`
            ) VALUES (
                :card_id,
                :name,
                :affinity_id,
                :cost,
                :enflowable,
                :speed_modifier,
                :zone_modifier,
                :starting_life,
                :card_type,
                :rules_text,
                :power,
                :health,
                :created_by
            )'
        );
        $stmt->bindValue(':card_id', $iteration->cardId->value);
        $stmt->bindValue(':name', $iteration->name);
        $stmt->bindValue(':affinity_id', $iteration->affinityId?->value, PDO::PARAM_INT);
        $stmt->bindValue(':cost', $iteration->cost, PDO::PARAM_INT);
        $stmt->bindValue(':enflowable', $iteration->enflowable, PDO::PARAM_BOOL);
        $stmt->bindValue(':speed_modifier', $iteration->speedModifier?->value);
        $stmt->bindValue(':zone_modifier', $iteration->zoneModifier?->value);
        $stmt->bindValue(':starting_life', $iteration->startingLife, PDO::PARAM_INT);
        $stmt->bindValue(':card_type', $iteration->cardType?->value);
        $stmt->bindValue(':rules_text', $iteration->rulesText);
        $stmt->bindValue(':power', $iteration->power, PDO::PARAM_INT);
        $stmt->bindValue(':health', $iteration->health, PDO::PARAM_INT);
        $stmt->bindValue(':created_by', $iteration->createdBy->value, PDO::PARAM_INT);
        $stmt->execute();

        /** @noinspection PhpUnhandledExceptionInspection */
        $iteration->id->set(
            (int) $this->db->lastInsertId()
        );

        $this->setCurrent(
            $iteration->cardId,
            $iteration->id,
        );
    }

    public function setCurrent(CardId $cardId, CardIterationId $iterationId): void
    {
        if (!$this->existsCurrent($cardId)) {
            $this->createCurrent($cardId, $iterationId);
        } else {
            $this->updateCurrent($cardId, $iterationId);
        }
    }

    private function existsCurrent(CardId $cardId): bool
    {
        $stmt = $this->db->prepare(
            'SELECT
                1
            FROM `current_iterations`
            WHERE `card_id` = :card_id'
        );
        $stmt->bindValue(':card_id', $cardId->value, PDO::PARAM_INT);
        $stmt->execute();
        return (bool) $stmt->fetchColumn();
    }

    private function createCurrent(CardId $cardId, CardIterationId $iterationId): void
    {
        $stmt = $this->db->prepare(
        'INSERT INTO `current_iterations` (
                `card_id`,
                `iteration_id`
            ) VALUES (
                :card_id,
                :iteration_id
            )'
        );
        $stmt->bindValue(':card_id', $cardId->value, PDO::PARAM_INT);
        $stmt->bindValue(':iteration_id', $iterationId->value, PDO::PARAM_INT);
        $stmt->execute();
    }

    private function updateCurrent(CardId $cardId, CardIterationId $iterationId): void
    {
        $stmt = $this->db->prepare(
            'UPDATE `current_iterations` SET
                `iteration_id` = :iteration_id
            WHERE `card_id` = :card_id'
        );
        $stmt->bindValue(':iteration_id', $iterationId->value, PDO::PARAM_INT);
        $stmt->bindValue(':card_id', $cardId->value, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getCurrent(CardId $cardId): CardIteration
    {
        $stmt = $this->db->prepare(
            'SELECT
                `iteration_id`
            FROM `current_iterations`
            WHERE `card_id` = :card_id
            LIMIT 1'
        );
        $stmt->bindValue(':card_id', $cardId->value, PDO::PARAM_INT);
        $stmt->execute();
        $iterationId = $stmt->fetchColumn();

        $iterationId = new CardIterationId($iterationId);

        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->getById($iterationId);
    }
}
