<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<title>Test API</title>
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Lato:400,300' rel='stylesheet' type='text/css'>
    <link rel="icon" href="http://icons.iconarchive.com/icons/musett/dragon-ballz/256/Dragon-Ball-icon.png">
	</head>
  <body>
            <h1>Test webservice</h1>

            <hr/>

			<p>Afficher scores</p>
			<form class="formulaire" action="#" method="post">
				limit: <input class="limit" type="text" name="limit" value="0" placeholder="limit">
				offset: <input class="offset" type="text" name="offset" value="10" placeholder="offset">
				<select class='select-period'>
					<option value="allTime">allTime</option>
					<option value="currentWeek">currentWeek</option>
				</select>
				<button class="trigger_event" display="scores" called-method='getHighScores'>Get scores</button>
			</form>
            <div class="results" id="scores"></div>

            <br/><br/><br/>

			<p>Sauvegarder score</p>
			<form class="formulaire" action="#" method="post">
				<input class="player_name" type="text" name="player_name" value="" placeholder="Name">
				<input class="player_score" type="text" name="player_score" value="" placeholder="Score">
                <button class="trigger_event" display="update_results" called-method='saveScore'>Save Score</button>
            </form>
            <div class="results" id="update_results"></div>

            <br/><br/>

	       <script type="text/javascript" src="jquery-1.11.3.min.js"></script>
	    	<script type="text/javascript" src="main.js"></script>
	    </body>
</html>
