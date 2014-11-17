<?php defined('DOCROOT') or die('No direct script access.');

class Dao_Base_Mongo
{

    const INSERT = 1;
    const UPDATE = 2;
    const SELECT = 3;
    const REMOVE = 4;
    const SAVE = 5;
    const DROP = 6;

    protected $cache_key = 'Dao_Base_Mongo';

    static $orders = array(
        'ASC' => 1,
        'DESC' => -1
    );


    protected $operations = array(
        '=' => '$eq',
        '!=' => '$ne',
        '<' => '$lt',
        '<=' => '$lte',
        '>' => '$gt',
        '>=' => '$gte',
        'IN' => '$in',
        'NOT IN' => '$nin',
    );

    protected $db = '';

    protected $lifetime = 0;
    protected $keycached = null;
    protected $tagcached = null;

    protected $limit = 0;
    protected $offset = 0;
    protected $order_by = array();
    protected $fields = array();
    protected $where = array();

    private $action;
    private $collection;

    protected $collection_name;

    public static function select($collection = NULL)
    {
        $self = new static();
        $self->action = self::SELECT;
        if ($collection) $self->collection_name = $collection;
        return $self;
    }

    public static function insert($collection = NULL)
    {
        $self = new static();
        $self->action = self::INSERT;
        if ($collection) $self->collection_name = $collection;
        return $self;
    }

    public static function update($collection = NULL)
    {
        $self = new static();
        $self->action = self::UPDATE;
        if ($collection) $self->collection_name = $collection;
        return $self;
    }

    public static function save($collection = NULL)
    {
        $self = new static();
        $self->action = self::SAVE;
        if ($collection) $self->collection_name = $collection;
        return $self;
    }

    public static function remove($collection = NULL)
    {
        $self = new static();
        $self->action = self::REMOVE;
        if ($collection) $self->collection_name = $collection;
        return $self;
    }

    public static function drop($collection = NULL)
    {
        $self = new static();
        $self->action = self::DROP;
        if ($collection) $self->collection_name = $collection;
        return $self;
    }

    public function set($field, $value)
    {
        $this->fields[$field] = $value;
        return $this;
    }

    public function where($field, $operation, $value)
    {
        $this->where[$field] = $operation !== '=' ? array($this->operations[$operation] => $value) : $value;
        return $this;
    }

    public function order_by($field, $value = 'DESC')
    {
        if (is_int($value)) {
            $this->order_by[$field] = $value;
        } else {
            $this->order_by[$field] = self::$orders[$value];
        }
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function cached($seconds, $key = null, $tag = null)
    {
        $this->lifetime = $seconds;
        if ($key) $this->keycached = $this->cache_key . ':' . $key;
        if ($tag) $this->tagcached = $tag;
        return $this;
    }

    public function clearcache($key = null, $tags = null)
    {
        /** @var Methods_Lemon $lemon */
        $lemon = Methods_Lemon::instance();

        if ($key) {
            $key = $this->cache_key . ':' . $key;
            $lemon->delete($key);
        }

        if ($tags) foreach ($tags as $tag) $lemon->delete_tag($tag);

        return $this;
    }

    private function getCacheKey()
    {
        return 'db:' . $this->db .
        ':c:' . $this->collection_name .
        ':w:' . json_encode($this->where) .
        ':l:' . $this->limit .
        ':o:' . $this->offset .
        ':s:' . json_encode($this->order_by);
    }

    public function execute()
    {
        if ($this->action == self::SELECT) {

            /** @var Methods_Lemon $lemon */
            $lemon = Methods_Lemon::instance();

            if ($this->lifetime) {
                $keycached = $this->keycached ? $this->keycached : $this->getCacheKey();

                try {
                    $cache = $lemon->get($keycached);
                } catch (Exception $e) {
                    $cache = null;
                }

                if (NULL !== $cache) {
                    return $cache;
                }
            }

            $this->collection = new MongoClient();
            $this->collection = $this->collection->selectDB($this->db)->selectCollection($this->collection_name);

            $find = $this->collection->find($this->where);

            if ($this->limit) $find->limit($this->limit);
            if ($this->offset) $find->skip($this->offset);
            if ($this->order_by) $find->sort($this->order_by);

            $select = array();
            foreach ($find as $key => $value) $select[$key] = $value;

            if ($select && $this->limit === 1) $select = current($select);

            if ($this->lifetime) $lemon->set($keycached, ($select ? $select : array()), $this->tagcached, $this->lifetime);

            return $select;

        } elseif ($this->action == self::INSERT || $this->action == self::UPDATE || $this->action == self::REMOVE || $this->action == self::SAVE || $this->action == self::DROP) {

            $this->collection = new MongoClient();
            $this->collection = $this->collection->selectDB($this->db)->selectCollection($this->collection_name);

            if ($this->action == self::INSERT) {
                $result = $this->collection->insert($this->fields);
            } elseif ($this->action == self::UPDATE) {
                $result = $this->collection->update($this->where, array('$set' => $this->fields), array('multi' => true));
            } elseif ($this->action == self::SAVE) {
                $result = $this->collection->save($this->fields, $this->where);
            } elseif ($this->action == self::REMOVE) {
                $result = $this->collection->remove($this->where);
            } elseif ($this->action == self::DROP) {
                $result = $this->collection->drop();
            }

            return isset($result['ok']) ? $result['ok'] : false;
        }

    }

}