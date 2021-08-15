<?php

namespace App\Service;

class Paginator
{
    public const PAGE_SIZE = 25;

    private $currentPage;
    private $limit;
    private $offset;
    private $results;
    private $totalResults;
    private $count;

    public function __construct(int $page, int $limit = self::PAGE_SIZE)
    {
        $this->limit = $limit;
        $this->currentPage = max(1, $page);
        $this->offset = ($this->currentPage - 1) * $this->limit;
    }

    public function init($apiDatas): self
    {
        $this->results = $apiDatas['data']['results'];
        $this->totalResults = $apiDatas['data']['total'];
        $this->count = $apiDatas['data']['count'];

        return $this;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function getLastPage(): int
    {
        return (int) ceil($this->totalResults / $this->limit);
    }

    public function hasPreviousPage(): bool
    {
        return $this->currentPage > 1;
    }

    public function getPreviousPage(): int
    {
        return max(1, $this->currentPage - 1);
    }

    public function hasNextPage(): bool
    {
        return $this->currentPage < $this->getLastPage();
    }

    public function getNextPage(): int
    {
        return min($this->getLastPage(), $this->currentPage + 1);
    }

    public function hasToPaginate(): bool
    {
        return $this->totalResults > $this->limit;
    }

    public function getTotalResults(): int
    {
        return $this->totalResults;
    }

    public function getResults() : array
    {
        return $this->results;
    }

    public function getCount()
    {
        return $this->count;
    }
}
