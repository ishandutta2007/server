<?php
interface PermissionName extends BaseEnum
{
	// special services
	
	const FEATURE_ANALYTICS_TAB = 'FEATURE_ANALYTICS_TAB';
	const FEATURE_508_PLAYERS = 'FEATURE_508_PLAYERS';
	const FEATURE_LIVE_STREAM = 'FEATURE_LIVE_STREAM';
	const FEATURE_VAST = 'FEATURE_VAST';
	const FEATURE_SILVERLIGHT = 'FEATURE_SILVERLIGHT';
	const FEATURE_PS2_PERMISSIONS_VALIDATION = 'FEATURE_PS2_PERMISSIONS_VALIDATION';
	
	// base system permissions
	
	const USER_SESSION_PERMISSION = 'BASE_USER_SESSION_PERMISSION';
	const ALWAYS_ALLOWED_ACTIONS = 'ALWAYS_ALLOWED_ACTIONS';
	
	const SYSTEM_BASE_ACTIONS = 'SYSTEM_BASE_ACTIONS';
	const SYSTEM_FILESYNC = 'SYSTEM_FILESYNC';
	const SYSTEM_INTERNAL = 'SYSTEM_INTERNAL';
	
	// system admin and admin console permissions
	
	const SYSTEM_ADMIN_BASE = 'SYSTEM_ADMIN_BASE';
	
	const SYSTEM_ADMIN_PUBLISHER_BASE = 'SYSTEM_ADMIN_PUBLISHER_BASE';
	const SYSTEM_ADMIN_PUBLISHER_KMC_ACCESS = 'SYSTEM_ADMIN_PUBLISHER_KMC_ACCESS';
	const SYSTEM_ADMIN_PUBLISHER_CONFIG = 'SYSTEM_ADMIN_PUBLISHER_CONFIG';
	const SYSTEM_ADMIN_PUBLISHER_BLOCK = 'SYSTEM_ADMIN_PUBLISHER_BLOCK';
	const SYSTEM_ADMIN_PUBLISHER_REMOVE = 'SYSTEM_ADMIN_PUBLISHER_REMOVE';
	const SYSTEM_ADMIN_PUBLISHER_ADD = 'SYSTEM_ADMIN_PUBLISHER_ADD';
	const SYSTEM_ADMIN_PUBLISHER_USAGE = 'SYSTEM_ADMIN_PUBLISHER_USAGE';
	
	const SYSTEM_ADMIN_USER_MANAGE = 'SYSTEM_ADMIN_USER_MANAGE';
	
	const SYSTEM_ADMIN_SYSTEM_MONITOR = 'SYSTEM_ADMIN_SYSTEM_MONITOR';
	
	const SYSTEM_ADMIN_DEVELOPERS_TAB = 'SYSTEM_ADMIN_DEVELOPERS_TAB';
	
	const SYSTEM_ADMIN_BATCH_CONTROL = 'SYSTEM_ADMIN_BATCH_CONTROL';
	const SYSTEM_ADMIN_BATCH_CONTROL_INPROGRESS = 'SYSTEM_ADMIN_BATCH_CONTROL_INPROGRESS';
	const SYSTEM_ADMIN_BATCH_CONTROL_FAILED = 'SYSTEM_ADMIN_BATCH_CONTROL_FAILED';
	const SYSTEM_ADMIN_BATCH_CONTROL_SETUP = 'SYSTEM_ADMIN_BATCH_CONTROL_SETUP';
	
	const SYSTEM_ADMIN_STORAGE = 'SYSTEM_ADMIN_STORAGE';
	const SYSTEM_ADMIN_VIRUS_SCAN = 'SYSTEM_ADMIN_VIRUS_SCAN';
	const SYSTEM_ADMIN_EMAIL_INGESTION = 'SYSTEM_ADMIN_EMAIL_INGESTION';
	const SYSTEM_ADMIN_CONTENT_DISTRIBUTION_BASE = 'SYSTEM_ADMIN_CONTENT_DISTRIBUTION_BASE';
	const SYSTEM_ADMIN_CONTENT_DISTRIBUTION_MODIFY = 'SYSTEM_ADMIN_CONTENT_DISTRIBUTION_MODIFY';
	const SYSTEM_ADMIN_PERMISSIONS_MANAGE = 'SYSTEM_ADMIN_PERMISSIONS_MANAGE';
	
	
	// batch related permissions
	
