<?php
/**
 * @package plugins.elasticSearch
 */
class ElasticSearchPlugin extends KalturaPlugin implements IKalturaEventConsumers //, IKalturaCriteriaFactory
{
	const PLUGIN_NAME = 'elasticSearch';
	const ELASTIC_SEARCH_MANAGER = 'kElasticSearchManager';
	
	public static function getPluginName()
	{
		return self::PLUGIN_NAME;
	}
	
	/**
	 * @return array
	 */
	public static function getEventConsumers()
	{
		return array(
			self::ELASTIC_SEARCH_MANAGER,
		);
	}


	/**
	 * Creates a new KalturaCriteria for the given object name
	 * 
	 * @param string $objectType object type to create Criteria for.
	 * @return KalturaCriteria derived object
	 */
	/*
	public static function getKalturaCriteria($objectType)
	{
		if ($objectType == "entry")
			return new SphinxEntryCriteria();
			
		if ($objectType == "category")
			return new SphinxCategoryCriteria();
			
		if ($objectType == "kuser")
			return new SphinxKuserCriteria();
		
		if ($objectType == "categoryKuser")
			return new SphinxCategoryKuserCriteria();
			
		return null;
	}
	*/
}
