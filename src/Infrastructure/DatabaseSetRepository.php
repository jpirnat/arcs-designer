<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Infrastructure;

use Jp\ArcsDesigner\Domain\Sets\Set;
use Jp\ArcsDesigner\Domain\Sets\SetRepositoryInterface;
use PDO;

final readonly class DatabaseSetRepository implements SetRepositoryInterface
{
    public function __construct(
        private PDO $db,
    ) {}

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
