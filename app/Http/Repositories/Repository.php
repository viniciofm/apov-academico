<?php

namespace App\Http\Repositories;


use App\Scopes\ActivedScope;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class Repository
{
    protected $entity;

    public function get(array $params)
    {
        return $this->searchWithPagination(
            $params['with'],
            $params['page'],
            $params['perPage'],
            $params['column'],
            $params['search'],
            $params['paginate']
        );
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->entity::all();
    }

    /**
     * @param  string  $id
     * @return mixed
     */
    public function find(string $id)
    {
        return $this->entity::findOrFail($id);
    }

    /**
     * @param  string  $id
     * @param $with
     * @return mixed
     */
    public function findWith(string $id, $with)
    {
        return $this->entity::with($with)->find($id);
    }

    /**
     * @param  string  $attr
     * @param  string  $op
     * @param $value
     * @return mixed
     */
    public function where(string $attr, string $op, $value)
    {
        return $this->entity::where($attr, $op, $value)->get();
    }

    /**
     * @param  string  $attr
     * @param  string  $op
     * @param $value
     * @param $with
     * @return mixed
     */
    public function whereWith(string $attr, string $op, $value, $with)
    {
        return $this->entity::with($with)->where($attr, $op, $value)->first();
    }

    /**
     * @param $callback
     * @param $with
     * @return Collection
     */
    public function whereFunc($callback, $with = []): Collection
    {
        return $this->entity::with($with)->where($callback)->get();
    }

    /**
     * @param  array  $attributes
     * @return mixed
     * @throws Exception
     */
    public function create(array $attributes)
    {
        try {
            $object = new $this->entity($attributes);
            $object->save();
            return $object;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param  string  $id
     * @param  array  $attributes
     * @return mixed
     */
    public function update(string $id, array $attributes)
    {
        $object = $this->entity::findOrFail($id);
        $object->update($attributes);

        return $object;
    }

    /**
     * @param $object
     * @return bool
     */
    public function remove($object): bool
    {
        $item = $this->entity::findOrFail($object->id);
        return $item->delete();
    }

    private function searchWithPagination(
        array $with = [],
        $page = 1,
        $perPage = 10,
        $column = null,
        $search = '',
        bool $paginate = false,
        array $columns = ['*']
    )
    {
        $query = $this->entity->withoutGlobalScope(ActivedScope::class)->with($with)->orderBy('created_at', 'DESC');
        if ($search && $column)
        {
            $query->where($column, 'ilike', '%'.$search.'%');
        }

        if ($paginate) {
            return $query->paginate(
                $perPage,
                ['*'],
                'page',
                $page
            );
        }

        return $query->get($columns);
    }
}
