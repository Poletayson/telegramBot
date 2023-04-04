// Show tooltips always even the stats are zero

Chart.pluginService.register({
    beforeRender: function(chart) {
      if (chart.config.options.showAllTooltips) {
        // create an array of tooltips
        // we can't use the chart tooltip because there is only one tooltip per chart
        chart.pluginTooltips = [];
        chart.config.data.datasets.forEach(function(dataset, i) {
          chart.getDatasetMeta(i).data.forEach(function(sector, j) {
            chart.pluginTooltips.push(new Chart.Tooltip({
              _chart: chart.chart,
              _chartInstance: chart,
              _data: chart.data,
              _options: chart.options.tooltips,
              _active: [sector]
            }, chart));
          });
        });
  
        // turn off normal tooltips
        chart.options.tooltips.enabled = false;
      }
    },
    afterDraw: function(chart, easing) {
      if (chart.config.options.showAllTooltips) {
        // we don't want the permanent tooltips to animate, so don't do anything till the animation runs atleast once
        if (!chart.allTooltipsOnce) {
          if (easing !== 1)
            return;
          chart.allTooltipsOnce = true;
        }
  
        // turn on tooltips
        chart.options.tooltips.enabled = true;
        Chart.helpers.each(chart.pluginTooltips, function(tooltip) {
          tooltip.initialize();
          tooltip.update();
          // we don't actually need this since we are not animating tooltips
          tooltip.pivot();
          tooltip.transition(easing).draw();
        });
        chart.options.tooltips.enabled = false;
      }
    }
  });

$('.js-dg1').each(function(i, el){

    var diagram = $(el);

    var d1 = new Chart(diagram, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [768, 4272],
                backgroundColor: [
                    '#FD5577',
                    '#7D7F9B',
                ]
            }],
            labels: [
                'не явилось, чел',
                'явилось, чел',
            ],
        },
        options: {
            showAllTooltips: true,
            legend: {
                display: true,
                labels: {
                    fontColor: '#616161',
                    fontSize: 17,
                    fontFamily: 'Lato, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Ubuntu, Arial, sans-serif',
                    padding: 20
                }
            },
            tooltips: {
                intersect: true,
                bodyFontSize: 14,
                bodyFontStyle: 'bold',
                xPadding: 12,
                yPadding: 12,
                callbacks: {
                    label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index],
                            valueTitle = data.labels[tooltipItem.index],
                            total = 0,
                            label;

                        for (var i = 0; i < data.datasets[tooltipItem.datasetIndex].data.length; i++)
                            total += data.datasets[tooltipItem.datasetIndex].data[i];

                        label = valueTitle + ': ' + value + ',  ' + ((value*100)/total).toFixed(2) + '%';

                        return label;

                    }
                }
            }
        }
        
    });

});

$('.js-dg2').each(function(i, el){

    var diagram = $(el);

    var d2 = new Chart(diagram, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [130, 637, 345],
                backgroundColor: [
                    '#FDAE55',
                    '#0FC789',
                    '#0FA9C7',
                ]
            }],
            labels: [
                'ЗНО верифицировано с нарушением срока',
                'отменено подозрений',
                'ЗНО верифицировано в срок',
            ],
        },
        options: {
            showAllTooltips: true,
            legend: {
                display: true,
                labels: {
                    fontColor: '#616161',
                    fontSize: 15,
                    fontFamily: 'Lato, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Ubuntu, Arial, sans-serif',
                    padding: 20
                }
            },
            tooltips: {
                intersect: true,
                bodyFontSize: 13,
                bodyFontStyle: 'bold',
                xPadding: 12,
                yPadding: 12,
                callbacks: {
                    label: function(tooltipItem, data) {
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index],
                            valueTitle = data.labels[tooltipItem.index],
                            total = 0,
                            label;

                        for (var i = 0; i < data.datasets[tooltipItem.datasetIndex].data.length; i++)
                            total += data.datasets[tooltipItem.datasetIndex].data[i];

                        label = valueTitle + ': ' + value + ',  ' + ((value*100)/total).toFixed(2) + '%';

                        return label;

                    }
                }
            },
        }
        
    });

});

