<?php
/**
 * @package plugins.dailymotionDistribution
 * @subpackage model
 */
class DailymotionDistributionProfile extends DistributionProfile
{
	const CUSTOM_DATA_USER = 'user';
	const CUSTOM_DATA_PASSWORD = 'password';
	const CUSTOM_DATA_METADATA_PROFILE_ID = 'metadataProfileId';	


	const METADATA_FIELD_CATEGORY = 'DailymotionCategory';
	const METADATA_FIELD_DESCRIPTION = 'DailymotionDescription';
	const METADATA_FIELD_TAGS = 'DailymotionKeywords';

	const ENTRY_NAME_MINIMUM_LENGTH = 1;
	const ENTRY_DESCRIPTION_MINIMUM_LENGTH = 1;
	const ENTRY_TAGS_MINIMUM_LENGTH = 1;
	
	/* (non-PHPdoc)
	 * @see DistributionProfile::getProvider()
	 */
	public function getProvider()
	{
		return DailymotionDistributionPlugin::getProvider();
	}
			
	/**
	 * @param EntryDistribution $entryDistribution
	 * @param int $action enum from DistributionAction
	 * @param array $validationErrors
	 * @param bool $validateDescription
	 * @param bool $validateTags
	 * @return array
	 */
	public function validateMetadataForSubmission(EntryDistribution $entryDistribution, $action, array $validationErrors, &$validateDescription, &$validateTags)
	{
		$validateDescription = true;
		$validateTags = true;
		
		if(!class_exists('MetadataProfile'))
			return $validationErrors;
			
		$metadataProfileId = $this->getMetadataProfileId();
		if(!$metadataProfileId)
		{
			$validationErrors[] = $this->createValidationError($action, DistributionErrorType::MISSING_METADATA, self::METADATA_FIELD_CATEGORY, '');
			return $validationErrors;
		}
		
		$metadataProfileCategoryField = MetadataProfileFieldPeer::retrieveByMetadataProfileAndKey($metadataProfileId, self::METADATA_FIELD_CATEGORY);
		if(!$metadataProfileCategoryField)
		{
			$validationErrors[] = $this->createValidationError($action, DistributionErrorType::MISSING_METADATA, self::METADATA_FIELD_CATEGORY, '');
			return $validationErrors;
		}
		
		$metadata = MetadataPeer::retrieveByObject($metadataProfileId, Metadata::TYPE_ENTRY, $entryDistribution->getEntryId());
		if(!$metadata)
		{
			$validationErrors[] = $this->createValidationError($action, DistributionErrorType::MISSING_METADATA, self::METADATA_FIELD_CATEGORY);
			return $validationErrors;
		}
		
		$values = $this->findMetadataValue(array($metadata), self::METADATA_FIELD_CATEGORY);
		
		if(!count($values))
			$validationErrors[] = $this->createValidationError($action, DistributionErrorType::MISSING_METADATA, self::METADATA_FIELD_CATEGORY, '');
			
		foreach($values as $value)
		{
			if(!strlen($value))
			{
				$validationErrors[] = $this->createValidationError($action, DistributionErrorType::INVALID_DATA, self::METADATA_FIELD_CATEGORY, '');
				return $validationErrors;
			}
		}
		
		$metadataProfileCategoryField = MetadataProfileFieldPeer::retrieveByMetadataProfileAndKey($metadataProfileId, self::METADATA_FIELD_DESCRIPTION);
		if($metadataProfileCategoryField)
		{
			$values = $this->findMetadataValue(array($metadata), self::METADATA_FIELD_DESCRIPTION);
			
			if(count($values))
			{	
				foreach($values as $value)
				{
					if(!strlen($value))
						continue;
				
					$validateDescription = false;
					
					// validate entry description minumum length of 1 character
					if(strlen($value) < self::ENTRY_DESCRIPTION_MINIMUM_LENGTH)
					{
						$validationError = $this->createValidationError($action, DistributionErrorType::INVALID_DATA, self::METADATA_FIELD_CATEGORY, 'Dailymotion description is too short');
						$validationError->setValidationErrorType(DistributionValidationErrorType::STRING_TOO_SHORT);
						$validationError->setValidationErrorParam(self::ENTRY_DESCRIPTION_MINIMUM_LENGTH);
						$validationErrors[] = $validationError;
					}
				}
			}
		}
		
		$metadataProfileCategoryField = MetadataProfileFieldPeer::retrieveByMetadataProfileAndKey($metadataProfileId, self::METADATA_FIELD_TAGS);
		if($metadataProfileCategoryField)
		{
			$values = $this->findMetadataValue(array($metadata), self::METADATA_FIELD_TAGS);
			
			if(count($values))
			{	
				foreach($values as $value)
				{
					if(!strlen($value))
						continue;
				
					$validateTags = false;
					
					// validate entry tags minumum length of 1 character
					if(strlen($value) < self::ENTRY_TAGS_MINIMUM_LENGTH)
					{
						$validationError = $this->createValidationError($action, DistributionErrorType::INVALID_DATA, self::METADATA_FIELD_CATEGORY, 'Dailymotion tags is too short');
						$validationError->setValidationErrorType(DistributionValidationErrorType::STRING_TOO_SHORT);
						$validationError->setValidationErrorParam(self::ENTRY_TAGS_MINIMUM_LENGTH);
						$validationErrors[] = $validationError;
					}
				}
			}
		}
		
		return $validationErrors;
	}
			
