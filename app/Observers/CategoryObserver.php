<?php

namespace App\Observers;

use App\Models\Category;

class CategoryObserver
{
    /**
     * Handle the organization "created" event.
     *
     * @param Category $category
     * @return void
     */
    public function created(Category $category)
    {

        if ($category->parent) {
            $category->parent->updateHashMap();
        }
        $category->updateHashMap();
    }

    /**
     * Handle the organization "updated" event.
     *
     * @param \App\Observers\Category $category
     * @return void
     */
    public function updated(Category $category)
    {
        if ($category->parent) {
            $category->parent->updateHashMap();
        }

        $category->updateHashMap();
    }

    /**
     * Handle the organization "deleted" event.
     *
     * @param \App\Observers\Category $category
     * @return void
     */
    public function deleted(Category $category)
    {
        if ($category->parent) {
            $category->parent->updateHashMap();
        }

    }

    /**
     * Handle the organization "restored" event.
     *
     * @param \App\Observers\Category $category
     * @return void
     */
    public function restored(Category $category)
    {

    }

    /**
     * Handle the organization "force deleted" event.
     *
     * @param Category $category
     * @return void
     */
    public function forceDeleted(Category $category)
    {

    }
}
