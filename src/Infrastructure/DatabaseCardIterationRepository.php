<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Infrastructure;

use Jp\ArcsDesigner\Domain\Affinities\AffinityId;
use Jp\ArcsDesigner\Domain\CardIterations\CardIteration;
use Jp\ArcsDesigner\Domain\CardIterations\CardIterationId;
use Jp\ArcsDesigner\Domain\CardIterations\CardIterationNotFoundException;
use Jp\ArcsDesigner\Domain\CardIterations\CardIterationRepositoryInterface;
use Jp\ArcsDesigner\Domain\CardIterations\CardType;
use Jp\ArcsDesigner\Domain\CardIterations\SpeedModifier;
use Jp\ArcsDesigner\Domain\CardIterations\ZoneModifier;
use Jp\ArcsDesigner\Domain\Cards\CardId;
use PDO;

final readonly class DatabaseCardIterationRepository implements CardIterationRepositoryInterface
{
    public function __construct(
        private PDO $db,
    ) {}

    /**
     * @throws CardIterationNotFoundException if no card iteration exists with this id.
     * @noinspection PhpDocMissingThrowsInspection
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
	            `burden`,
	            `card_type`,
	            `rules_text`,
	            `attack`,
	            `defense`
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
            (bool) $result['enflowable'],
            new SpeedModifier($result['speed_modifier']),
            new ZoneModifier($result['zone_modifier']),
            $result['starting_life'],
            $result['burden'],
            new CardType($result['card_type']),
            $result['rules_text'],
            $result['attack'],
            $result['defense'],
        );
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
                `burden`,
                `card_type`,
                `rules_text`,
                `attack`,
                `defense`
            ) VALUES (
                :card_id,
                :name,
                :affinity_id,
                :cost,
                :enflowable,
                :speed_modifier,
                :zone_modifier,
                :starting_life,
                :burden,
                :card_type,
                :rules_text,
                :attack,
                :defense
            )'
        );
        $stmt->bindValue(':card_id', $iteration->cardId->value);
        $stmt->bindValue(':name', $iteration->name);
        $stmt->bindValue(':affinity_id', $iteration->affinityId?->value, PDO::PARAM_INT);
        $stmt->bindValue(':cost', $iteration->cost, PDO::PARAM_INT);
        $stmt->bindValue(':enflowable', $iteration->enflowable, PDO::PARAM_BOOL);
        $stmt->bindValue(':speed_modifier', $iteration->speedModifier->value);
        $stmt->bindValue(':zone_modifier', $iteration->zoneModifier->value);
        $stmt->bindValue(':starting_life', $iteration->startingLife, PDO::PARAM_INT);
        $stmt->bindValue(':burden', $iteration->burden, PDO::PARAM_INT);
        $stmt->bindValue(':card_type', $iteration->cardType->value);
        $stmt->bindValue(':rules_text', $iteration->rulesText);
        $stmt->bindValue(':attack', $iteration->attack, PDO::PARAM_INT);
        $stmt->bindValue(':defense', $iteration->defense, PDO::PARAM_INT);
        $stmt->execute();

        /** @noinspection PhpUnhandledExceptionInspection */
        $iteration->id->set(
            (int) $this->db->lastInsertId()
        );
    }
}