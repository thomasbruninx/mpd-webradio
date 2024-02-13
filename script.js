const TemplateStationListItem = ({ name, streamurl }) => 
`<li><a href="#action" data-action="set_station" data-stream-url="${streamurl}">${name}</a></li>`;

const TemplateStatusTableRow = ({ key, value }) => 
`<tr><th scope="row">${key}</th><td>${value}</td></tr>`;

const TemplateLogTableRow = ({ timestamp, action }) => 
`<tr><td>${timestamp}</td><td>${action}</td></tr>`;

var debug_mode = false;
var refreshTimeout = 5000
const api_url = "./api.php";


// Process settings.json
function loadSettings(json) {
	// Load radio stations
	$('#ctrl-group-stations #station-list').html("");
	for(let i = 0; i < json.stations.length; i++) {
		if (debug_mode) { console.log(json.stations[i]); }
		$('#ctrl-group-stations #station-list')
			.append($([{name: json.stations[i].name, streamurl: json.stations[i].url}]
				.map(TemplateStationListItem)
				.join(''))
				.bind('click', processAction));
	}

	refreshTimeout = json.refreshTimeout;
	if (debug_mode) { console.log('refreshTimeout = ' + refreshTimeout) }
}

// Call the API
function callAPI(action, parameters, callback) {

	api_data = {
		"action": action,
		"parameters": parameters
	}
	
	$.ajax({
		dataType: "json",
		method: "GET",
		url: api_url,
		data: api_data
	}).done(callback)
	.fail(function($xhr) {
		if (debug_mode) { 
			console.error("API call failed");
			console.error($xhr.responseJSON); 
		}
	});

	if (debug_mode) {
		$('#ctrl-debug-log table tbody')
				.append($([{timestamp: (new Date).toUTCString(), action: action + '(' + JSON.stringify(parameters) + ')'}]
				.map(TemplateLogTableRow)
				.join('')));
	};

}

// Refresh MPD status
function refreshDebugStatus() {
	callAPI('get_status', [], function(data) {
		console.log(data)
		if (debug_mode) {
			$('#ctrl-debug-status table tbody').html("");
			for (var key in data.content) {
				var value = data.content[key];
				$('#ctrl-debug-status table tbody')
					.append($([{key: key, value: value}]
					.map(TemplateStatusTableRow)
					.join('')));
			}
		} else {
			console.info('Enable debug mode to view MPD status on page')
		}
	});
}

// Generic debug function for processing API calls 
function debugCallback(data) {
	if (debug_mode) {
		console.log(data);
	} 
}

// Refresh stream and song information
function refreshPlayerInfo() {
	callAPI('get_songinfo', [], function(data) {
		if (debug_mode) {
			console.log(data);
		} 
		$('#ctrl-currently-playing .stream-name').html(data.content.streamurl);
		$('#ctrl-currently-playing .song-name').html(data.content.song);		
	});
}

// Change station 
function changeStation(streamurl) {
	callAPI('set_station', {"streamurl": streamurl}, function(data) {
		if (debug_mode) {
			console.log(data);
		} 
		refreshPlayerInfo();
	});
}

// Get volume
function getVolume() {
	callAPI('get_volume', [], function(data) {
		if (debug_mode) {
			console.log(data);
		} 
		$('#ctrl-radio-controls-volume input').val(parseInt(data.content));
	});
}

// Event handler for actions
function processAction(event) {
	let action = $(event.target).data("action");
	switch (action) {
		case "refresh_status":
			refreshDebugStatus();
			refreshPlayerInfo();
			break;
		case "set_station":
			let streamurl = $(event.target).data('stream-url');
			changeStation(streamurl);
			break;
		default:
			alert('unknown action');
			if (debug_mode) { console.warn('unknown action: ' + action) }
	}
	event.preventDefault();
}

// Document load
$(document).ready(function() {

	// Setup debug mode
	debug_mode = ($("meta[name='debug']").attr("content") == 'true');
	if (debug_mode) {
		refreshDebugStatus();
	}

	// Load settings.json from server
	fetch('./settings.json')
		.then((response) => response.json())
		.then((json) => { if (debug_mode) { console.log(json); } loadSettings(json); });

	// Process clicks on action anchors
	$('a[href="#action"]').bind('click', processAction);

	// Volume slider
	$('#ctrl-radio-controls-volume input').on('change', function(event) {
		let volume = $(event.target).val();

		if (debug_mode) { console.log("Volume slider: " + volume) }

		callAPI("set_volume", {"volume" : volume}, null);
	});

	// Refresh player info
	function refreshPlayerInfoTimeout() {
		refreshPlayerInfo();
		setTimeout(refreshPlayerInfoTimeout, refreshTimeout);
	}
	refreshPlayerInfoTimeout();

	getVolume();
	
});

