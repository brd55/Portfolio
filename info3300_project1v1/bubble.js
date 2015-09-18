
var BubbleChart = {
  make : function (root){
  var i = -1;
  var node = svg.selectAll(".node")
      .data(bubble.nodes({children: root})
    .filter(function(d) { return !d.children; }))
    .enter().append("g")
      .attr("class", "node")
      .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; })
    .attr("id", function(){
    i++;
    return "cereal" + i;
    });

  node.append("title")
      .text(function(d) { return d.name + ": " + format(d.value); });

  node.append("circle")
      .attr("r", function(d) { return d.r; })
      .style("fill", function(d) { return d.mfrC; });

  /*node.append("text")
      .attr("dy", "4.5em")
      .style("text-anchor", "middle")
      .style("font-weight", "bold")
      .text(function(d) { return d.name.substring(0, d.r / 3); });*/

  }
}