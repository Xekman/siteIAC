<?
	$SYSTEM_LOCALE = array('ru_RU.CP1251', 'rus', 'russian', 'Russian_Russia.1251');
	$CHARSET_NAME_WINDOWS1251 = 'Windows-1251';
	$CHARSET_NAME_UTF8 = 'UTF-8';
	$SUPPORT_EMAIL_HOTLINE_REGISTER = 'sudhelp@sudrf.ru';
	$SUPPORT_EMAIL_HOTLINE_CLOSE = 'tech@iterion.ru';
	$SUPPORT_NAME_HOTLINE = '������� �����';
	$SYSTEM_CHARSET = $CHARSET_NAME_WINDOWS1251;

	$AJAX_LETTERS_NAME = 'letters';
	$AJAX_LIMIT_NAME = 'limit';
	$AJAX_LIST_SEPARATOR = '|';
	$AJAX_LIMIT_DEFAULT_VALUE = 100;
	
	$PRINT_EXPANSION_NAME = array();

	$PRINT_EXPANSION_NAME[0] = '.doc';
	$PRINT_EXPANSION_NAME[1] = '.xls';
	$PRINT_EXPANSION_NAME[2] = '.docx';
	$PRINT_EXPANSION_NAME[3] = '.xlsx';
	$PRINT_EXPANSION_NAME[4] = '.txt';

	$WEBCAMERA_EXPANSION_NAME[0] = '.jpg';
	$WEBCAMERA_EXPANSION_NAME[1] = '.jpeg';
	$WEBCAMERA_EXPANSION_NAME[2] = '.png';
	$WEBCAMERA_EXPANSION_NAME[3] = '.bmp';	
	
	$INTERSERVICEFUNCTION_EXPANSION_NAME = '.xml';

	$MESSAGE_PHOTO_PREVIEW_PREFIX_NAME = 'photo_message';
	$MESSAGE_PHOTO_PREFIX_NAME = 'photo';	
	
	$INDEX_PAGE_NAME = 'index.php?';
	$MAX_INT_VALUE = 4294967295;
	$CONSUMABLE_NUMBER_MAX_DIGIT = 9;
	$INVALID_HANDLE_VALUE = 4294967295;
	$HOTLINE_REGISTER_DELAY = 43200;

	$MAIN_ROOT_PATH = getcwd().'\\';

	$LICENSE_KEY_PATH = $MAIN_ROOT_PATH;
	
	$FILE_USER_SEPARATOR = '_';

	$REQUESTOFREPAIR_NUMBER_SEPARATOR = '-';
	$REQUESTOFCONSUMABLES_NUMBER_SEPARATOR = '-';
	$REQUEST_NUMBER_SEPARATOR = '-';
	$LIST_NUMBER_SEPARATOR = '-';
	$TRAVEL_NUMBER_SEPARATOR = '-';
	$INSTRUCTION_NUMBER_SEPARATOR = '-';
	
	$URL_VALUE_SEPARATOR = '=';
	$URL_PARAMETR_SEPARATOR = '&';
	
	$FILE_INSTRUCTION_ROOT_NAME = 'files/instruction/';
	$FILE_SERVICE_ROOT_NAME = 'files/service/';
	$FILE_DOCUMENT_ROOT_NAME = 'files/document/';
	$FILE_REQUESTOFDOCUMENT_ROOT_NAME = 'files/requestofdocument/';
	$FILE_TEMPLATE_ROOT_NAME = 'files/template/';
	$FILE_FILESHARE_ROOT_NAME = 'files/fileshare/';
	$FILE_TEMP_ROOT_NAME = 'files/temp/';
	$FILE_VISITER_ROOT_NAME = 'files/visiter/';
	$FILE_INTERNALTEMPLATE_ROOT_NAME = 'files/internaltemplate/';
	$FILE_WEBCAMERA_ROOT_NAME = 'files/webcamera/';
	$FILE_USER_ROOT_NAME = 'files/user/';
	$FILE_REPORT_ROOT_NAME = 'files/report/';
	$FILE_INTERSERVICEFUNCTION_ROOT_NAME = 'files/interservicefunction/';
	$FILE_INTERSERVICE_ROOT_NAME = 'interservice/';
	$FILE_REQUEST_ROOT_NAME = 'files/request/';
	$FILE_DOCUMENT_BRANCH_ROOT_NAME = 'files/document/';
	$FILE_COOKIES_ROOT_NAME = 'files/cookies/';
	$FEDZAPRAVKA_PATH = 'fedzapravka/';
	
	$RQ_COMMAND_VALUE_NEWS = 'news';
	$RQ_COMMAND_VALUE_INSTRUCTION = 'instruction';
	$RQ_COMMAND_VALUE_MSGBOX = 'msgbox';
	$RQ_COMMAND_VALUE_AUTHORIZATION = 'authorization';
	$RQ_COMMAND_VALUE_SUPPORT = 'support';
	$RQ_COMMAND_VALUE_MESSAGE = 'message';
	$RQ_COMMAND_VALUE_SERVICE = 'service';
	$RQ_COMMAND_VALUE_DOCUMENT = 'document';
	$RQ_COMMAND_VALUE_VISITER = 'visiter';
	$RQ_COMMAND_VALUE_TEMPLATE = 'template';
	$RQ_COMMAND_VALUE_SERVICEOFDOCUMENT = 'serviceofdocument';
	$RQ_COMMAND_VALUE_REQUEST3 = 'request3';
	$RQ_COMMAND_VALUE_REQUEST = 'request';
	$RQ_COMMAND_VALUE_VISITERLEGAL = 'visiterlegal';
	$RQ_COMMAND_VALUE_LISTESCORT = 'listescort';
	$RQ_COMMAND_VALUE_LISTARCHIVE = 'listarchive';
	$RQ_COMMAND_VALUE_LISTCONSUMABLES = 'listconsumables';
	$RQ_COMMAND_VALUE_LISTCONSUMABLESBACK = 'listconsumablesback';	
	$RQ_COMMAND_VALUE_LISTCONFIRM = 'listconfirm';
	$RQ_COMMAND_VALUE_LISTLOST = 'listlost';
	$RQ_COMMAND_VALUE_ABOUT = 'about';
	$RQ_COMMAND_VALUE_LIFESITUATION = 'lifesituation';
	$RQ_COMMAND_VALUE_LIFESITUATION2 = 'lifesituation2';
	$RQ_COMMAND_VALUE_EQUIPMENT = 'equipment';
	$RQ_COMMAND_VALUE_EQUIPMENTOFDIVISION = 'equipmentofdivision';
	$RQ_COMMAND_VALUE_BBB = 'bbb';
	$RQ_COMMAND_VALUE_RECORD = 'record';
	$RQ_COMMAND_VALUE_PERSON = 'person';
	$RQ_COMMAND_VALUE_REPORT = 'report';

	$RQ_COMMAND_VALUE_CONSUMABLES = 'consumables';
	$RQ_COMMAND_VALUE_CONSUMABLESOFDIVISION = 'consumablesofdivision';	
	
	$RQ_COMMAND_NAME = 'command';

	$TABLE_REQUEST_NUMBER_NAME = 'NumberRequest'.date('Y');
	$TABLE_REPAIR_NUMBER_NAME = 'NumberRepair'.date('Y');
	$TABLE_TRAVEL_NUMBER_NAME = 'NumberTravel'.date('Y');

	$RQ_REC_INDEX_NAME = 'recid';
	$RQ_DOCUMENT_INDEX_NAME = 'did';
	$RQ_SERVICEOFDOCUMENT_INDEX_NAME = 'sodid';
	$RQ_TEMPLATE_INDEX_NAME = 'tid';
	$RQ_SERVICE_INDEX_NAME = 'sid';
	$RQ_AGENCYGROUP_INDEX_NAME = 'agid';
	$RQ_AGENCY_INDEX_NAME = 'aid';
	$RQ_VISITER_INDEX_NAME = 'vid';
	$RQ_REQUEST_INDEX_NAME = 'rid';
	$RQ_LIST_INDEX_NAME = 'lid';
	$RQ_USER_INDEX_NAME = 'uid';
	$RQ_INTERSERVICE_INDEX_NAME = 'isid';
	$RQ_NEWSGROUP_INDEX_NAME = 'ngid';
	$RQ_CONSUMABLES_INDEX_NAME = 'cid';
	$RQ_BBB_INDEX_NAME = 'bbb';
	$RQ_PERSON_INDEX_NAME = 'pid';
	
	$RQ_EQUIPMENTGROUP_SUBGROUP_INDEX_NAME = 'egsgid';
	$RQ_EQUIPMENTGROUP_INDEX_NAME = 'egid';
	$RQ_CONSUMABLESGROUP_INDEX_NAME = 'cgid';
	$RQ_EQUIPMENT_INDEX_NAME = 'eid';
	$RQ_EQUIPMENTOFDIVISION_INDEX_NAME = 'eodid';
	$RQ_CONSUMABLESOFDIVISION_INDEX_NAME = 'codid';
	$RQ_DIVISION_INDEX_NAME = 'did';
	$RQ_INSTRUCTION_INDEX_NAME = 'iid';
	$RQ_REQUESTOFREPAIR_INDEX_NAME = 'rorid';
	$RQ_REQUESTOFCONCUMABLES_INDEX_NAME = 'rocid';
	$RQ_REQUESTOFDOCUMENT_INDEX_NAME = 'rodid';
	$RQ_TRAVEL_INDEX_NAME = 'tid';
	$RQ_PAYDOCUMENTOFTRAVEL_INDEX_NAME = 'pdorid';
	$RQ_EQUIPMENTCONSUMABLES_INDEX_NAME = 'ecid';
	$RQ_DOCUMENT_STATUS_INDEX_NAME = 'dsid';
	$RQ_DOCUMENT_BRANCH_INDEX_NAME = 'dbid';
	$RQ_SIGNATURE_INDEX_NAME = 'dbsid';
	$RQ_DOCUMENT_SIGNATURE_INDEX_NAME = 'dsigid';
	$RQ_UPDATE_KIT_SOFWARE_INDEX_NAME = 'uksid';
	$RQ_SERVICESUBREGLAMENT_INDEX_NAME = 'ssid';
	
	$RQ_ACTION_VALUE_CREATE = 'create';
	$RQ_ACTION_VALUE_CREATEANDSELECT = 'creates';
	$RQ_ACTION_VALUE_CHANGE = 'change';
	$RQ_ACTION_VALUE_JOIN = 'join';
	$RQ_ACTION_VALUE_PUBLISH = 'publish';
	$RQ_ACTION_VALUE_JOINMODERATOR = 'joinmoderator';
	$RQ_ACTION_VALUE_DELETE = 'delete';
	$RQ_ACTION_VALUE_SELECT = 'select';
	$RQ_ACTION_VALUE_LOGIN = 'login';
	$RQ_ACTION_VALUE_LOGOUT = 'logout';
	$RQ_ACTION_VALUE_PRINT = 'print';
	$RQ_ACTION_VALUE_LOCK = 'lock';
	$RQ_ACTION_VALUE_VIEW = 'view';
	$RQ_ACTION_VALUE_DOCUMENT = 'document';
	$RQ_ACTION_VALUE_REQUEST = 'request';
	$RQ_ACTION_VALUE_IMPORT = 'import';
	$RQ_ACTION_VALUE_COPY = 'copy';
	$RQ_ACTION_VALUE_PASTE = 'paste';
	$RQ_ACTION_VALUE_DELETEEX = 'deleteex';
	$RQ_ACTION_VALUE_COMPLETE = 'complete';
	$RQ_ACTION_VALUE_REPAIR = 'repair';
	$RQ_ACTION_VALUE_SIGNATURE = 'signature';
	$RQ_ACTION_VALUE_EXPORT = 'export';
	$RQ_ACTION_VALUE_SERVICE_REGLAMENT = 'servicereglamentequipmentgroup';
	$RQ_ACTION_VALUE_FILE = 'file';
	$RQ_ACTION_VALUE_DIVISION = 'division';
	
	$RQ_ACTION_VALUE_TOGARBAGE = 'garbage';
	
	$RQ_ACTION_VALUE_CONSUMABLESFROMDIVISION_DO = 'do';
	$RQ_ACTION_VALUE_CONSUMABLESFROMDIVISION_BACK = 'back';	
	$RQ_ACTION_VALUE_DOCUMENT_STATUS = 'documentstatus';
	$RQ_ACTION_VALUE_DOCUMENT_SIGNATURE = 'documentsignature';
	$RQ_ACTION_VALUE_SIGNATURE = 'signature';
	
	$RQ_ACTION_VALUE_HOTLINE_REGISTER = 'hotlineregister';
	$RQ_ACTION_VALUE_HOTLINE_CLOSE = 'hotlineclose';
	
	$RQ_ACTION_VALUE_CONSUMABLES = 'consumables';
	
	$RQ_MODE_VALUE_SELECT = 'select';

	$RQ_ACTION_NAME = 'action';

	$RQ_MODE_NAME = 'm';

	$RQ_SUBSUBSUBACTION_NAME = 'subsubsubaction';
	$RQ_SUBSUBACTION_NAME = 'subsubaction';
	$RQ_SUBACTION_NAME = 'subaction';
	$RQ_FLAG_VALUE_COMPLETE = 'complete';
	$RQ_FLAG_NAME = 'f';
	$RQ_PAGE_NAME = 'p';
	$RQ_PAGE_NAME2 = 'p2';
	$RQ_PAGE_NAME3 = 'p3';
	$RQ_SORT_NAME = 's';
	$RQ_SORT_NAME2 = 's2';
	$RQ_SORT_NAME3 = 's3';
	$RQ_LETTER_NAME = 'l';
	$RQ_LETTER_NAME2 = 'l2';
	$RQ_LETTER_NAME3 = 'l3';

	$POST_FLAG_NAME = 'flag';
	$POST_FLAG_VALUE_COMPLETE = 'complete';
	$POST_FLAG_VALUE_PRINT = 'print';
	$POST_RADIOINDEX_NAME = 'radioindex';
	$POST_CHECKBOX_NAME = 'checkindex';
	$POST_CHECKBOX_ARRAY_NAME = $POST_CHECKBOX_NAME.'[]';
	$POST_MAINFORM_NAME = 'mainform';	
	$POST_HIDDENBOX_NAME = 'hiddenindex';
	$POST_HIDDENBOX_ARRAY_NAME = $POST_HIDDENBOX_NAME.'[]';
	$POST_HIDDENVALUE_NAME = 'hiddenvalue';
	$POST_HIDDENVALUE_ARRAY_NAME = $POST_HIDDENVALUE_NAME.'[]';
	
	
	
	$SESSION_UID_NAME = 'uid';
	$SESSION_BID_NAME = 'bid';
	$SESSION_RESTORE_NAME = 'restore';
	$SESSION_RESTORE2_NAME = 'restore2';
	$SESSION_SET_NAME = 'set';
	$SESSION_MSGBOX_NAME = 'msgbox';
	$SESSION_BACKURL_NAME = 'backurl';
	$SESSION_BACKINDEX_NAME = 'backindex';
	$SESSION_PAGESCROLL_NAME = 'pagescroll';
	$SESSION_PAGEGOTO_NAME = 'pagegoto';

	$SESSION_VISITER_NAME = 'visiter';
	$SESSION_VISITER_VALUE_COPY = 'copy';
	$SESSION_VISITER_VALUE_SURNAME = 'surname';
	$SESSION_VISITER_VALUE_NAME = 'name';
	$SESSION_VISITER_VALUE_PATRONYMIC = 'patronymic';
	$SESSION_VISITER_VALUE_BIRTHDATE = 'birthdate';
	$SESSION_VISITER_VALUE_BIRTHPLACE = 'birthplace';
	$SESSION_VISITER_VALUE_IDENTDOCUMENT_TYPE = 'identdocumenttype';
	$SESSION_VISITER_VALUE_IDENTDOCUMENT_SERIA = 'identdocumentseria';
	$SESSION_VISITER_VALUE_IDENTDOCUMENT_NUMBER = 'identdocumentnumber';
	$SESSION_VISITER_VALUE_IDENTDOCUMENT_DATE = 'identdocumentdate';
	$SESSION_VISITER_VALUE_IDENTDOCUMENT_PLACE = 'identdocumentplace';
	$SESSION_VISITER_VALUE_INN = 'inn';
	$SESSION_VISITER_VALUE_SNILS = 'snils';
	$SESSION_VISITER_VALUE_SEX_TYPE = 'sextype';
	
	$SESSION_LIFESITUATION_NAME = 'lifesituation';
	$SESSION_LIFESITUATION_VALUE_COPY = 'copy';	
	
	$SESSION_INDEX_NAME = 'index';
	$SESSION_INDEX_VALUE_LIFESITUATION = 'index_lifesituation';
	$SESSION_INDEX_VALUE_REQUEST = 'index_request';
	$SESSION_INDEX_VALUE_SERVICEOFDOCUMENT = 'index_serviceofdocument';
	$SESSION_INDEX_VALUE_LIFESITUATION_EDIT = 'index_lifesituation_edit';
	$SESSION_INDEX_VALUE_LIFESITUATION_COPY = 'index_lifesituation_copy';
	
	
	$SESSION_MSGBOX_VALUE_SHOW = 'show';
	$SESSION_MSGBOX_VALUE_TEXT = 'text';
	$SESSION_MSGBOX_VALUE_FLAG = 'flag';
	$SESSION_MSGBOX_VALUE_SIZE = 'size';
	$SESSION_MSGBOX_VALUE_TYPE = 'type';
	$SESSION_MSGBOX_VALUE_LINK_B = 'link_b';
	$SESSION_MSGBOX_VALUE_LINK_A = 'link_a';

	$SESSION_IMPORT_VISITER_SET_NAME = 'import_visiter_set';

	$ACCESS_FULL = 1;
	$ACCESS_LOW = 2;
	$ACCESS_NO = 0;

	$WEBCAMERA_PREVIEW_WIDTH = 320;
	$WEBCAMERA_PREVIEW_HEIGHT = 320;

	$MESSAGE_PREVIEW_IMAGE_WIDTH = 45;
	$MESSAGE_PREVIEW_IMAGE_HEIGHT = 45;	
	
	$MSGBOX_FLAG_VALUE_NONE = 0;
	$MSGBOX_FLAG_VALUE_WARNING = 1;
	$MSGBOX_FLAG_VALUE_ERROR = 2;
	$MSGBOX_FLAG_VALUE_QUESTION = 3;
	$MSGBOX_FLAG_VALUE_INFORMATION = 4;

	$MSGBOX_SIZE_VALUE_16 = 0;
	$MSGBOX_SIZE_VALUE_32 = 1;
	$MSGBOX_SIZE_VALUE_48 = 2;

	$MSGBOX_TYPE_VALUE_OK = 0;
	$MSGBOX_TYPE_VALUE_YESNO = 1;
	
	$UNIT_PAGE_NAME = 'page.php';
	$UNIT_HINT_NAME = 'hint.php';
	$UNIT_SORT_NAME = 'sort.php';
	$UNIT_LETTER_NAME = 'letter.php';
	$UNIT_PRINT_NAME = 'print.php';
	$UNIT_INTERSERVICE_PROCESS_NAME = 'interservice_process.php';
	$UNIT_INTERSERVICE_RESULT_NAME = 'interservice_result.php';
	$UNIT_UNZIP_NAME = 'unzip.php';
	
	$UNIT_SIMPLE_INTERFACE_SUFFIX = '_simple';
	$UNIT_FORM_SUFFIX = '_form';
	$UNIT_LIST_SUFFIX = $UNIT_FORM_SUFFIX.'_list.php';
	$UNIT_FORM_CREATE_SUFFIX = $UNIT_FORM_SUFFIX.'_create.php';
	$UNIT_FORM_CHANGE_SUFFIX = $UNIT_FORM_SUFFIX.'_change.php';
	$UNIT_FORM_SELECT_SUFFIX = $UNIT_FORM_SUFFIX.'_select.php';
	$UNIT_VIEW_SUFFIX = '_view.php';
	$UNIT_FORM_VIEW_SUFFIX = $UNIT_FORM_SUFFIX.$UNIT_VIEW_SUFFIX;
	$UNIT_PRINT_SUFFIX = '_print.php';
	$UNIT_SQL_SUFFIX = '_sql';
	$UNIT_DATA_SUFFIX = '_data';
	$UNIT_SQL_PRINT_SUFFIX = $UNIT_SQL_SUFFIX.$UNIT_PRINT_SUFFIX;
	$UNIT_DATA_VIEW_SUFFIX = $UNIT_DATA_SUFFIX.$UNIT_VIEW_SUFFIX;
	$UNIT_SQL_VIEW_SUFFIX = $UNIT_SQL_SUFFIX.$UNIT_VIEW_SUFFIX;

	$UNIT_EQUIPMENT_SQL_PRINT_NAME = 'equipment'.$UNIT_SQL_PRINT_SUFFIX;	
	$UNIT_EQUIPMENTOFDIVISION_SQL_PRINT_NAME = 'equipmentofdivision'.$UNIT_SQL_PRINT_SUFFIX;
	$UNIT_SERVICECENTER_SQL_PRINT_NAME = 'servicecenter'.$UNIT_SQL_PRINT_SUFFIX;
	
	$UNIT_MFCOFFICE_SQL_PRINT_NAME = 'mfcoffice'.$UNIT_SQL_PRINT_SUFFIX;
	$UNIT_REQUEST_SQL_PRINT_NAME = 'request'.$UNIT_SQL_PRINT_SUFFIX;
	$UNIT_LIST_CONSUMABLES_SQL_PRINT_NAME = 'listconsumables'.$UNIT_SQL_PRINT_SUFFIX;
	$UNIT_REQUEST_SQL2_PRINT_NAME = 'request'.$UNIT_SQL_SUFFIX.'2'.$UNIT_PRINT_SUFFIX;
	$UNIT_MFC_SQL_PRINT_NAME = 'mfc'.$UNIT_SQL_PRINT_SUFFIX;
	$UNIT_REQUEST_FORM_PRINT_NAME = 'request'.$UNIT_FORM_SUFFIX.$UNIT_PRINT_SUFFIX;
	$UNIT_LIST_CONSUMABLES_FORM_PRINT_NAME = 'listconsumables'.$UNIT_FORM_SUFFIX.$UNIT_PRINT_SUFFIX;
	$UNIT_REQUESTOFDOCUMENT_SQL_PRINT_NAME = 'request_document'.$UNIT_SQL_PRINT_SUFFIX;
	$UNIT_REQUEST_REPAIR_FORM_PRINT_NAME = 'request_repair'.$UNIT_FORM_SUFFIX.$UNIT_PRINT_SUFFIX;
	$UNIT_TRAVEL_PAY_DOCUMENT_FORM_PRINT_NAME = 'travel__pay_document'.$UNIT_FORM_SUFFIX.$UNIT_PRINT_SUFFIX;
	
	$UNIT_TRAVEL_FORM_PRINT_NAME = 'travel'.$UNIT_FORM_SUFFIX.$UNIT_PRINT_SUFFIX;
	$UNIT_MAIL_FORM_PRINT_NAME = 'mail'.$UNIT_FORM_SUFFIX.$UNIT_PRINT_SUFFIX;
	$UNIT_SOFTWAREUPDATE_FORM_PRINT_NAME = 'softwareupdate'.$UNIT_FORM_SUFFIX.$UNIT_PRINT_SUFFIX;
	$UNIT_SERVICEEQUIPMENT_FORM_PRINT_NAME = 'serviceequipment'.$UNIT_FORM_SUFFIX.$UNIT_PRINT_SUFFIX;
	$UNIT_TIMESHEET_FORM_PRINT_NAME = 'timesheet'.$UNIT_FORM_SUFFIX.$UNIT_PRINT_SUFFIX;
	
	$UNIT_BRANCH_SQL_PRINT_NAME = 'branch'.$UNIT_SQL_PRINT_SUFFIX;
	$UNIT_DIVISION_SQL_PRINT_NAME = 'division'.$UNIT_SQL_PRINT_SUFFIX;
	$UNIT_GENERALDIVISION_SQL_PRINT_NAME = 'generaldivision'.$UNIT_SQL_PRINT_SUFFIX;
	$UNIT_REQUEST_REPAIR_SQL_PRINT_NAME = 'request_repair'.$UNIT_SQL_PRINT_SUFFIX;
	$UNIT_TRAVEL_PAY_DOCUMENT_SQL_PRINT_NAME = 'travel_pay_document'.$UNIT_SQL_PRINT_SUFFIX;
	$UNIT_USER_SQL_PRINT_NAME = 'user'.$UNIT_SQL_PRINT_SUFFIX;
	$UNIT_TRAVEL_SQL_PRINT_NAME = 'travel'.$UNIT_SQL_PRINT_SUFFIX;
	$UNIT_MAIL_SQL_PRINT_NAME = 'mail'.$UNIT_SQL_PRINT_SUFFIX;
	$UNIT_SOFTWAREUPDATE_SQL_PRINT_NAME = 'softwareupdate'.$UNIT_SQL_PRINT_SUFFIX;
	$UNIT_SERVICEEQUIPMENT_SQL_PRINT_NAME = 'serviceequipment'.$UNIT_SQL_PRINT_SUFFIX;
	$UNIT_TIMESHEET_SQL_PRINT_NAME = 'timesheet'.$UNIT_SQL_PRINT_SUFFIX;
	$UNIT_SERVICECENTER_SQL_PRINT_NAME = 'servicecenter'.$UNIT_SQL_PRINT_SUFFIX;
	
	$COOKIE_PLUGIN_NAME = 'plugin';
	$COOKIE_PLUGIN_VALUE_NONE = 'none';
	$COOKIE_BID_NAME = 'bid';
	
	$INI_CONFIG_FILE = 'config.ini';
	
	$INI_CONFIG_SECTION[0] = 'System';
	$INI_CONFIG_SECTION[1] = 'Common';
	$INI_CONFIG_SECTION[2] = 'News';
	$INI_CONFIG_SECTION[3] = 'FileShare';
	$INI_CONFIG_SECTION[4] = 'Classifier';
	$INI_CONFIG_SECTION[5] = 'List';
	$INI_CONFIG_SECTION[6] = 'Message';
	$INI_CONFIG_SECTION[7] = 'Request';
	$INI_CONFIG_SECTION[9] = 'Barcode';
	
	
	$INI_CONFIG_KEY[0][0] = 'ServiceMode';
	$INI_CONFIG_KEY[0][1] = 'ServiceModeMessage';
	$INI_CONFIG_KEY[0][2] = 'ShowBuildScriptTime';	
	$INI_CONFIG_KEY[0][6] = 'Show';
	$INI_CONFIG_KEY[0][7] = 'TerminateOfficeApplicationTimeOut';
	
	$INI_CONFIG_KEY[1][0] = 'RecordPerPageCount';
	
	$INI_CONFIG_KEY[2][0] = 'RecordPerPageCount';
	$INI_CONFIG_KEY[2][1] = 'ActivePeriod';
	
	$INI_CONFIG_KEY[3][0] = 'RecordPerPageCount';
	$INI_CONFIG_KEY[3][1] = 'ActivePeriod';
	
	$INI_CONFIG_KEY[4][3] = 'WorkPostShowCoeff';
	
	$INI_CONFIG_KEY[5][0] = 'RecordPerPageCount';
	$INI_CONFIG_KEY[5][1] = 'LockPeriod';
	
	$INI_CONFIG_KEY[6][0] = 'RecordPerPageCount';
	$INI_CONFIG_KEY[6][1] = 'RecordPerCreatePageCount';
	$INI_CONFIG_KEY[6][2] = 'PhotoMaxWidth';
	$INI_CONFIG_KEY[6][3] = 'PhotoMaxHeight';	
	
	$INI_CONFIG_KEY[7][0] = 'RecordPerPageCount';
	
	$INI_CONFIG_KEY[9][0] = 'Scale';
	$INI_CONFIG_KEY[9][1] = 'Height';
	$INI_CONFIG_KEY[9][2] = 'FontScale';
	
	//BigBlueButtonDettings
	define("CONFIG_SECURITY_SALT", "48");
	define("CONFIG_SERVER_BASE_URL_INNER", "http://bbb.iac-analytics.ru/bigbluebutton/");
	define("CONFIG_SERVER_BASE_URL_OUTER", "http://bbb.iac-analytics.ru/bigbluebutton/");
?>