	const BATCH_BASE = 'BATCH_BASE';
		
	// user actions permissions
	
	const CONTENT_INGEST_UPLOAD = 'CONTENT_INGEST_UPLOAD';
	const CONTENT_INGEST_BULK_UPLOAD = 'CONTENT_INGEST_BULK_UPLOAD';
	const CONTENT_INGEST_FEED = 'CONTENT_INGEST_FEED';
	
	const CONTENT_MANAGE_DISTRIBUTION_BASE = 'CONTENT_MANAGE_DISTRIBUTION_BASE';
	const CONTENT_MANAGE_DISTRIBUTION_MODIFY = 'CONTENT_MANAGE_DISTRIBUTION_MODIFY';
	const CONTENT_MANAGE_DISTRIBUTION_PROFILE_BASE = 'CONTENT_MANAGE_DISTRIBUTION_PROFILE_BASE';
	const CONTENT_MANAGE_DISTRIBUTION_PROFILE_MODIFY = 'CONTENT_MANAGE_DISTRIBUTION_PROFILE_MODIFY';
	
	const CONTENT_MANAGE_VIRUS_SCAN = 'CONTENT_MANAGE_VIRUS_SCAN';
	const CONTENT_MANAGE_MIX = 'CONTENT_MANAGE_MIX';
	const CONTENT_MANAGE_BASE = 'CONTENT_MANAGE_BASE';
	const CONTENT_MANAGE_METADATA = 'CONTENT_MANAGE_METADATA';
	const CONTENT_MANAGE_ASSIGN_CATEGORIES = 'CONTENT_MANAGE_ASSIGN_CATEGORIES';
	const CONTENT_MANAGE_THUMBNAIL = 'CONTENT_MANAGE_THUMBNAIL';
	const CONTENT_MANAGE_SCHEDULE = 'CONTENT_MANAGE_SCHEDULE';
	const CONTENT_MANAGE_ACCESS_CONTROL = 'CONTENT_MANAGE_ACCESS_CONTROL';
	const CONTENT_MANAGE_CUSTOM_DATA = 'CONTENT_MANAGE_CUSTOM_DATA';
	const CONTENT_MANAGE_DELETE = 'CONTENT_MANAGE_DELETE';
	const CONTENT_MANAGE_RECONVERT = 'CONTENT_MANAGE_RECONVERT';
	const CONTENT_MANAGE_EDIT_CATEGORIES = 'CONTENT_MANAGE_EDIT_CATEGORIES';
	const CONTENT_MANAGE_ANNOTATION = 'CONTENT_MANAGE_ANNOTATION';
	const CONTENT_MANAGE_SHARE = 'CONTENT_MANAGE_SHARE';
	const CONTENT_MANAGE_DOWNLOAD = 'CONTENT_MANAGE_DOWNLOAD';
	
	const LIVE_STREAM_ADD = 'LIVE_STREAM_ADD';
	const LIVE_STREAM_UPDATE = 'LIVE_STREAM_UPDATE';
		
	const CONTENT_MODERATE_BASE = 'CONTENT_MODERATE_BASE';
	const CONTENT_MODERATE_METADATA = 'CONTENT_MODERATE_METADATA';
	const CONTENT_MODERATE_CUSTOM_DATA = 'CONTENT_MODERATE_CUSTOM_DATA';
	const CONTENT_MODERATE_APPROVE_REJECT = 'CONTENT_MODERATE_APPROVE_REJECT';
	
	const PLAYLIST_BASE = 'PLAYLIST_BASE';
	const PLAYLIST_ADD = 'PLAYLIST_ADD';
	const PLAYLIST_UPDATE = 'PLAYLIST_UPDATE';
	const PLAYLIST_DELETE = 'PLAYLIST_DELETE';
	
