<?php

namespace App\Repositories;


use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository extends Repository
{


    public function model(): string
    {
        return Category::class;
    }


    /**
     * @return Collection
     */
    public function parents(): Collection
    {
        return $this->model()::whereNull('parent_id')->get();
    }

    /**
     * @return Collection
     */
    public function children(): Collection
    {
        return $this->model()::whereNotNull('parent_id')->get();
    }

    /**
     * @param array $data
     * @param int $id
     */
    public function update(int $id, array $data): void
    {
        $category = $this->getById($id);
        if ($category->children()->exists() && $data['parent_id']) {
            $category->children()->update(['parent_id' => null]);
        }
        $category->update($data);
    }


    /**
     * @param $id
     */
    public function destroy($id): void
    {
        $category = $this->getById($id);
        if ($category->children()->exists()) {
            $category->children()->update(['parent_id' => null]);
        }
        $category->delete();
    }
}
