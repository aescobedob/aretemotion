<?php

	$post = '[{"id":"9","titulo":"Reporte 5","url":"http://google.com","set_id":"2","orden":"1"},{"id":"10","titulo":"Reporte 7.6","url":"http://google.com","set_id":"2","orden":"2"},{"id":"11","titulo":"Reporte 7.2","url":"http://google.com","set_id":"2","orden":"3"},{"id":"12","titulo":"Reporte 7.3","url":"http://google.com","set_id":"2","orden":"4"}]';
	$post = '[{"id":"9","titulo":"Reporte 5","url":"http://google.com","set_id":"2","orden":"1"},{"id":"10","titulo":"Reporte 7.7","url":"http://google.com","set_id":"2","orden":"2"},{"id":"11","titulo":"Reporte 7.2","url":"http://google.com","set_id":"2","orden":"3"},{"id":"12","titulo":"Reporte 7.3","url":"http://google.com","set_id":"2","orden":"4"}]';

	$p_update = json_decode($post, true);


	foreach($p_update as $p) {
		echo "id: " . $p['id'] . "<br>titulo: " . $p['titulo'] . " <br>url: " . $p['url'] . " <br>orden: " . $p['orden'];
		echo "<br><br>";
	}

?>