<?php
/**
 * Elastica query object
 * 
 * Creates different types of queries
 *
 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/search/
 * @category Xodoa
 * @package Elastica
 * @author Nicolas Ruflin <spam@ruflin.com>
 */
class Elastica_Query
{
	const TERM = 'term';
	const RANGE = 'range';
	const WILDCARD = 'wildcard';
	const QUERY_STRING = 'query_string';
	
	protected $_rawArguments = array();
	protected $_from = 0;
	protected $_limit = 10;
	protected $_sortArgs = array();
	protected $_explain = false;
	protected $_fields = array();
	protected $_scriptFields = array();
	protected $_query = array();
	protected $_filters = array();
	
	/**
	 * Creates a query object
	 * 
	 * @param array|Elastica_Query_Abstract $query OPTIONAL Query object (default = null)
	 */
	public function __construct($query = null) {
		if (is_array($query)) {
			$this->setRawQuery($query);
		} else if ($query instanceof Elastica_Query_Abstract) {
			$this->setQuery($query);
		}
	}
	
	/**
	 * Adds query as raw array
	 * 
	 * @param array $query Query array
	 */
	public function setRawQuery(array $query) {
		$this->_query = $query;
	}
	
	public function setQuery(Elastica_Query_Abstract $query) {
		$this->_query = $query->toArray();
	}
	
	/**
	 * @deprecated Use setQuery
	 */
	public function addQuery(Elastica_Query_Abstract $query) {
		trigger_error('addQuery is deprecated. Use setQuery instead');
		$this->setQuery($query);
	}
	
	public function addFilter(Elastica_Filter $filter) {
		$this->_filters = $filter->toArray();
	}
	
	public function setFrom($from) {
		$this->_from = $from;
	}
	
	/**
	 * Sets sort arguments for the query
	 * 
	 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/search/sort/
	 * @param array $sortArgs Sorting arguments
	 */
	public function setSort(array $sortArgs) {
		$this->_sortArgs = $sortArgs;
	}
	
	/**
	 * Sets highlight arguments for the query
	 * 
	 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/search/highlighting/
	 * @param array $highlightArgs
	 */
	public function setHighlight(array $highlightArgs) {
		$this->_highlightArgs = $highlightArgs;
	}
	
	/**
	 * Alias for setLimit
	 * 
	 * @param int $limit OPTIONAL Maximal number of results for query (default = 10)
	 */
	public function setSize($limit = 10) {
		$this->setLimit($limit);
	}
	
	/**
	 * Sets maximum number of results for this query
	 * 
	 * @param int $limit OPTIONAL Maximal number of results for query (default = 10)
	 */
	public function setLimit($limit = 10) {
		$this->_limit = $limit;
	}
	
	/**
	 * Enables explain on the query
	 * 
	 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/search/explain/
	 * @param bool $explain OPTIONAL Enabled or disable explain (default = true)
	 */
	public function setExplain($explain = true) {
		$this->_explain = $explain;
	}
	
	/**
	 * Sets the fields to be returned by the search
	 * 
	 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/search/fields/
	 * @param array $fields Fields to be returne
	 */
	public function setFields(array $fields) {
		$this->_fields = $fields;
	}
	
	/**
	 * Set script fields
	 * 
	 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/search/script_fields/
	 * @param array $scriptFields Script fields
	 */
	public function setScriptFields(array $scriptFields) {
		$this->_scriptFields = $scriptFields;
	}
	
	/**
	 * Allows to set raw arguments that can't be set over the
	 * provided method. Field name has also to be set in given array.
	 * Values set here are overrided by values set
	 * over the specific methods
	 * 
	 * @param array $args Argument array
	 */
	public function setRawArguments(array $args) {
		$this->_rawArgumens = $args;
	}
	
	/**
	 * @link http://www.elasticsearch.com/docs/elasticsearch/rest_api/search/facets
	 */
	public function setFacets(array $args) {
		throw new Elastica_Exception('not implemented yet');
	}
	
	public function toArray() {
		
		$query = $this->_rawArguments;

		$query['query'] = $this->_query;
		
		if (!empty($this->_limit)) {
			$query['size'] = $this->_limit;
		}
		
		$query['from'] = $this->_from;
		
		if (!empty($this->_sortArgs)) {
			$query['sort'] = $this->_sortArgs;
		}
		
		if (!empty($this->_highlightArgs)) {
			$query['highlight'] = $this->_highlightArgs;
		}
		
		if ($this->_explain) {
			$query['explain'] = $this->_explain;
		}
		
		if (!empty($this->_fields)) {
			$query['fields'] = $this->_fields;
		}
		
		if (!empty($this->_scriptFields)) {
			$query['script_fields'] = $this->_scriptFields;
		}
		
		if (!empty($this->_filters)) {
			// TODO: should query really be overwritten?
			$query['query'] = $this->_filters;			
		}
		
		return $query;
	}
}