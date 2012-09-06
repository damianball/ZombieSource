var w = 940,
    h = 940,
    i = 0,
    duration = 500,
    root;

var tree = d3.layout.tree();
    tree.size([h, w - 160]);

var diagonal = d3.svg.diagonal()
    .projection(function(d) { return [d.y, d.x]; });

var vis = d3.select("#chart").append("svg:svg")
    .attr("width", w)
    .attr("height", h)
    .append("svg:g")
    .attr("transform", "translate(40,0)");

d3.json(json_path, function(json) {
    json.x0 = 800;
    json.y0 = 0;
    update(root = json);
    var height = widestBranch(root) * 80 + 100;
    tree.size([height, w - 160]);
    $('#chart svg').animate({height: height}, duration);
    update(root);
    $('image').tooltip();
});

function killRecurse(n){
    var k = 0;
    if(n.children != null){
        for(var i = 0; i < n.children.length; i++){
            k += 1 + killRecurse(n.children[i]);
        }
    }
    if(n._children != null){
        for(var i = 0; i < n._children.length; i++){
            k += 1 + killRecurse(n._children[i]);
        }
    }
    return k;
}

function displayKills(d) {
    var directKills = 0;
    if(d.children != null){
        directKills += d.children.length;
    }
    if(d._children != null){
        directKills += d._children.length;
    }
    return directKills + " direct kills, " + (killRecurse(d) - directKills) + " kills by descendants";
}

function widestBranch(rt){
    var hash = {};
    function recurse(node, depth){
        depth++;
        if(hash[depth] == undefined){
            hash[depth] = 0;
        }
        try{
            hash[depth] += node.children.length;
            for(var i = 0; i < node.children.length; i++){
                recurse(node.children[i], depth);
            }
        } catch(err){}
    }
    recurse(rt, 0);
    var mx = 0;
    for(var depth in hash){
        mx = Math.max(hash[depth], mx);
    }
    return mx;
}

function update(source) {

    // Compute the new tree layout.
    var nodes = tree.nodes(root).reverse();
    // Update the nodes…
    var node = vis.selectAll("g.node")
        .data(nodes, function(d) { return d.id || (d.id = ++i); })
        .attr("style", function(d) { return (d._children != null && d._children.length > 0) ? "font-weight: bold;" : "" });

    var nodeEnter = node.enter().append("svg:g")
        .attr("class", "node")
        .attr("transform", function(d) { return "translate(" + source.y0 + "," + source.x0 + ")"; });
    //.style("opacity", 1e-6);

    // Enter any new nodes at the parent's previous position.

    nodeEnter
        .append("svg:image")
        .attr("text-anchor", "middle")
        .attr("rel", "tooltip")
        .attr("title", displayKills)
        .attr("data-placement", "top")
        .attr("xlink:href", function(d) { return d.gravatar; })
        .attr("height", 30)
        .attr("width", 30)
        .attr("x", -15)
        .attr("y", -15)
        .on("click", click);


    nodeEnter.append("svg:text")
        .attr("y", 28)
        .attr("text-anchor", "middle")
        .text(function(d) { return d.name; });


    // Transition nodes to their new position.
    nodeEnter.transition()
        .duration(duration)
        .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; })
        .style("opacity", 1);

    node.transition()
        .duration(duration)
        .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; })
        .style("opacity", 1);

    node.exit().transition()
        .duration(duration)
        .attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
        .style("opacity", 1e-6)
        .remove();

    // Update the links…
    var link = vis.selectAll("path.link")
        .data(tree.links(nodes), function(d) { return d.target.id; });

    // Enter any new links at the parent's previous position.
    link.enter().insert("svg:path", "g")
        .attr("class", "link")
        .attr("d", function(d) {
            var o = {x: source.x0, y: source.y0};
            return diagonal({source: o, target: o});
        })
            .transition()
            .duration(duration)
            .attr("d", diagonal);

    // Transition links to their new position.
    link.transition()
        .duration(duration)
        .attr("d", diagonal);

    // Transition exiting nodes to the parent's new position.
    link.exit().transition()
        .duration(duration)
        .attr("d", function(d) {
            var o = {x: source.x, y: source.y};
            return diagonal({source: o, target: o});
        })
            .remove();

    // Stash the old positions for transition.
    nodes.forEach(function(d) {
        d.x0 = d.x;
        d.y0 = d.y;
    });
}

// Toggle children on click.
function click(d) {
    if (d.children) {
        d._children = d.children;
        d.children = null;
    } else {
        d.children = d._children;
        d._children = null;
    }
    var height = widestBranch(root) * 80 + 100;
    tree.size([height, w - 160]);
    update(d);
    $('#chart svg').animate({height: height}, duration);
    $('image').tooltip();
}


