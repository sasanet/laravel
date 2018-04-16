<?php

/**
 * Created by Cristian.
 * Date: 18/09/16 08:36 PM.
 */

namespace Reliese\Meta\SqlSrv;

use Illuminate\Support\Fluent;

class Column implements \Reliese\Meta\Column
{
    /**
     * @var \Doctrine\DBAL\Schema\Column
     */
    protected $metadata;

    /**
     * @var array
     */
    protected $metas = [
        'type', 'name', 'autoincrement', 'nullable', 'default', 'comment',
    ];

    /**
     * @var array
     */
    public static $mappings = [
        'string' => ['varchar','char','nvarchar','nchar','string'],
        'date' => ['datetime', 'datetim2', 'date'],
        'int' => ['bigint', 'int', 'tinyint', 'smallint','integer'],
        'float' => ['float', 'decimal','money','smallmoney'],
        'real' =>['real'],
        'boolean' => ['bit','bool','boolean'],
    ];

    /**
     * MysqlColumn constructor.
     *
     * @param array $metadata
     */
    public function __construct($metadata = [])
    {
        $this->metadata = $metadata;
    }

    /**
     * @return \Illuminate\Support\Fluent
     */
    public function normalize()
    {
        $attributes = new Fluent();

        foreach ($this->metas as $meta) {
            $this->{'parse'.ucfirst($meta)}($attributes);
        }

        return $attributes;
    }

    /**
     * @param \Illuminate\Support\Fluent $attributes
     */
    protected function parseType(Fluent $attributes)
    {

        
        $dataType = $this->metadata->getType()->getName();

        foreach (static::$mappings as $phpType => $database) {
            if (in_array($dataType, $database)) {
                $attributes['type'] = $phpType;
            }
        }

        /*
        if ($attributes['type'] == 'int') {
            $attributes['unsigned'] = $this->metadata->getUnsigned();
        }*/
    }

    /**
     * @param \Illuminate\Support\Fluent $attributes
     */
    protected function parseName(Fluent $attributes)
    {
        $attributes['name'] = $this->metadata->getName();
    }

    /**
     * @param \Illuminate\Support\Fluent $attributes
     */
    protected function parseAutoincrement(Fluent $attributes)
    {
        $attributes['autoincrement'] = $this->metadata->getAutoincrement();
    }

    /**
     * @param \Illuminate\Support\Fluent $attributes
     */
    protected function parseNullable(Fluent $attributes)
    {
        $attributes['nullable'] = $this->metadata->getNotnull();
    }

    /**
     * @param \Illuminate\Support\Fluent $attributes
     */
    protected function parseDefault(Fluent $attributes)
    {
        $attributes['default'] = $this->metadata->getDefault();
    }

    /**
     * @param \Illuminate\Support\Fluent $attributes
     */
    protected function parseComment(Fluent $attributes)
    {
        $attributes['comment'] = $this->metadata->getComment();
    }
}