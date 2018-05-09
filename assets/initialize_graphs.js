
jQuery().ready(function() {
    jQuery("div[id^='chart1_data_']").each(function () {
        data = JSON.parse(jQuery(this).html());
        render(data);
    });
});

var charts = {}


function render(data) {
    var renderData = {};
    renderData['bindto'] = "#chart_" + data['unique_id'];
    renderData['data'] = {};
    renderData['data']['type'] = data['type'];




    if (data['type'] == 'gauge') {
        renderData['data']['columns'] = [['data', data['value']]];

        if (data['min'] || data['max'] || data['unit']) {
            renderData['gauge'] = {};

            if (data['min']) {
                renderData['gauge']['min'] = data['min'];
            }
            if (data['max']) {
                renderData['gauge']['max'] = data['max'];
            }
            if (data['unit']) {
                renderData['gauge']['label'] = {}
                renderData['gauge']['label']['format'] = function(value) { return value.toFixed(1) + " [" + data['unit'] + "]"; }
            }
        }
    } else {
        renderData['data']['columns'] = [['data1', 5,10], ['data2', 20,30]];
    }

    charts[data['unique_id']] = c3.generate(renderData);

    if (data['update_interval']) {
        update(data['update_interval']*1000, data['unique_id']);
    }
}


function update(interval, graph) {
    setTimeout(function() {
        charts[graph].load({
            // unload: true,
            columns: [['data', Math.random()*100]]
        });
        update(interval, graph);
    }, interval);
}






