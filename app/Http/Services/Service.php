<?php

namespace App\Http\Services;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Service
{
    protected $repository;

    /**
     * @param array $params
     * @return mixed
     */
    public function get(array $params)
    {
        return $this->repository->get($params);
    }

    /**
     * @param  string|null  $sortBy
     * @return mixed
     */
    public function all(string $sortBy = NULL)
    {
        return $this->repository->all($sortBy);
    }

    /**
     * @param  int  $number
     * @return Collection
     */
    public function latest(int $number): Collection
    {
        return $this->repository->all()->sortByDesc('created_at')->take($number);
    }

    /**
     * @param  string  $id
     * @return mixed
     */
    public function find(string $id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param  string  $id
     * @param $with
     * @return mixed
     */
    public function findWith(string $id, $with)
    {
        return $this->repository->findWith($id, $with);
    }

    /**
     * @param  string  $attr
     * @param  string  $value
     * @param  array  $with
     * @return mixed
     */
    public function findByAttr(string $attr, string $value, array $with = [])
    {
        return $this->repository->whereWith($attr, '=', $value, $with)->toArray();
    }

    /**
     * @param  string  $attr
     * @param  string  $op
     * @param $value
     * @return mixed
     */
    public function where(string $attr, string $op, $value)
    {
        return $this->repository->where($attr, $op, $value);
    }

    /**
     * @param  array  $attributes
     * @return mixed
     * @throws Exception
     */
    public function create(array $attributes)
    {
        $attributes['instituicao_id'] = Auth::user()->instituicao_id;

        return $this->repository->create($attributes);
    }

    /**
     * @param  string  $id
     * @param  array  $attributes
     * @return mixed
     */
    public function update(string $id, array $attributes)
    {
        return $this->repository->update($id, $attributes);
    }

    /**
     * @return mixed
     */
    public function active()
    {
        return $this->repository->where('active', '=', true);
    }

    /**
     * @param $object
     * @param  bool  $active
     * @return bool
     */
    public function activeObject($object, bool $active) : bool
    {
        return $this->repository->activeObject($object, $active);
    }

    /**
     * @param $object
     * @param  bool  $block
     * @return bool
     */
    public function blockObject($object, bool $block) : bool
    {
        return $this->repository->blockObject($object, $block);
    }

    /**
     * @param $object
     * @return bool
     */
    public function remove($object): bool
    {
        return $this->repository->remove($object);
    }
}
