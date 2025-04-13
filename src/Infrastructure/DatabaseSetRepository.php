<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Infrastructure;

use Jp\ArcsDesigner\Domain\Sets\Set;
use Jp\ArcsDesigner\Domain\Sets\SetId;
use Jp\ArcsDesigner\Domain\Sets\SetNotFoundException;
use Jp\ArcsDesigner\Domain\Sets\SetRepositoryInterface;
use PDO;

final readonly class DatabaseSetRepository implements SetRepositoryInterface
{
    public function __construct(
        private PDO $db,
    ) {}

    /**
     * @inheritDoc
     */
    public function getById(SetId $setId): Set
    {
        $stmt = $this->db->prepare(
            'SELECT
                `name`
            FROM `sets`
            WHERE `id` = :set_id
            LIMIT 1'
        );
        $stmt->bindValue(':set_id', $setId->value, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new SetNotFoundException("No set exists with id $setId->value.");
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        return new Set(
            $setId,
            $result['name'],
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
                `name`
            FROM `sets`'
        );
        $stmt->execute();

        $sets = [];

        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $set = new Set(
                new SetId($result['id']),
                $result['name'],
            );

            $sets[$result['id']] = $set;
        }

        return $sets;
    }

    public function save(Set $set): void
    {
        if ($set->id->isNew) {
            $this->create($set);
        } else {
            $this->update($set);
        }
    }

    private function create(Set $set): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO `sets` (
                `name`
            ) VALUES (
                :name
            )'
        );
        $stmt->bindValue(':name', $set->name);
        $stmt->execute();

        /** @noinspection PhpUnhandledExceptionInspection */
        $set->id->set(
            (int) $this->db->lastInsertId()
        );
    }

    private function update(Set $set): void
    {
        $stmt = $this->db->prepare(
            'UPDATE `sets` SET
                `name` = :name
            WHERE `id` = :set_id'
        );
        $stmt->bindValue(':name', $set->name);
        $stmt->bindValue(':set_id', $set->id->value, PDO::PARAM_INT);
        $stmt->execute();
    }
}
