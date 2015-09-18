#Friends in Space Data

This archive contains the anonymized data of orbits, TLEs, and activities in Friends in Space.

All the timestamps are UTC in millis. All the coordinates are in the form [lng,lat].



##fisOrbits.json
This file contains all the orbits of the ISS around the earth from the 2014-11-24 03:00 UTC.

The file contains a JSON array structured as the following:

    {
    	"orbitId" : 0,  //the ID of the orbit
    	"start" : 1416796036000,  //the first timestamp on this orbit
    	"end" : 1416801796000, //the last timestamp of this orbit
    	"orbit" : { 
    		"coordinates" : [  // coordinates of each point of the orbit
    			[lng,lat],
    			[lng,lat],
    			...
    		],
    		"timestamps" : [  //timestamp in UTC millis of each orbit point
    			1416796036000, 
    			1416796096000,
    			...
    		],
    		 "altitudes" : [ // altitudes in meters of each orbit point
	    		 434.3063984588207, 
    			 434.4863779065154, 
    			 ...
    		]
    	}
    }

##fisTLE.json
This file contains all the TLE used to calculate the orbits from the first TLE available at 2014-11-24 03:00 UTC.

The file contains a JSON array structured as the following:

	{
		 "start" : 1416754479000, //the time this TLE was downloaded
		 "line1" : "1 25544U 98067A   14327.62128819  .00027616  00000-0  46569-3 0  4499", 
		 "line2" : "2 25544  51.6479  29.1490 0007009  60.3444  30.8455 15.51427777916069" 
	}
	


##fisOrbitsActivities.json
This file contains all the activities of the users on Friends in Space.

The file contains a JSON array structured as the following:

	{
		"orbitId" : 5,                  // the orbit id
		"userId" : "AnonimizedUserId",  //an anonimized userID
		"coordinates" : [               //coordinates of the user
			lng,
			lat
		],
		"onOrbit" : true,               //if the user was on orbit or not
	    "lastActivity" : 1416831148318, //the last activity timestamp of this user in this orbit
	    "type" : "user",				 //if it's a user or a guest
	    "earthGreetingsActivity" : 1416831145729,  //last hello earth timestamp
		"earthGreetings" : 15,				//number of hello earth on this orbit
		"samGreetingsActivity" : 1416830540505,        //best sam hello timestamp
		"samGreetingsDist" : 54,                       //best sam hello distance on a scale from 0  to 100
		"samGreetings" : 1,							//count hello sam
		"greetingLinks" : [                       //an array of created links between other users when doing hello earth
			{
				"startUserId" : "AnonimizedUserId2", //the user who started the link
				"endUserId" : "AnonimizedUserId",    //the user how answered the hello and created the link
				"started" : false  					//true if the current user started the link, false otherwise
			}
		]
	}