<?php

declare(strict_types=1);

namespace Shared\Domain\Criteria;

use Shared\Domain\Collections\Collection;

use function Lambdish\Phunctional\reduce;

final class Filters extends Collection
{
    public static function fromValues(array $values): self
    {
        return new self(array_map(self::filterBuilder(), $values));
    }

    private static function filterBuilder(): callable
    {
        return static fn (array $values): Filter => Filter::fromValues($values);
    }

    public function add(Filter $filter): self
    {
        return new self(array_merge($this->items(), [$filter]));
    }

    public function filters(): array
    {
        return $this->items();
    }

    public function serialize(): string
    {
        return reduce(
            static fn (string $accumulate, Filter $filter): string => sprintf(
                '%s^%s',
                $accumulate,
                $filter->serialize()
            ),
            $this->items(),
            ''
        );
    }

    protected function type(): string
    {
        return Filter::class;
    }
}
