<?php

namespace App\Classes\Tag\Unit;

use Illuminate\Database\Eloquent\Collection;

class Tags
{
    private Collection $tags;

    public function __construct()
    {
        $this->tags = new Collection();
    }

    public function add(Tag $tag): void
    {
        $group = $tag->getUniqueGroup();
        if ($this->tags->has($group)) {
            $this->tags->put($group, $tag);
        }
    }

    public function have(Tag $tag): bool
    {
        $group = $tag->getUniqueGroup();
        if ($this->tags->has($group)) {
            $foundTag = $this->tags->get($group);
            return $foundTag == $tag;
        }
        return false;
    }
}