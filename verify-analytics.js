(function($) {
	var sitesArray = [
		'http://istocode.com',
		'http://www.craigslist.org/',
		'http://www.autoshopsolutions.com',
		'http://ellislab.com/codeigniter',
		'http://jquery.com/',
		'http://oreilly.com'
	];	

	var $progress = $('#progress');
	var $progressMessage = $progress.find('.msg');

	var $results = $('#results');
	var $results_content = $('#content');
	var $errors = $('#errors');

	var successNum = 0;
	var totalSites = sitesArray.length;

	var fragment = document.createDocumentFragment();
	var $fragment = $(fragment);

	var errMessage;

	// Here is where we loop through the array and send the requests to cURL
	function sendAjax() {
		$progressMessage.html('...loading...');

		var curlXhr,
		settings = {
			url: 'curl_site.php',
			dataType: 'json',
			data: { },
			cache: false
		};

		$.each(sitesArray, function(i, siteUrl) {
			// Site variables
			var itemContainer = document.createElement('textarea');
			var $textarea = $(itemContainer);

			// Ajax settings
			settings.data.site = siteUrl;
			curlXhr = $.ajax(settings); // Sends here

			// On success
			curlXhr.done(function(page) {
				if (page && page.head && page.head.scripts) {
					$textarea.val($.trim(page.head.scripts));

					$textarea.wrap('<div class="twrap" />')
					$textarea.parent('.twrap').prepend('<h3>'+siteUrl+'</h3>');

					// Checks if analytics is in the page's <head>
					if ($textarea.val().indexOf('_setAccount') != -1) {
						$textarea.removeClass().addClass('green');
						successNum += 1;
					} else {
						$textarea.removeClass().addClass('red');
					}

					$fragment.append($textarea.parent('.twrap'));
				}				
			});

			// On failure
			curlXhr.fail(function(jqXHR, textStatus, errorThrown) {
				errMessage = '<p> <b>' + sitesArray[i] + '</b>';
				errMessage += ' - This site totally failed (perhaps of a redirect). Error mesages:</p>';
				errMessage += '<p><b class="red">1. ' + textStatus + '</b><br>';
				errMessage += '<b class="red">2. ' + errorThrown + '</b></p>';

				$errors.append(errMessage);
			});			
		});
	}

	// Ajax complete callback
	function onCurlComplete() {
		$results_content.append(fragment);
		$results.fadeIn('slow');

		$progressMessage.hide().text('Task completed');
		$progress.find('.bar').hide();

		$progressMessage.fadeIn('slow');
	}

	// On all Ajax completes
	$results.ajaxStop(onCurlComplete);
	
	// Initialize here...
	$('#start').on('click', function (e) {
		e.preventDefault();

		// Reset
		$results_content.html('');
		$errors.html('');
		$results.fadeOut();

		$progress.find('div').fadeIn(); 

		$progress.fadeIn('slow', function() {
			window.setTimeout(function() {
				sendAjax();
			}, 1200);
		});
	});
}(jQuery));