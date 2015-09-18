function checkForEntry(tweet, active) {
    active.forEach(function(active_tweet) {
        if (active_tweet.id === tweet.id) {
            return true;
        }
    });
    return false;
}
 
function TweetHandler(tweets) {
    function setTweets() {
        returnArr = [];
        tweets.forEach(function(tweet) {
            returnArr.push(Tweet(tweet));
        });
        return returnArr;
    }
    return {
        tweets : setTweets(),
 
        // Tweet[], The tweets currently on the map
        active : [],
 
        // Retrieves the tweets from midnight on a given day to the 
        // current timestamp of the slider
        serveTweets : function(current) {
            // Serves the last tweet that was before or at a given timestamp.
            // Called on change of var current.
            active = this.active;
            this.tweets.forEach(function(tweet) {
                ts = tweet.timestamp + (5 * 60 * 60 * 1000); // -5hr for EST
                if (Date.parse(day.date) <= ts && ts <= Date.parse(current.date)) {
                    if (!checkForEntry(tweet, active)) {
                        t = Tweet(tweet);
                        this.active.push(t);
                    }
                }
            });
        },
 
        // Plot all of the "active" tweets
        plotTweets : function() {
            if (this.active && this.active.length > 0) {
                this.active[this.active.length - 1].plotTweet(svg);
            }
        },

        // Remove all active tweets from view
        removeTweets : function() {
            this.active.forEach(function(tweet) {
                tweet.hideTweet();
            });
            this.active = [];
        },
    }
}
 
function Tweet(tweet, map) {
    return {
        id          : tweet.id,
        timestamp   : tweet.timestamp,
        html        : tweet.html,
        coordinates : tweet.coordinates,
        showing     : false,
        plotTweet   : function(map) {
            this.hideTweet();
            document.getElementById("displayTweet").innerHTML = this.html;
            twttr.widgets.load();
        },

        showTweet : function() {
            if (!this.showing) {
                this.showing = true;
                // document.getElementById("about").style.display = 'none';
                document.getElementById("displayTweet").innerHTML = this.html;
                twttr.widgets.load();
            }
        },

        hideTweet : function() {
            // document.getElementById("about").style.display = 'block';
            document.getElementById("displayTweet").innerHTML = "";
            this.showing = false;
        },
    }
}