	const SYNDICATION_BASE = 'SYNDICATION_BASE';
	const SYNDICATION_ADD = 'SYNDICATION_ADD';
	const SYNDICATION_UPDATE = 'SYNDICATION_UPDATE';
	const SYNDICATION_DELETE = 'SYNDICATION_DELETE';
	
	const STUDIO_BASE = 'STUDIO_BASE';
	const STUDIO_ADD_UICONF = 'STUDIO_ADD_UICONF';
	const STUDIO_UPDATE_UICONF = 'STUDIO_UPDATE_UICONF';
	const STUDIO_DELETE_UICONF = 'STUDIO_DELETE_UICONF';

	const ACCOUNT_BASE = 'ACCOUNT_BASE';
	const ACCOUNT_UPDATE_SETTINGS = 'ACCOUNT_UPDATE_SETTINGS';
	
	const INTEGRATION_BASE = 'INTEGRATION_BASE';
	const INTEGRATION_UPDATE_SETTINGS = 'INTEGRATION_UPDATE_SETTINGS';
	
	const ACCESS_CONTROL_BASE = 'ACCESS_CONTROL_BASE';
	const ACCESS_CONTROL_ADD = 'ACCESS_CONTROL_ADD';
	const ACCESS_CONTROL_UPDATE = 'ACCESS_CONTROL_UPDATE';
	const ACCESS_CONTROL_DELETE = 'ACCESS_CONTROL_DELETE';
	
	const TRANSCODING_BASE = 'TRANSCODING_BASE';
	const TRANSCODING_ADD = 'TRANSCODING_ADD';
	const TRANSCODING_UPDATE = 'TRANSCODING_UPDATE';
	const TRANSCODING_DELETE = 'TRANSCODING_DELETE';
	
	const CUSTOM_DATA_PROFILE_BASE = 'CUSTOM_DATA_PROFILE_BASE';
	const CUSTOM_DATA_PROFILE_ADD = 'CUSTOM_DATA_PROFILE_ADD';
	const CUSTOM_DATA_PROFILE_UPDATE = 'CUSTOM_DATA_PROFILE_UPDATE';
	const CUSTOM_DATA_PROFILE_DELETE = 'CUSTOM_DATA_PROFILE_DELETE';
	
	const ADMIN_BASE = 'ADMIN_BASE';
	
	const ADMIN_USER_ADD = 'ADMIN_USER_ADD';
	const ADMIN_USER_UPDATE = 'ADMIN_USER_UPDATE';
	const ADMIN_USER_DELETE = 'ADMIN_USER_DELETE';
	
	const ADMIN_ROLE_ADD = 'ADMIN_ROLE_ADD';
	const ADMIN_ROLE_UPDATE = 'ADMIN_ROLE_UPDATE';
	const ADMIN_ROLE_DELETE = 'ADMIN_ROLE_DELETE';
	
	const ADMIN_PUBLISHER_MANAGE = 'ADMIN_PUBLISHER_MANAGE';
	const ANALYTICS_BASE = 'ANALYTICS_BASE';
	
	const WIDGET_ADMIN = 'WIDGET_ADMIN';
	const SEARCH_SERVICE = 'SEARCH_SERVICE';
	const ANALYTICS_SEND_DATA = 'ANALYTICS_SEND_DATA';
	
	const AUDIT_TRAIL_BASE = 'AUDIT_TRAIL_BASE';
	const AUDIT_TRAIL_ADD = 'AUDIT_TRAIL_ADD';
	
	
	// KMC only permissions
	
	const ADVERTISING_BASE = 'ADVERTISING_BASE';
	const ADVERTISING_UPDATE_SETTINGS = 'ADVERTISING_UPDATE_SETTINGS';
	const PLAYLIST_EMBED_CODE = 'PLAYLIST_EMBED_CODE';
	const STUDIO_BRAND_UICONF = 'STUDIO_BRAND_UICONF';
	const STUDIO_SELECT_CONTENT = 'STUDIO_SELECT_CONTENT';
	const CONTENT_MANAGE_EMBED_CODE = 'CONTENT_MANAGE_EMBED_CODE';

	
	// not valid yet
	
	const ADMIN_WHITE_BRANDING = 'ADMIN_WHITE_BRANDING';		
}
