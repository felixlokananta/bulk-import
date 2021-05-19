<?php

namespace App\Models\Repositories;

abstract class BaseRepository
{
    protected $model;
    protected $pageSizeConfig = 'main.core_page_default';

    /**
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes)
    {
        $model = $this->model->create($attributes);
        return $model;
    }

    /**
     * @param id
     * @return bool
     */
    public function delete($id): bool
    {
        if (!is_int($id) && !is_array($id)) {
            throw new \Exception("Unexpected ID ({$id}) given");
        }
        $this->model->destroy($id);
        return true;
    }

    /**
     * @param mixed $value
     * @param string $column
     * @param bool $paginate - If returning a collection, have the builder paginate?
     * @param bool $withTrashed
     */
    public function find($value, ?string $column = 'id', bool $paginate = true, bool $withTrashed = false)
    {
        if (is_array($value)) {
            $builder = $this->model->where($value);
        } else {
            $builder = $this->model->where($column, $value);
        }
        if ($withTrashed) {
            $builder = $builder->withTrashed();
        }

        if ($paginate) {
            return $builder->paginate(config($this->pageSizeConfig));
        }
        return $builder->get();
    }

    /**
     * @param mixed $value
     * @param string $column
     * @param bool $paginate - If returning a collection, have the builder paginate?
     */
    public function findIn($values, string $column = 'id', bool $paginate = true)
    {
        $builder = $this->model->whereIn($column, $values);
        if ($paginate) {
            return $builder->paginate(config($this->pageSizeConfig));
        }
        return $builder->get();
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param array $rows - an array of attribute sets
     */
    public function insertMany(array $rows)
    {
        return $this->model->insert($rows);
    }

    /**
     * Paginate all results
     */
    public function paginate()
    {
        return $this->model->paginate(config($this->pageSizeConfig));
    }

    /**
     * @param int $id
     * @param array $attributes
     */
    public function update(int $id, array $attributes)
    {
        $this->model->find($id)->update($attributes);
        return $this->model->find($id);
    }


    /**
     * Get the first model or Create
     *
     * @param array $data
     * @return Model
     */
    public function firstOrCreate($data)
    {
        return $this->model->firstOrCreate($data);
    }
}
