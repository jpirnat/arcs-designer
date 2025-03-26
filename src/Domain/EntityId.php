<?php
declare(strict_types=1);

namespace Jp\ArcsDesigner\Domain;

use Exception;

abstract class EntityId
{
    protected int $_value = 0;

    public bool $isNew {
        get => $this->_value === 0;
    }

    public function __construct(
        protected(set) int $value = 0 {
            set(int $v) {
                $this->_value = $v;
            }
            get {
                if ($this->isNew) {
                    throw new Exception('Id has not been set.');
                }

                return $this->_value;
            }
        },
    ) {}

    /**
     * Set the id.
     *
     * @throws Exception if the id is already set, or is being set to 0.
     */
    public function set(int $value): void
    {
        if (!$this->isNew) {
            throw new Exception('Set id cannot be reset.');
        }

        if ($value === 0) {
            throw new Exception('Id cannot be set to 0.');
        }

        $this->_value = $value;
    }
}
