
/**
 * Load new by ajax
 * @param {String} target
 */
function loadNews(target) {
	$.ajax({
		url: '/?r=main/getNews',
		type: 'GET',
		dataType: 'HTML',
		data: {newsTarget: target},
		error: function() {
			alert('error');
		},
		success: function(data) {
			alert(data);
		}
	});
}

/**
 * Click event on top links
 */
$('a.load-news').click(function(event) {
	event.preventDefault();
	
	loadNews($(this).data('target'));
	
	
	
	
});