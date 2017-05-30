<?php
defined('TYPO3_MODE') or die();

// Extend the <core:contentEditable> viewhelper by the one from EXT:frontend_editing
$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['core'][] = 'TYPO3\\CMS\\FrontendEditing\\ViewHelpers';

// Disable cHash check when browsing the frontend in frontend editing
if (TYPO3\CMS\Core\Utility\GeneralUtility::_GET('frontend_editing')) {
    $GLOBALS['TYPO3_CONF_VARS']['FE']['pageNotFoundOnCHashError'] = false;
}
/**
 * Hooks
 */
// Register the edit panel view
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/classes/class.frontendedit.php']['edit'] =
    \TYPO3\CMS\FrontendEditing\EditingPanel\FrontendEditingPanel::class;

// Hook to render toolbars
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-output']['frontend_editing'] =
    \TYPO3\CMS\FrontendEditing\Hook\FrontendEditingInitializationHook::class . '->main';

// Hook content object render. Check if column is empty
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClass']['CONTENT'] = [
    'CONTENT',
    \TYPO3\CMS\FrontendEditing\Hook\ContentObjectRendererHook::class
];

/**
 * Pre processors
 */
// Save core content element "Bullets"
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['FrontendEditing']['requestPreProcess']['frontend_editing-cobjbullets'] =
    \TYPO3\CMS\FrontendEditing\RequestPreProcess\CeBullets::class;

// Save core content element "Table"
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['FrontendEditing']['requestPreProcess']['frontend_editing-cobjtable'] =
    \TYPO3\CMS\FrontendEditing\RequestPreProcess\CeTable::class;

// Save headers, field header, will also affect field header_layout
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['FrontendEditing']['requestPreProcess']['frontend_editing-CeHeader'] =
    \TYPO3\CMS\FrontendEditing\RequestPreProcess\CeHeader::class;

// Add UserTsConfig settings
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:frontend_editing/Configuration/UserTsConfig/userTsConfig.ts">'
);

// Register BE user avatar provider on FE
if (TYPO3_MODE === 'FE') {
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['backend']['avatarProviders']['frontendEditingAvatarProvider'] = [
        'provider' => \TYPO3\CMS\FrontendEditing\Provider\FrontendEditingAvatarProvider::class
    ];
}
