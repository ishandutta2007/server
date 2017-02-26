<?php
/**
 * @package plugins.elasticSearch
 * @subpackage lib
 */
class kElasticSearchManager implements kObjectReadyForIndexEventConsumer
{

	const ELASTIC_INDEX_NAME = 'kaltura';

	/* (non-PHPdoc)
	 * @see kObjectReadyForIndexEventConsumer::shouldConsumeReadyForIndexEvent()
	 */
    public function shouldConsumeReadyForIndexEvent(BaseObject $object)
	{
		if($object instanceof IIndexable)
			return true;
		return false;
	}
	
	/* (non-PHPdoc)
	 * @see kObjectReadyForIndexEventConsumer::objectReadyForIndex()
	 */
	public function objectReadyForIndex(BaseObject $object, BatchJob $raisedJob = null)
	{
		$this->saveToElastic($object);
		return true;
	}
	
	private function retrieveSphinxConnectionId ()
	{
		$sphinxConnectionId = null;
		if(kConf::hasParam('exec_sphinx') && kConf::get('exec_sphinx'))
        {
        	$sphinxConnection = DbManager::getSphinxConnection(false);
			$sphinxServerCacheStore = kCacheManager::getSingleLayerCache(kCacheManager::CACHE_TYPE_SPHINX_EXECUTED_SERVER);
			if ($sphinxServerCacheStore)
			{
				$sphinxConnectionId = $sphinxServerCacheStore->get(self::CACHE_PREFIX . $sphinxConnection->getHostName());
				if ($sphinxConnectionId)
					return $sphinxConnectionId;
			}
			
			$sphinxServer = SphinxLogServerPeer::retrieveByLocalServer($sphinxConnection->getHostName());
			if($sphinxServer)
			{
	        	$sphinxConnectionId = $sphinxServer->getId();
				if ($sphinxServerCacheStore)
					$sphinxServerCacheStore->set(self::CACHE_PREFIX . $sphinxConnection->getHostName(), $sphinxConnectionId);
			}
		}
		
		return $sphinxConnectionId;
	}
		
	/**
	 * @param IIndexable $object
	 * @param bool $isInsert
	 * @param bool $force 
	 * TODO remove $force after replace bug solved
	 * 
	 * @return bool
	 */
	public function saveToElastic(IIndexable $object)
	{
		KalturaLog::debug('Updating elastic for object [' . get_class($object) . '] [' . $object->getId() . ']');

		$elasticConnection = DbManager::getElasticConnection(false);

		$objectIndexClass = $object->getIndexObjectName();
		$indexName = kElasticSearchManager::getElasticIndexName($objectIndexClass::getObjectIndexName());
		$typeName = kElasticSearchManager::getElasticIndexName($objectIndexClass::getObjectIndexName());
		$params = [
			"index" => $indexName,
			"type" => $typeName,
			"id" => $object->getIntId(), // All IIndexable objects have this getter
			"body" => array(),
		];
		$fields = $objectIndexClass::getElasticIndexFieldsMap();
		foreach($fields as $fieldName => $getterName)
		{
			$getter = "get" . $getterName;
			$params['body'][$fieldName] = $object->$getter();
		}
		$response = $elasticConnection->index($params);
	}

	/**
	 * @param string $baseName
	 * @return string
	 */
	public static function getElasticIndexName($baseName)
	{
		return self::ELASTIC_INDEX_NAME . '_' . $baseName . '_index';
	}

	/**
	 * @param string $baseName
	 * @return string
	 */
	public static function getElasticTypeName($baseName)
	{
		return self::ELASTIC_INDEX_NAME . '_' . $baseName . '_type';
	}

}
