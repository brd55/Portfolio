        //takes value of speed slider and scales it to a millisecond delay
			var speedScale = d3.scale.linear()
				.domain([0,200])
				.range([400, 4]);
			//variable for setTimeout of play feature
			var forward;
		//change in date
        function setDate(ind){
			//updates day text
            $("#selectedDate").text(orbitArr[ind][0].date.toDateString());
			//sets the max of #times to be the number of times for the selected day -1
            $("#times").attr("max", orbitArr[ind].length - 1);
			//updates current to the first time of the selected day
            current = orbitArr[ind][0];
            // day is used as the lower bound for tweet displays.
            day.date = current.date;
			//updates time text
            $("#selectedTime").text(current.date.toTimeString());
			//Sets times slider to its min value
            $("#times").val(0);
			if(show1Orbit){
				plotCurOrbit();
			}
			else{
				plotDayTrace();
			}
        }
        //change in time
        function setTime(ind){
			var temp = current;
			//updates current to the corresponding entry
            current = orbitArr[Number($('#days').val())][ind];
			//updates time text
            $("#selectedTime").text(current.date.toTimeString());
			if(show1Orbit && (temp.id != current.id)) {	
				plotCurOrbit();
			}
			else{
				moveCircle();
			}
        }
		//implementation of play feature
		function moveForward(){
            document.getElementById("about").style.display = 'none';
			
			var temp = current;
			//toggles buttons
			$('#play').hide();
			$('#pause').show();
			//case if not at end of day
			if($('#times').val() != $('#times').attr('max')){
				//increments times slider
				document.getElementById("times").stepUp();
				var ind = $('#times').val()
				current = orbitArr[Number($('#days').val())][ind];
				$("#selectedTime").text(current.date.toTimeString());
				if(show1Orbit && (temp.id != current.id)) {	
					plotCurOrbit();
				}
				else{
					moveCircle();
				}
				tweetHandler.serveTweets(current);
                // // console.log('plotting');
                tweetHandler.plotTweets();
				//sets next instance of moveForward to happen after the time indicated by the speed slider
				forward=setTimeout('moveForward()', speedScale($('#speed').val()));
			}
			//case if end of day but not last day
			else if($('#days').val() != $('#days').attr('max')){
				document.getElementById("days").stepUp();
				//increments days slider
				var ind = $('#days').val();
				$("#selectedDate").text(orbitArr[ind][0].date.toDateString());
				$("#times").attr("max", orbitArr[ind].length - 1);
				current = orbitArr[ind][0];
				$("#selectedTime").text(current.date.toTimeString());
				$("#times").val(0);
				if(show1Orbit){
					plotCurOrbit();
				}
				else{
					plotDayTrace();
				}
				tweetHandler.serveTweets(current);
                // // console.log('plotting');
                tweetHandler.plotTweets();
				//sets next instance of moveForward to happen after the time indicated by the speed slider
				forward=setTimeout('moveForward()', speedScale($('#speed').val()));
			}
		}
		//pauses/stops the play function
		function pause(forward) {
			//toggles buttons
			$('#pause').hide();
			$('#play').show();
			//prevents moveForward from being called until the play button is pressed again
			clearTimeout(forward);
		}