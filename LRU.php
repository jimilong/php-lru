<?php
/**
 * Created by PhpStorm.
 * User: longmin
 * Date: 16/12/17
 * Time: 下午2:52
 */
class LRU
{
    public    $data = [];       //缓存数据
    protected $length  = 10000; //缓存数据长度
    protected $link = null;

    public function __construct()
    {
        $this->link = new SplDoublyLinkedList();
        $this->link->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);
        $this->link->rewind();
    }

    public function getData($val)
    {
        if (! isset($this->data[$val])) {
            $this->insert($val);
        }

        return $this->data[$val];
    }

    protected function insert($val)  //插入数据到链表头部
    {
        $index = $this->getLinkIndex($val);
        if ($index !== false) {   //数据已存在
            $this->link->offsetUnset($index);
        } else {
            $this->data[$val] = $this->createData($val);
        }
        $this->link->unshift($val);

        if ($this->link->count() > $this->length) {
            $end = $this->link->pop();
            unset($this->data[$end]);
        }
    }

    protected function getLinkIndex($val)
    {
        if ($this->link->isEmpty()) {
            return false;
        }
        if ($val == $this->link->bottom()) {
            return 0;
        }
        if ($val == $this->link->top()) {
            return $this->link->count();
        }

        for ($this->link->rewind(); $this->link->valid(); $this->link->next()) {
            if ($this->link->current() == $val) {
                return $this->link->key();
            }
        }

        return false;
    }

    protected function createData($val)
    {
        return $val;   //todo
    }
}

$lru = new LRU();

while (true) {
    $val = time();
    echo $lru->getData($val).PHP_EOL;
    print_r($lru->data);
    sleep(1);
}