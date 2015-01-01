var dataset = dataset;

var createChart = function(selector,type,title,yAxisTitle,seriesName,dataset){
	this.selector = selector;
	this.type = type;
	this.title = title;
	this.yAxisTitle = yAxisTitle;
	this.seriesName = seriesName;
	this.dataset = dataset;

	var that = this;
	var categories = [];
	var data = [];

	var populate = function(){
		for (var i = 0; i < that.dataset.length; i++){
			categories.push(dataset[i]["name"]);
			data.push(dataset[i]["avg"]);
		}
	};

	var render = function(){
		$(that.selector).highcharts({
	        chart: {
            	type: that.type
       		},
	        title: {
            	text: that.title
        	},
	        xAxis: {
	        	categories: categories
	        },
	        yAxis: {
	        	title: {
	        		text: yAxisTitle
	        	}
	        },
	        series: [{
	            name: seriesName,
	            data: data
	        }]
    	});
    };

    populate();
	render();
}

createChart("#chart","column","Average City Retail Price","AVG Price","City",dataset);