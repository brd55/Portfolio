        var show1Orbit = true;
        //plot current ISS position
        var plotCircle = function(){
            $("#iss").remove();
            svg.append("svg:image")
                .attr("x", projection(current.coords)[0] -33)
                .attr("y", projection(current.coords)[1] -18)
                .attr('width', 66)
                .attr('height', 36)
                .attr("id", "iss")
                .attr('xlink:href', "images/iss.gif");
            };
		//moves the circle and image representing the current position of the iss
        function moveCircle() {
            svg.select('#iss')
                .attr("x", projection(current.coords)[0] -33)
                .attr("y", projection(current.coords)[1] -18);

        }
        //plot current ISS orbit trace for current day
        var plotDayTrace = function(){
            $(".dayCircle").remove();
            svg.selectAll('.dayCircle')
                .data(orbitArr[$('#days').val()])
                .enter()
                .append("circle")
                .attr("r", 2)
                .attr("class", "dayCircle")
                .attr("transform", function(d) { 
                    return "translate(" + projection(d.coords) + ")";
                })
                .attr("data-coords", function(d){
                    return d.coords;
                })
                .style("fill", 'red');
            plotCircle();
        };
        //plot current ISS orbit trace for current orbit
        var plotCurOrbit = function(){
            $(".curOrbitCircle").remove();
            svg.selectAll('.curOrbitCircle')
                .data(orbitData[current.id].orbit.coordinates)
                .enter()
                .append("circle")
                .attr("r", 2)
                .attr("class", "curOrbitCircle")
                .attr("transform", function(d) { 
                    return "translate(" + projection(d) + ")";
                })
                .attr("data-coords", function(d){
                    return d;
                })
                .style("fill", 'red');
            plotCircle();
        };
		//Shows all orbits for the day
        function showDay() {
            show1Orbit = false;
            $('#showDay').hide();
            $('#showOrbits').show();
            $(".curOrbitCircle").remove();
            plotDayTrace();
        }
		//Shows only the current orbit
        function showOrbit() {
            show1Orbit = true;
            $('#showOrbits').hide();
            $('#showDay').show();
            $(".dayCircle").remove();
            plotCurOrbit();
        }