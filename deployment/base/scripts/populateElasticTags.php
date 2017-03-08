<?php
chdir(dirname(__FILE__));

require_once(__DIR__ . '/../../bootstrap.php');
require_once(__DIR__.'/../../../vendor/elastic/autoload.php');
require_once('mapping.php');

$allPeers = array(
	"tag" => TagPeer,
	"entry" => entryPeer,
	"cuepiont" => CuePointPeer,
	"metadata" => MetadataPeer,
	"captionassetitem" => CaptionAssetItemPeer,
	"category" => categoryPeer,
	"kuser" => kuserPeer,
	"categorykuser" => categoryKuserPeer, // still needs to be tested further
	"entrydistribution" => EntryDistributionPeer,
	"scheduleevent" => ScheduleEventPeer,
);

$options = getopt('e:t:m:i:l:h');

if(isset($options['h']))
{
	printUsage();
	exit;
}

$elasticServer = "ubuntudrm:9200";
$tableName = "tag";
$exportFileName = false;
$importFile = false;
$limit = 100000;

foreach ($options as $option => $value)
{
	if ($option == 'e')
		$elasticServer = $value;
	if ($option == 't')
		$tableName = $value;
	if ($option == 'm')
		$exportFileName = $value;
	if ($option == 'i')
		$importFile = $value;
	if ($option == 'l')
		$limit = $value;
}

if ($importFile && $exportFileName)
{
	KalturaLog::alert('cannot have import and export files');
	exit;
}

if (!$exportFileName)
	$elastic = initElasticClient($elasticServer);

//createMappings($mappingParamsCategoryEntry);
//createMappings($mappingParamsEntry);

if ($importFile)
	doImportFromFile($importFile);
else
	doFromDB();
KalturaLog::log('Done');



function doFromDB()
{
	global $tableName, $exportFileName, $allPeers, $limit;

	$peer = null;
	if (isset($allPeers[$tableName]))
		$peer = $allPeers[$tableName];
	else
	{
		echo "unknown table name given\n";
		exit;
	}

	$c = new Criteria();
	$c->addAscendingOrderByColumn($peer::ID);
	$c->setLimit(min(10000, $limit));

	$con = myDbHelper::getConnection(myDbHelper::DB_HELPER_CONN_PROPEL2);
	$objects = $peer::doSelect($c, $con);

	$f = null;
	if ($exportFileName)
		$f = fopen($exportFileName, 'w');
	while (count($objects))
	{
		foreach ($objects as $currObject)
		{
			try
			{
				$elasticEntry = createElasticEntry($currObject, $f);
			} catch (Exception $e)
			{
				fclose($f);
				KalturaLog::err($e->getMessage());
				exit - 1;
			}
		}

		$c->setOffset($c->getOffset() + count($objects));
		if ($c->getOffset() > $limit)
		{
			KalturaLog::log("limit reached");
			break;
		}
		kMemoryManager::clearMemory();
		$objects = $peer::doSelect($c, $con);
	}
	if ($f)
		fclose($f);
}

function initElasticClient($elasticServer)
{
	$hosts = array($elasticServer);
	$client = Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
	->setHosts($hosts)      // Set the hosts
	->build();
	return $client;
}

function createElasticEntry($object, $f)
{
	global $elastic, $tableName;
	$params = [
		"index" => "kaltura_".$tableName."_index",
		"type" => "kaltura_".$tableName."_type",
		"id" => $object->getIntId(), // All IIndexable objects have this getter
		"body" => array(),
	];
	$objectIndexClass = $object->getIndexObjectName();
	$fields = $objectIndexClass::getElasticIndexFieldsMap();
	foreach($fields as $fieldName => $getterName)
	{
		$getter = "get" . $getterName;
		$params['body'][$fieldName] = $object->$getter();
	}
	if ($f)
		fwrite($f, serialize($params)."\n");
	else
		$response = $elastic->index($params);
}

function doImportFromFile($importFile)
{
	global $elastic;
	KalturaLog::debug("importing from [$importFile]");
	$inFile = fopen($importFile, "r");
	$totalImported = 0;
	if ($inFile) {
		while (($line = fgets($inFile)) !== false) {
			$line = trim($line);
			$params = unserialize($line);
			$elastic->index($params);
			$totalImported++;
		}
		fclose($inFile);
	} else {
		KalturaLog::alert('import file could not be opened');
	}
	KalturaLog::log("finished importing [$totalImported] rows to elastic");
}

function createMappings($indexParams)
{
	global $elastic;
	$response = $elastic->indices()->create($indexParams);
}

function printUsage()
{
	global $argv, $allPeers;
	$supportedTableNames = implode(" / ",array_keys($allPeers));
	echo "Usage: php ".$argv[0]." {OPTIONS}\n";
	echo "\t-e elastic server location\n";
	echo "\t-t sql table name to be populated to elastic < $supportedTableNames >\n";
	echo "\t-h show this help\n";
	echo "\t-m <file_path> to export to instead of elastic server\n";
	echo "\t-i <file_path> to import from instead of DB\n";
	echo "\t-l <number> number of total records to populate\n";
}