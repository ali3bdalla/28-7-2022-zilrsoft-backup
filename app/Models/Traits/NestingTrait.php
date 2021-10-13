<?php

namespace App\Models\Traits;

use App\Models\HashMap;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait NestingTrait
{

    public function scopeLastChildren($query)
    {
        return $query->has('children', '=', 0);
    }

    public function getParentsIncludeMe(): array
    {
        $result = $this->getParentsHashMap();
        $parents = [];
        foreach ($result as $value) {
            $parents[] = $value;
        }
        $parents[] = $this->id;

        return $parents;
    }

    public function getParentsHashMap()
    {
        return $this->getHashMap()['parents'];
    }

    public function getHashMap(): array
    {
        $hashMap = $this->load('hashMap')->hashMap;
        if ($hashMap === null) {
            $query = $this->createHashMap();

            $result = [
                'children' => $query->children == null || (count($query->children) == 1 && $query->children[0] == 0) ? [] : $query->children,
                'parents' => $query->parents == null || (count($query->parents) == 1 && $query->parents[0] == 0) ? [] : $query->parents,
            ];
        } else {

            $result = [

                'children' => $hashMap->children == null ? [] : collect($hashMap->children)->toArray(),
                'parents' => $hashMap->parents == null ? [] : collect($hashMap->parents)->toArray(),
            ];
        }
        return $result;
    }

    public function createHashMap(): Model
    {
        $result = $this->_getParentsAndChildren();

        return $this->hashMap()->create(
            [
                'children' => $result['children'] === [] ? null : implode(',', $result['children']),
                'parents' => $result['parents'] === [] ? null : implode(',', $result['parents']),
            ]
        );
    }

    public function _getParentsAndChildren(): array
    {
        return [
            'children' => $this->_getChildrenList(),
            'parents' => $this->_getParentsList(),
        ];
    }

    public function _getChildrenList(): array
    {
        $list = [];

        if ($this->children()->withTrashed()->count() >= 1) {
            foreach ($this->children()->withTrashed()->get() as $child) {
                $list[] = $child->id;
                foreach ($child->_getChildrenList() as $id) {
                    $list[] = $id;
                }
            }

        }
        return $list;
    }

    public function _getParentsList(): array
    {
        $list = [];

        if ($this->parent_id !== null) {
            $list[] = $this->parent_id;
        }

        if ($this->parent) {
            foreach (($this->parent()->withTrashed()->first())->_getParentsList() as $id) {
                $list[] = $id;
            }
        }

        return $list;
    }

    public function hashMap(): MorphOne
    {
        return $this->morphOne(HashMap::class, 'mappable');
    }

    public function getChildrenIncludeMe(): array
    {
        $result = $this->getChildrenHashMap();
        $children = [];
        foreach ($result as $value) {
            if ($value != null && $value != "")
                $children[] = (int)$value;
        }
        $children[] = $this->id;

        return $children;
    }

    public function getChildrenHashMap()
    {
        return $this->getHashMap()['children'];
    }

    public function updateHashMap()
    {

        if ($this->parent != null) {
            $this->parent->updateHashMap();
        }

        if ($this->hashMap == null) {
            return $this->createHashMap();
        }
        $result = $this->_getParentsAndChildren();

        return $this->hashMap()->update(
            [

                'children' => $result['children'] === [] ? null : implode(',', $result['children']),
                'parents' => $result['parents'] === [] ? null : implode(',', $result['parents']),
            ]
        );
    }

}
