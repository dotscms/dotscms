<?php
namespace ZeDb;

use Zend\Db\TableGateway\TableGateway as Gateway;

class TableGateway extends Gateway
{
    protected static $PATTERNS = array(
        '/^getAll(?:OrderBy(?P<orderBy>[A-Z][a-zA-Z0-9]+))?(?:Limit(?P<limit>[0-9]+)(?:From(?P<offset>[0-9]+))?)?$/' => '__getAll',
        '/^getByColumns(?:OrderBy(?P<orderBy>[A-Z][a-zA-Z0-9]+))?(?:Limit(?P<limit>[0-9]+)(?:From(?P<offset>[0-9]+))?)?$/' =>'__getBy',
        '/^getAllByColumns(?:OrderBy(?P<orderBy>[A-Z][a-zA-Z0-9]+))?(?:Limit(?P<limit>[0-9]+)(?:From(?P<offset>[0-9]+))?)?$/' => '__getAll',
        '/^getBy(?P<fields>[A-Z][a-zA-Z0-9]+)(?:OrderBy(?P<orderBy>[A-Z][a-zA-Z0-9]+))?(?:Limit(?P<limit>[0-9]+)(?:From(?P<offset>[0-9]+))?)?$/' => '__getBy',
        '/^getAllBy(?P<fields>[A-Z][a-zA-Z0-9]+)(?:OrderBy(?P<orderBy>[A-Z][a-zA-Z0-9]+))?(?:Limit(?P<limit>[0-9]+)(?:From(?P<offset>[0-9]+))?)?$/' => '__getAll',
        '/^getLike(?P<fields>[A-Z][a-zA-Z0-9]+)(?:OrderBy(?P<orderBy>[A-Z][a-zA-Z0-9]+))?(?:Limit(?P<limit>[0-9]+)(?:From(?P<offset>[0-9]+))?)?$/' => '__getLike',
        '/^getAllLike(?P<fields>[A-Z][a-zA-Z0-9]+)(?:OrderBy(?P<orderBy>[A-Z][a-zA-Z0-9]+))?(?:Limit(?P<limit>[0-9]+)(?:From(?P<offset>[0-9]+))?)?$/' => '__getAllLike',

        '/^removeBy(?P<fields>[A-Z][a-zA-Z0-9]+)(?:OrderBy(?P<orderBy>[A-Z][a-zA-Z0-9]+))?(?:Limit(?P<limit>[0-9]+)(?:From(?P<offset>[0-9]+))?)?$/' => '__removeBy',
    );

    public function __call($name, $args){
        foreach (static::$PATTERNS as $pattern=>$function){
            $matches = null;
            $found = preg_match($pattern, $name, $matches);
            if ($found){
                $options = array();
                foreach($matches as $key=>$value){
                    if (!is_int($key))
                        $options[$key] = $value;
                }
                return $this->$function($options, $args);
            }
        }
        throw new Exception('Invalid method called: ' . $name);
    }

    private function __removeBy($matches, $args)
    {
        $where = $this->_parseWhere($matches, $args);
        $order = $this->_parseOrder($matches);
        $limit = $this->_parseLimit($matches);

        $result = $this->delete(function ($select) use ($where, $order, $limit)
        {
            $select->where($where);
            //@todo set order
            //@todo set limit
        });
        return $result;
    }

    private function __getBy($matches, $args){
        $resultSet = $this->_getResultSet($matches, $args);
        $entity = $resultSet->current();
        return $entity;
    }

    private function __getAll($matches, $args){
        $resultSet = $this->_getResultSet($matches, $args);
        $entities = array();
        foreach ($resultSet as $entity){
            $entities[] = $entity;
        }
        return $entities;
    }

    private function _getResultSet($matches, $args){
        $where = $this->_parseWhere($matches, $args);
        $order = $this->_parseOrder($matches);
        $limit = $this->_parseLimit($matches);

        $resultSet = $this->select(function ($select) use ($where, $order, $limit)
        {
            $select->where($where);
//            $select->order($order);
            //@todo set limit
        });
        return $resultSet;
    }

    private function _parseWhere($matches, $args){
        $where = array();
        if (array_key_exists('fields', $matches) && !empty($matches['fields'])) {
            $fields = explode('And', $matches['fields']);
            $fields = $this->__normalizeKeys($fields);
            $where = array_combine($fields, $args);
        }else{
            //handle by columns
            $where = $args[0];
        }
        return $where;
    }

    private function _parseOrder($matches){
        $order = array();
        if (array_key_exists('orderBy', $matches) && !empty($matches['orderBy'])) {
            $orderBy = $this->__normalizeKeys($matches['orderBy']);
            $orderBy = explode('And', $orderBy);
            foreach ($orderBy as $value) {
                if (substr($value, -4) == 'Desc')
                    $order[substr($value, 0, -4)] = 'DESC';
                else
                    $order[$value] = 'ASC';
            }
        }
//        $orderBy = array();
//        foreach($order as $k=>$v){
//            $orderBy[]= "$k $v";
//        }
//        return implode(', ', $orderBy);
        return $order;
    }

    private function _parseLimit($matches){
        $limit = (array_key_exists('limit', $matches) ? $matches['limit'] : null);
        $offset = (array_key_exists('offset', $matches) ? $matches['offset'] : null);
        if (!$limit) return null;
        if ($limit && !$offset) return $limit;
        return array($limit, $offset);
    }

    private function __normalizeKeys($keys){
        if (!is_array($keys))
            return strtolower(preg_replace('/([A-Z]+)/', '_\1', lcfirst($keys)));
        foreach ($keys as $k => $v)
            $keys[$k] = strtolower(preg_replace('/([A-Z]+)/', '_\1', lcfirst($v)));
        return $keys;
    }
}