 

 var RadarChart = {
    draw: function (id, d, options, legend) {
                
      	var size = $("g").length;
      	//for(var z = 0; z < size; z++){
        var g = d3.select(id);

        var cfg = {
          radius: 5,
          w: 600,
          h: 600,
          factor: 1,
          factorLegend: .85,
          levels: 3,
          maxValue: 0,
          radians: 2 * Math.PI,
          opacityArea: 0.5,
          ToRight: 5,
          TranslateX: 80,
          TranslateY: 30,
          ExtraWidthX: 100,
          ExtraWidthY: 100,
          color: d3.scale.category10()
        };
        
        if('undefined' !== typeof options){
          for(var i in options){
            if('undefined' !== typeof options[i]){
              cfg[i] = options[i];
            }
          }
        }

        cfg.maxValue = Math.max(cfg.maxValue, d3.max(d, function(i){
            return d3.max(i.map(function(o){
              return o.value;
            }))
          }));
        
        var allAxis = (d[0].map(function(i, j){return i.axis}));
        var total = allAxis.length;
        var radius = cfg.factor*Math.min(cfg.w/2, cfg.h/2);
        var Format = d3.format('%');
        
        
        
        var circleAttributes = g.attr("cx", 500).attr("cy", 500);

        var tooltip;

        //console.log(g);
        // Circular segments for the levels
        for(var j=0; j<cfg.levels-1; j++){
           var levelFactor = cfg.factor*radius*((j+1)/cfg.levels);
          g.selectAll(".levels")
           .data(allAxis)
           .enter()
           .append("svg:line")
           .attr("x1", function(d, i){return levelFactor*(cfg.factor*Math.sin(i*cfg.radians/total));})
           .attr("y1", function(d, i){return levelFactor*(cfg.factor*Math.cos(i*cfg.radians/total));})
           .attr("x2", function(d, i){return levelFactor*(cfg.factor*Math.sin((i+1)*cfg.radians/total));})
           .attr("y2", function(d, i){return levelFactor*(cfg.factor*Math.cos((i+1)*cfg.radians/total));})
           .attr("class", "line")
           .style("stroke", "grey")
           .style("stroke-opacity", "0.75")
           .style("stroke-width", "0.3px");
      
        }

        series = 0;

        var axis = g.selectAll(".axis")
            .data(allAxis)
            .enter()
            .append("g")
            .attr("class", "axis");

        axis.append("line")
          .attr("x1", 0)
          .attr("y1", 0)
          .attr("x2", function(d, i){return radius*(cfg.factor*Math.sin(i*cfg.radians/total));})
          .attr("y2", function(d, i){return radius*(cfg.factor*Math.cos(i*cfg.radians/total));})
          .attr("class", "line")
          .style("stroke", "grey")
          .style("stroke-width", "1px");

        if (legend){
          axis.append("text")
            .attr("class", "legend")
            .text(function(d){return d})
            .style("font-family", "sans-serif")
            .style("font-size", "10px")
            .attr("text-anchor", "middle")
            .attr("dy", "1.5em")
            .attr("transform", function(d, i){return "translate(0, -12)"})
            .attr("x", function(d, i){return 1.9*cfg.w/2*(cfg.factorLegend*Math.sin(i*cfg.radians/total))-cfg.w/4*Math.sin(i*cfg.radians/total);})
            .attr("y", function(d, i){return 1.25*cfg.h/2*(Math.cos(i*cfg.radians/total))-cfg.h/10*Math.cos(i*cfg.radians/total);});
        }

       
        d.forEach(function(y, x){
          dataValues = [];
          g.selectAll(".nodes")
          .data(y, function(j, i){
            dataValues.push([
            .9*cfg.w/2*((parseFloat(Math.max(j.value, 0))/cfg.maxValue)*cfg.factor*Math.sin(i*cfg.radians/total)), 
            .9*cfg.h/2*((parseFloat(Math.max(j.value, 0))/cfg.maxValue)*cfg.factor*Math.cos(i*cfg.radians/total))
            ]);
          });
          dataValues.push(dataValues[0]);
          g.selectAll(".area")
                 .data([dataValues])
                 .enter()
                 .append("polygon")
                 .attr("class", "radar-chart-serie"+series)
                 .style("stroke-width", "2px")
                 .style("stroke", "white")
                 .attr("points",function(d) {
                   var str="";
                   for(var pti=0;pti<d.length;pti++){
                     str=str+d[pti][0]+","+d[pti][1]+" ";
                   }
                   return str;
                  })
                 .style("fill", "white")
                 .style("fill-opacity", 0.4);
          series++;
        });
        series=0;


        d.forEach(function(y, x){
          g.selectAll(".nodes")
          .data(y).enter()
          .append("svg:circle")
          .attr("class", "radar-chart-serie"+series)
          .attr('r', cfg.radius)
          .attr("alt", function(j){return Math.max(j.value, 0)})
          .attr("cx", function(j, i){
            dataValues.push([
            .9*cfg.w/2*((parseFloat(Math.max(j.value, 0))/cfg.maxValue)*cfg.factor*Math.sin(i*cfg.radians/total)), 
          ]);
          return .9*cfg.w/2*((Math.max(j.value, 0)/cfg.maxValue)*cfg.factor*Math.sin(i*cfg.radians/total));
          })
          .attr("cy", function(j, i){
            return .9*cfg.h/2*((Math.max(j.value, 0)/cfg.maxValue)*cfg.factor*Math.cos(i*cfg.radians/total));
          })
          .attr("data-id", function(j){return j.axis})
          .style("fill", "white").style("fill-opacity", .6)
          .append("svg:title")
          .text(function(j){return Math.max(j.value, 0)});

          series++;
        });
        //Tooltip
        /*tooltip = g.append('text')
               .style('opacity', 0)
               .style('font-family', 'sans-serif')
               .style('font-size', '13px');*/
      //}
        }};
