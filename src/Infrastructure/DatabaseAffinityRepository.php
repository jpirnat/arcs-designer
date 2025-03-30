<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Infrastructure;

use Jp\ArcsDesigner\Domain\Affinities\Affinity;
use Jp\ArcsDesigner\Domain\Affinities\AffinityId;
use Jp\ArcsDesigner\Domain\Affinities\AffinityRepositoryInterface;
use PDO;

final readonly class DatabaseAffinityRepository implements AffinityRepositoryInterface
{
    public function __construct(
        private PDO $db,
    ) {}

    public function getByAspects(
        bool $purple,
        bool $red,
        bool $orange,
        bool $yellow,
        bool $green,
        bool $blue,
    ): Affinity {
        $stmt = $this->db->prepare(
            'SELECT
                `id`,
                `name`
            FROM `affinities`
            WHERE `purple` = :purple
                AND `red` = :red
                AND `orange` = :orange
                AND `yellow` = :yellow
                AND `green` = :green
                AND `blue` = :blue
            LIMIT 1'
        );
        $stmt->bindValue(':purple', $purple, PDO::PARAM_BOOL);
        $stmt->bindValue(':red', $red, PDO::PARAM_BOOL);
        $stmt->bindValue(':orange', $orange, PDO::PARAM_BOOL);
        $stmt->bindValue(':yellow', $yellow, PDO::PARAM_BOOL);
        $stmt->bindValue(':green', $green, PDO::PARAM_BOOL);
        $stmt->bindValue(':blue', $blue, PDO::PARAM_BOOL);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return new Affinity(
            new AffinityId($result['id']),
            $result['name'],
            $purple,
            $red,
            $orange,
            $yellow,
            $green,
            $blue,
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
                `name`,
                `purple`,
                `red`,
                `orange`,
                `yellow`,
                `green`,
                `blue`
            FROM `affinities`'
        );
        $stmt->execute();

        $affinities = [];

        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $affinity = new Affinity(
                new AffinityId($result['id']),
                $result['name'],
                (bool) $result['purple'],
                (bool) $result['red'],
                (bool) $result['orange'],
                (bool) $result['yellow'],
                (bool) $result['green'],
                (bool) $result['blue'],
            );

            $affinities[$result['id']] = $affinity;
        }

        return $affinities;
    }
}
