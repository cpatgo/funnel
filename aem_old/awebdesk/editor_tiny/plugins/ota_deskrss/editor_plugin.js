// 1-2-All ActiveRSS plugin.
//
// Create a list box which, when you click any of the items in it, will insert that item into
// the editor at the cursor's present point.  If you haven't clicked onto the editor yet, the
// item will be inserted at the first column of the first row of the content.

(function() {
	tinymce.create('tinymce.plugins.OTA_ActiveRSS', {
		/*
		init : function(ed, url) {
			this.stp_ed = ed;
			ed.addCommand('mceOTA_ActiveRSS', function() {
			});
		},
		*/

		createControl: function(n, cm) {
			if ( n == "ota_deskrss" ) {
				var c = cm.createButton(
					'ota_deskrss',
					{
						title : editorActiveRSSTitle,
						image : 'images/editor_activerss.gif',
						icons : false,
						onclick: ac_editor_activerss_click
					}
				);
				// Return the new button instance
				return c;
			} else {
				return null;
			}
		},

		getInfo : function() {
			return {
				longname : '1-2-All ActiveRSS',
				author : 'Email Software, Inc.',
				authorurl : 'http://www.example.com/',
				infourl : '',
				version : "1.0.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('ota_deskrss', tinymce.plugins.OTA_ActiveRSS);
})();
