<?php

if ($argc != 4)
{
	echo "Usage: php ".$argv[0]." <entry_id> <user_id> <privacyContext>\n";
	exit;
}


require_once(__DIR__ . '/../../bootstrap.php');
require_once(__DIR__.'/../../../vendor/elastic/autoload.php');

$entryId = $argv[1];
$userId = $argv[2];
$privacyContext = $argv[3];

$elastic = initElasticClient('ubuntudrm:9200');

echo "is entitled [".isEntitled($entryId, $userId, $privacyContext)."]\n";

function isEntitled($entryId, $userId, $privacyContext)
{
//	$pcCategories = getPrivacyContextCategories($privacyContext);
//	echo "pc category ids [".print_r($pcCategories,true)."]\n";
	$entryCategories = getEntryCategories($entryId, $privacyContext);
//	echo "entry categories [".print_r($entryCategories,true)."]\n";
	$userBelongs = isUserBelongsToCategories($userId, explode(',',$entryCategories));
	return $userBelongs;
}

function getPrivacyContextCategories($privacyContext)
{
	global $elastic;
	$params = [
		'index' => 'kaltura_category_index',
		'type' => 'kaltura_category_type',
		//'size' => 3,
		'body' => [
			'query' => [
				'bool' => [
					'should' => [
//						'match' => [
//							'privacy_context' => $privacyContext
//						],
						'match' => [
							'privacy_contexts' => $privacyContext
						]
					]
				]
			]
		]
	];
	$results = $elastic->search($params);
//	echo "categories [".print_r($results,true)."]\n";
	if ($results['hits']['total'] < 1)
	{
		echo "could not find categories with privacy context [".$privacyContext."]\n";
		exit;
	}
	$privacyContexts = array();
	foreach ($results['hits']['hits'] as $category)
	{
		$privacyContexts[] = $category['_source']['privacy_context'];
	}
	return $privacyContexts;
}

/*
 * Since we filter specifically on an exact match there is no need to add a filter for partner_id
 * In a real use case we will probalby want to check status of entry and similar stuff.
 */
function getEntryCategories($entryId, $privacyContext)
{
	global $elastic;
	$params = [
		'index' => 'kaltura_entry_index',
		'type' => 'kaltura_entry_type',
		'size' => 1,
		'body' => [
			'query' => [
				'bool' => [
					'must' => [
						['match' => ['privacy_by_contexts' => $privacyContext ]],
						['match' =>	['entry_id' => $entryId]]
					]
				]
			]
		]
	];
	echo "entry query [".json_encode($params, JSON_PRETTY_PRINT)."]\n";
	$results = $elastic->search($params);
	if ($results['hits']['total'] < 1)
	{
		echo "could not find entry with id [".$entryId."]\n";
		exit;
	}
	echo "entries [".print_r($results['hits']['hits'][0]['_source']['entry_id'],true)."]\n";
	return $results['hits']['hits'][0]['_source']['categories'];
}

function isUserBelongsToCategories($userId, $entryCategories)
{
	global $elastic;
//	echo "entry categories [".print_r($entryCategories,true)."]\n";
	$params = [
		'index' => 'kaltura_categorykuser_index',
		'type' => 'kaltura_categorykuser_type',
		'size' => 1,
		'body' => [
			'query' => [
				'bool' => [
					'must' => ['match' => ['puser_id' => $userId]]
				]
			]
		]
	];
	if (count($entryCategories) > 0)
	{
		$shouldArr = array();
		foreach ($entryCategories as $categoryId)
		{
			$shouldArr[] = ['match' => ['category_id' => $categoryId]];
		}
		$params['body']['query']['bool']['should'] = $shouldArr;
		$params['body']['query']['bool']['minimum_should_match'] = 1;
	}
	echo "kuser query [".json_encode($params, JSON_PRETTY_PRINT)."]\n";
	$results = $elastic->search($params);
	if ($results['hits']['total'] < 1)
	{
		echo "could not find user with id [".$userId."]\n";
		return false;
	}
	echo "found user data [".print_r($results,true)."]\n";
	return true;
}

function initElasticClient($elasticServer)
{
	$hosts = array($elasticServer);
	$client = Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
	->setHosts($hosts)      // Set the hosts
	->build();
	return $client;
}