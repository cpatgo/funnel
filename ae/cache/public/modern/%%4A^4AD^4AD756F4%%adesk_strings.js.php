<?php /* Smarty version 2.6.12, created on 2016-07-08 15:27:04
         compiled from adesk_strings.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'i18n', 'adesk_strings.js', 1, false),array('modifier', 'js', 'adesk_strings.js', 1, false),array('modifier', 'alang', 'adesk_strings.js', 5, false),)), $this); ?>
var _charset = '<?php echo ((is_array($_tmp=((is_array($_tmp="utf-8")) ? $this->_run_mod_handler('i18n', true, $_tmp) : smarty_modifier_i18n($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var _twoletterlangid = '<?php echo ((is_array($_tmp=((is_array($_tmp='en')) ? $this->_run_mod_handler('i18n', true, $_tmp) : smarty_modifier_i18n($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

// define messages
var jsNothingSelected                = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not select any items. Please first check the box(es) next to the item(s) you wish to perform this operation on.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsNothingFound                   = '<?php echo ((is_array($_tmp=((is_array($_tmp="There are no items to choose from.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsNothingSelectedButContinue     = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not select any items, so this action will be performed on all items!")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
' + '\n\n' +
	'<?php echo ((is_array($_tmp=((is_array($_tmp="If you wish to select specific items first, then please cancel this action, then check the box(es) next to the item(s) you wish to perform this operation on.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
' + '\n\n' +
	'<?php echo ((is_array($_tmp=((is_array($_tmp="Are you sure you wish to continue?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
'
;



// global strings (used everywhere)

var replaceAlert1                    = '<?php echo ((is_array($_tmp=((is_array($_tmp="Please type in the phrase to replace...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var replaceAlert2                    = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not provide a phrase to replace. Type it in now...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var replaceAlert3                    = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not provide within which areas should this text be replaced.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
\n\n<?php echo ((is_array($_tmp=((is_array($_tmp="Would you like to replace text '%s' with text '%s' EVERYWHERE?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var versionsNotFound                 = '<?php echo ((is_array($_tmp=((is_array($_tmp="No versions found.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var jsAllItemsWillBeDeleted          = '<?php echo ((is_array($_tmp=((is_array($_tmp="All items will be deleted.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsAllItemsWillBeClosed           = '<?php echo ((is_array($_tmp=((is_array($_tmp="All items will be closed.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var jsTitleAdd                       = '<?php echo ((is_array($_tmp=((is_array($_tmp="&raquo; Add")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsTitleEdit                      = '<?php echo ((is_array($_tmp=((is_array($_tmp="&raquo; Edit")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsTitleDelete                    = '<?php echo ((is_array($_tmp=((is_array($_tmp="&raquo; Delete")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsTitleVersion                   = '<?php echo ((is_array($_tmp=((is_array($_tmp='Past Versions')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var jsEdit                           = '<?php echo ((is_array($_tmp=((is_array($_tmp='Edit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsDelete                         = '<?php echo ((is_array($_tmp=((is_array($_tmp='Delete')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsRemove                         = '<?php echo ((is_array($_tmp=((is_array($_tmp='Remove')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsAdd                            = '<?php echo ((is_array($_tmp=((is_array($_tmp='Add')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsUpdate                         = '<?php echo ((is_array($_tmp=((is_array($_tmp='Update')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsSave                           = '<?php echo ((is_array($_tmp=((is_array($_tmp='Save')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsImport                         = '<?php echo ((is_array($_tmp=((is_array($_tmp='Import')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsNext                           = '<?php echo ((is_array($_tmp=((is_array($_tmp='Next')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsOK                             = '<?php echo ((is_array($_tmp=((is_array($_tmp='OK')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsCancel                         = '<?php echo ((is_array($_tmp=((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsBack                           = '<?php echo ((is_array($_tmp=((is_array($_tmp='Back')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsContent                        = '<?php echo ((is_array($_tmp=((is_array($_tmp='Content')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsSetAsDefault                   = '<?php echo ((is_array($_tmp=((is_array($_tmp='Set as Default')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsHtmlEditor                     = '<?php echo ((is_array($_tmp=((is_array($_tmp='HTML Editor')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsTextEditor                     = '<?php echo ((is_array($_tmp=((is_array($_tmp='Text Editor')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsNotAvailable                   = '<?php echo ((is_array($_tmp=((is_array($_tmp="N/A")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsNone                           = '<?php echo ((is_array($_tmp=((is_array($_tmp='None')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsDefault                        = '<?php echo ((is_array($_tmp=((is_array($_tmp='Default')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsSubmit                         = '<?php echo ((is_array($_tmp=((is_array($_tmp='Submit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsReset                          = '<?php echo ((is_array($_tmp=((is_array($_tmp='Reset')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsBranding                       = '<?php echo ((is_array($_tmp=((is_array($_tmp='Branding')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsPrint                          = '<?php echo ((is_array($_tmp=((is_array($_tmp='Print')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsForward                        = '<?php echo ((is_array($_tmp=((is_array($_tmp='Forward')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsOptions                        = '<?php echo ((is_array($_tmp=((is_array($_tmp='Options')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsUnknown                        = '<?php echo ((is_array($_tmp=((is_array($_tmp='Unknown')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsCheck                          = '<?php echo ((is_array($_tmp=((is_array($_tmp='Check')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var jsOrderNotSaved                  = '<?php echo ((is_array($_tmp=((is_array($_tmp="You have not saved your order changes.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var jsSearching                      = '<?php echo ((is_array($_tmp=((is_array($_tmp="Searching...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsSorting                        = '<?php echo ((is_array($_tmp=((is_array($_tmp="Sorting...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsFiltering                      = '<?php echo ((is_array($_tmp=((is_array($_tmp="Filtering...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsSaving                         = '<?php echo ((is_array($_tmp=((is_array($_tmp="Saving...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsDeleting                       = '<?php echo ((is_array($_tmp=((is_array($_tmp="Deleting...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsRemoving                       = '<?php echo ((is_array($_tmp=((is_array($_tmp="Removing...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsReplacing                      = '<?php echo ((is_array($_tmp=((is_array($_tmp="Replacing...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsWorking                        = '<?php echo ((is_array($_tmp=((is_array($_tmp="Working...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsReverting                      = '<?php echo ((is_array($_tmp=((is_array($_tmp="Reverting...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsConnecting                     = '<?php echo ((is_array($_tmp=((is_array($_tmp="Connecting...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsImporting                      = '<?php echo ((is_array($_tmp=((is_array($_tmp="Importing...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsChecking                       = '<?php echo ((is_array($_tmp=((is_array($_tmp="Checking...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsInstalling                     = '<?php echo ((is_array($_tmp=((is_array($_tmp="Installing...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsUpdating                       = '<?php echo ((is_array($_tmp=((is_array($_tmp="Updating...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsResetting                      = '<?php echo ((is_array($_tmp=((is_array($_tmp="Resetting...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsSending                        = '<?php echo ((is_array($_tmp=((is_array($_tmp="Sending...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsStarting                       = '<?php echo ((is_array($_tmp=((is_array($_tmp="Starting...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsResuming                       = '<?php echo ((is_array($_tmp=((is_array($_tmp="Resuming...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsPausing                        = '<?php echo ((is_array($_tmp=((is_array($_tmp="Pausing...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsStopping                       = '<?php echo ((is_array($_tmp=((is_array($_tmp="Stopping...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsRestarting                     = '<?php echo ((is_array($_tmp=((is_array($_tmp="Restarting...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsEnabling                       = '<?php echo ((is_array($_tmp=((is_array($_tmp="Enabling...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsDisabling                      = '<?php echo ((is_array($_tmp=((is_array($_tmp="Disabling...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsFetching                       = '<?php echo ((is_array($_tmp=((is_array($_tmp="Fetching...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsCounting                       = '<?php echo ((is_array($_tmp=((is_array($_tmp="Counting...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsAdding                         = '<?php echo ((is_array($_tmp=((is_array($_tmp="Adding...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsApproving                      = '<?php echo ((is_array($_tmp=((is_array($_tmp="Approving...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var jsWait4AWhile                    = '<?php echo ((is_array($_tmp=((is_array($_tmp="Please wait, this can take a while.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var jsFilteringOn                    = '<?php echo ((is_array($_tmp=((is_array($_tmp='on')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsFilteringBetween               = '<?php echo ((is_array($_tmp=((is_array($_tmp='between')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsFilteringAnd                   = '<?php echo ((is_array($_tmp=((is_array($_tmp='and')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var jsOptionEdit                     = '<?php echo ((is_array($_tmp=((is_array($_tmp='Edit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsOptionDelete                   = '<?php echo ((is_array($_tmp=((is_array($_tmp='Delete')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsOptionView                     = '<?php echo ((is_array($_tmp=((is_array($_tmp='View')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsOptionReply                    = '<?php echo ((is_array($_tmp=((is_array($_tmp='Reply')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsOptionPublic                   = '<?php echo ((is_array($_tmp=((is_array($_tmp='Public')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsOptionRestore                  = '<?php echo ((is_array($_tmp=((is_array($_tmp='Restore')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsOptionPreview                  = '<?php echo ((is_array($_tmp=((is_array($_tmp='Preview')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsOptionDownload                 = '<?php echo ((is_array($_tmp=((is_array($_tmp='Download')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsOptionTest                     = '<?php echo ((is_array($_tmp=((is_array($_tmp='Test')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsOptionRun                      = '<?php echo ((is_array($_tmp=((is_array($_tmp='Run')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsOptionResume                   = '<?php echo ((is_array($_tmp=((is_array($_tmp='Resume')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsOptionContinue                 = '<?php echo ((is_array($_tmp=((is_array($_tmp='Continue')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsOptionRestart                  = '<?php echo ((is_array($_tmp=((is_array($_tmp='Restart')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsOptionPause                    = '<?php echo ((is_array($_tmp=((is_array($_tmp='Pause')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsOptionStop                     = '<?php echo ((is_array($_tmp=((is_array($_tmp='Stop')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsOptionReports                  = '<?php echo ((is_array($_tmp=((is_array($_tmp='Reports')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsOptionLog                      = '<?php echo ((is_array($_tmp=((is_array($_tmp='Log')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsOptionExport                   = '<?php echo ((is_array($_tmp=((is_array($_tmp='Export')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsOptionHTML                     = '<?php echo ((is_array($_tmp=((is_array($_tmp='HTML')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsOptionXML                      = '<?php echo ((is_array($_tmp=((is_array($_tmp='XML')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsOptionEnable                   = '<?php echo ((is_array($_tmp=((is_array($_tmp='Enable')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsOptionDisable                  = '<?php echo ((is_array($_tmp=((is_array($_tmp='Disable')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsOptionApprove                  = '<?php echo ((is_array($_tmp=((is_array($_tmp='Approve')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsOptionBlock                    = '<?php echo ((is_array($_tmp=((is_array($_tmp='Block')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var jsCreated                        = '<?php echo ((is_array($_tmp=((is_array($_tmp="Created: ")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsModified                       = '<?php echo ((is_array($_tmp=((is_array($_tmp="Modified: ")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var messageLP                        = '<?php echo ((is_array($_tmp=((is_array($_tmp="This will cancel all the changes you might have made.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';


var jsYes                            = '<?php echo ((is_array($_tmp=((is_array($_tmp='Yes')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsNo                             = '<?php echo ((is_array($_tmp=((is_array($_tmp='No')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var jsNoGroup                        = '<?php echo ((is_array($_tmp=((is_array($_tmp='No Group')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var decimalDelim										 = '<?php echo ((is_array($_tmp=((is_array($_tmp=".")) ? $this->_run_mod_handler('i18n', true, $_tmp) : smarty_modifier_i18n($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var commaDelim										   = '<?php echo ((is_array($_tmp=((is_array($_tmp=",")) ? $this->_run_mod_handler('i18n', true, $_tmp) : smarty_modifier_i18n($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';


// define strings
var jsAreYouSure                     = '<?php echo ((is_array($_tmp=((is_array($_tmp="Are You Sure?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsAPIfailed                      = '<?php echo ((is_array($_tmp=((is_array($_tmp="Server call failed for unknown reason. Please try your action again...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsLoading                        = '<?php echo ((is_array($_tmp=((is_array($_tmp="Loading...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsResult                         = '<?php echo ((is_array($_tmp=((is_array($_tmp="Changes Saved.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsError                          = '<?php echo ((is_array($_tmp=((is_array($_tmp="Error Occurred!")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

// Users
var jsUserDeleteMessage              = '<?php echo ((is_array($_tmp=((is_array($_tmp="This operation will delete %s (%s %s); are you sure you want to do this?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsUserDeleteGlobal               = '<?php echo ((is_array($_tmp=((is_array($_tmp="This operation will permanently delete this user.  If they are used in any other product that shares your authentication table, they will be deleted there as well.  Are you sure you want to do this?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsUserImport                     = '<?php echo ((is_array($_tmp=((is_array($_tmp='Import')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsUserDelete                     = '<?php echo ((is_array($_tmp=((is_array($_tmp='Delete')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsUserFormPasswordBlank          = '<?php echo ((is_array($_tmp=((is_array($_tmp="Password field cannot be left blank.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsUserFormPasswordMismatch       = '<?php echo ((is_array($_tmp=((is_array($_tmp="The two passwords do not match; you should re-type them and try again.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsUserFormValidationFail         = '<?php echo ((is_array($_tmp=((is_array($_tmp="To add or change a user, you must ensure that they have at least a username, password and group.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsUserFormValidationUserBadchars = '<?php echo ((is_array($_tmp=((is_array($_tmp="A username can only have lower-case letters and/or numbers, and may not have any spaces in it.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var jsUserFormMissingGroups          = '<?php echo ((is_array($_tmp=((is_array($_tmp="You need to select at least one group for this user to belong to.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';


// awebdesk assetsS

var syncEnterTitle                   = '<?php echo ((is_array($_tmp=((is_array($_tmp="Please type in the sync name...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var syncMissingTitle                 = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not provide a sync name. Type it in now...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var syncEnterUser                    = '<?php echo ((is_array($_tmp=((is_array($_tmp="Please type in the database username...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var syncMissingUser                  = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not provide a database username. Type it in now...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var syncEnterHost                    = '<?php echo ((is_array($_tmp=((is_array($_tmp="Please type in the database host name...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var syncMissingHost                  = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not provide a database host name. Type it in now...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var syncNothingChanged               = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not change anything. Do you wish to save this sync anyway?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var syncMissingRelid                 = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not select a sync destination. Please select it first...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var syncMissingTable                 = '<?php echo ((is_array($_tmp=((is_array($_tmp="Please select a table you wish to sync with first.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var syncMissingQuery                 = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter a proper (SELECT) database query. Please write down the query or select a table to sync...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var syncDuplicateMapping             = '<?php echo ((is_array($_tmp=((is_array($_tmp="You cannot sync the same field twice. Please select different destinations for every mapped field.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var syncMissingMapping               = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not map a required field %s. Please select a field in external database for it.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var syncCustomQuery                  = '<?php echo ((is_array($_tmp=((is_array($_tmp='Custom Query')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var syncShowTables                   = '<?php echo ((is_array($_tmp=((is_array($_tmp='Show Tables')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var syncHideTables                   = '<?php echo ((is_array($_tmp=((is_array($_tmp='Hide Tables')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var syncShowSynced                   = '<?php echo ((is_array($_tmp=((is_array($_tmp='Show Synced')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var syncHideSynced                   = '<?php echo ((is_array($_tmp=((is_array($_tmp='Hide Synced')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var syncShowFailed                   = '<?php echo ((is_array($_tmp=((is_array($_tmp='Show Failed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var syncHideFailed                   = '<?php echo ((is_array($_tmp=((is_array($_tmp='Hide Failed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var syncTitleRun                     = '<?php echo ((is_array($_tmp=((is_array($_tmp='Initiating Synchronization')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var syncTitleTest                    = '<?php echo ((is_array($_tmp=((is_array($_tmp='Testing Synchronization')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var syncStartRun                     = '<?php echo ((is_array($_tmp=((is_array($_tmp='Start Sync')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var syncStartTest                    = '<?php echo ((is_array($_tmp=((is_array($_tmp='Start Test')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';


var importMissingRelid               = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not select an import destination. Please select it first...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var importMissingText                = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter any data into a text box. Please add data first...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var importMissingFile                = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not upload a file to import. Please do that first...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var importMissingMapping             = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not map a required field %s. Please select a column for it.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var importDuplicateMapping           = '<?php echo ((is_array($_tmp=((is_array($_tmp="You cannot map the same field twice. Please select different destinations for every mapped field.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var importSuccessfulMapping          = '<?php echo ((is_array($_tmp=((is_array($_tmp="Mapping successful.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';


var installerFoundTables             = '<?php echo ((is_array($_tmp=((is_array($_tmp="Installer has found a version of %s already installed.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var installerFoundTablesOptions      = '<?php echo ((is_array($_tmp=((is_array($_tmp="\n\nYour options are:\n1) Please enter a different database information and click NEXT.\n2) Move/Backup your previous database data and click NEXT\n3) Install can remove your old installation for you.\n\n\nDo you want installer to remove found installation?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var installerRemoveTablesConfirm     = '<?php echo ((is_array($_tmp=((is_array($_tmp="THIS WILL REMOVE TABLES FROM THE DATABASE!\n\n Are you sure you wish to continue?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var installerAuthTableMissing        = '<?php echo ((is_array($_tmp=((is_array($_tmp="Table `aweb_globalauth` was not found on this connection. Please choose another.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';



var strPersCustomFields              = '<?php echo ((is_array($_tmp=((is_array($_tmp='Custom Fields')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersGlobalFields              = '<?php echo ((is_array($_tmp=((is_array($_tmp='Global Custom Fields')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var strPersSystemTags                = '<?php echo ((is_array($_tmp=((is_array($_tmp='System Personalization Tags')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var syncConfDeleteSingle = '<?php echo ((is_array($_tmp=((is_array($_tmp="Are you sure you want to delete this sync job?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var syncConfDeleteMulti = '<?php echo ((is_array($_tmp=((is_array($_tmp="Are you sure you want to delete the following sync jobs?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';


var editorConfirmSwitch = '<?php echo ((is_array($_tmp=((is_array($_tmp="Do you wish to convert existing text to other format?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var editorPersonalizeTitle = '<?php echo ((is_array($_tmp=((is_array($_tmp='Insert Personalization')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var editorActiveRSSTitle = '<?php echo ((is_array($_tmp=((is_array($_tmp='Insert RSS Feed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var editorTemplateTitle = '<?php echo ((is_array($_tmp=((is_array($_tmp='Insert Template')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var editorConditionalTitle = '<?php echo ((is_array($_tmp=((is_array($_tmp='Insert Conditional Content')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var editorConditionalText = '<?php echo ((is_array($_tmp=((is_array($_tmp='Insert Content Here')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var editorConditionalElseText = '<?php echo ((is_array($_tmp=((is_array($_tmp='Insert Alternative Content Here')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';


var jsErrorMailerBarMessage = '<?php echo ((is_array($_tmp=((is_array($_tmp="Unexpected Error Occurred.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var jsAPIErrorAuthMessage = '<?php echo ((is_array($_tmp=((is_array($_tmp='You are not authorized to access this file')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';