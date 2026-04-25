( function () {
	function copyReport() {
		var report = document.querySelector( '[data-wp-care-report-markdown]' );
		var status = document.querySelector( '[data-wp-care-report-copy-status]' );

		if ( ! report ) {
			return;
		}

		function showStatus( message ) {
			if ( status ) {
				status.textContent = message;
			}
		}

		if ( navigator.clipboard && navigator.clipboard.writeText ) {
			navigator.clipboard.writeText( report.value ).then(
				function () {
					showStatus( 'Markdown report copied.' );
				},
				function () {
					fallbackCopy( report, showStatus );
				}
			);

			return;
		}

		fallbackCopy( report, showStatus );
	}

	function fallbackCopy( report, showStatus ) {
		report.focus();
		report.select();

		try {
			document.execCommand( 'copy' );
			showStatus( 'Markdown report copied.' );
		} catch ( error ) {
			showStatus( 'Copy failed. Select the markdown text manually.' );
		}
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		var button = document.querySelector( '[data-wp-care-report-copy]' );

		if ( button ) {
			button.addEventListener( 'click', copyReport );
		}
	} );
}() );
