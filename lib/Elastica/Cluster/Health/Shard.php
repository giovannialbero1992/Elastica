<?php

/**
 * Wraps status information for a shard.
 *
 * @package Elastica
 * @author Ray Ward <ray.ward@bigcommerce.com>
 * @link http://www.elasticsearch.org/guide/reference/api/admin-cluster-health.html
 */
class Elastica_Cluster_Health_Shard
{
    /**
     * The shard index/number.
     *
     * @var int
     */
    protected $_shardNumber;

    /**
     * The shard health data.
     *
     * @var array
     */
    protected $_data;

    /**
     * @param int $shardNumber The shard index/number.
     * @param array $data The shard health data.
     */
    public function __construct($shardNumber, $data)
    {
        $this->_shardNumber = $shardNumber;
        $this->_data = $data;
    }

    /**
     * Gets the index/number of this shard.
     *
     * @return int
     */
    public function getShardNumber()
    {
        return $this->_shardNumber;
    }

    /**
     * Gets the status of this shard.
     *
     * @return string green, yellow or red.
     */
    public function getStatus()
    {
        return $this->_data['status'];
    }

    /**
     * Is the primary active?
     *
     * @return bool
     */
    public function isPrimaryActive()
    {
        return $this->_data['primary_active'];
    }

    public function isActive()
    {
        return $this->_data['active_shards'] == 1;
    }

    public function isRelocating()
    {
        return $this->_data['relocating_shards'] == 1;
    }

    public function isInitialized()
    {
        return $this->_data['initializing_shards'] == 1;
    }

    public function IsUnassigned()
    {
        return $this->_data['unassigned_shards'] == 1;
    }
}

