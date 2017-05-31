/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.allowedContent = true;
	config.removeDialogTabs = 'link:upload;image:Upload';
	config.enterMode = CKEDITOR.ENTER_BR;
	// config.skin = 'bootstrapck';
	config.filebrowserImageUploadUrl = '/js/kcfinder/upload.php?type=images';
    config.filebrowserImageBrowseUrl = '/js/kcfinder/browse.php?type=images';
    config.filebrowserUploadUrl = '/js/kcfinder/upload.php?type=videos';
    config.filebrowserBrowseUrl = '/js/kcfinder/browse.php?type=videos';
    config.height = 450;
};