	/* (non-PHPdoc)
	 * @see DistributionProfile::validateForSubmission()
	 */
	public function validateForSubmission(EntryDistribution $entryDistribution, $action)
	{
		$validationErrors = parent::validateForSubmission($entryDistribution, $action);

		$entry = entryPeer::retrieveByPK($entryDistribution->getEntryId());
		if(!$entry)
		{
			KalturaLog::err("Entry [" . $entryDistribution->getEntryId() . "] not found");
			$validationErrors[] = $this->createValidationError($action, DistributionErrorType::INVALID_DATA, 'entry', 'entry not found');
			return $validationErrors;
		}
		
		// validate entry name minumum length of 1 character
		if(strlen($entry->getName()) < self::ENTRY_NAME_MINIMUM_LENGTH)
		{
			$validationError = $this->createValidationError($action, DistributionErrorType::INVALID_DATA, entryPeer::NAME, 'Name is too short');
			$validationError->setValidationErrorType(DistributionValidationErrorType::STRING_TOO_SHORT);
			$validationError->setValidationErrorParam(self::ENTRY_NAME_MINIMUM_LENGTH);
			$validationErrors[] = $validationError;
		}

		$validateDescription = true;
		$validateTags = true;
		$validationErrors = $this->validateMetadataForSubmission($entryDistribution, $action, $validationErrors, $validateDescription, $validateTags);
		
		if($validateDescription)
		{
			if(!strlen($entry->getDescription()))
			{
				$validationErrors[] = $this->createValidationError($action, DistributionErrorType::MISSING_METADATA, entryPeer::DESCRIPTION, 'Description is empty');
			}
			elseif(strlen($entry->getDescription()) < self::ENTRY_DESCRIPTION_MINIMUM_LENGTH)
			{
				$validationError = $this->createValidationError($action, DistributionErrorType::INVALID_DATA, entryPeer::DESCRIPTION, 'Description is too short');
				$validationError->setValidationErrorType(DistributionValidationErrorType::STRING_TOO_SHORT);
				$validationError->setValidationErrorParam(self::ENTRY_DESCRIPTION_MINIMUM_LENGTH);
				$validationErrors[] = $validationError;
			}
		}
	
		if($validateTags)
		{
			if(!strlen($entry->getTags()))
			{
				$validationErrors[] = $this->createValidationError($action, DistributionErrorType::MISSING_METADATA, entryPeer::TAGS, 'Tags is empty');
			}
			elseif(strlen($entry->getTags()) < self::ENTRY_TAGS_MINIMUM_LENGTH)
			{
				$validationError = $this->createValidationError($action, DistributionErrorType::INVALID_DATA, entryPeer::TAGS, 'Tags is too short');
				$validationError->setValidationErrorType(DistributionValidationErrorType::STRING_TOO_SHORT);
				$validationError->setValidationErrorParam(self::ENTRY_TAGS_MINIMUM_LENGTH);
				$validationErrors[] = $validationError;
			}
		}
		
		return $validationErrors;
	}
	
	public function getUser()					{return $this->getFromCustomData(self::CUSTOM_DATA_USER);}
	public function getPassword()				{return $this->getFromCustomData(self::CUSTOM_DATA_PASSWORD);}
	public function getMetadataProfileId()		{return $this->getFromCustomData(self::CUSTOM_DATA_METADATA_PROFILE_ID);}
	
	public function setUser($v)				{$this->putInCustomData(self::CUSTOM_DATA_USER, $v);}
	public function setPassword($v)				{$this->putInCustomData(self::CUSTOM_DATA_PASSWORD, $v);}
	public function setMetadataProfileId($v)	{$this->putInCustomData(self::CUSTOM_DATA_METADATA_PROFILE_ID, $v);}
}