//in operator by marco
function whereIn(varString, varArray){
    var result = false;
    $.each(varArray, function(key, arr) {
        if(arr == varString){
            result = true;
        }
    });
    return result;
}

//unique json by marco
function uniqueJsonArray(jsonData, column){
    var lookup = {};
    var items = jsonData;
    var result = [];

    for (var item, i = 0; item = items[i++];) {
        var name = item[column];

        if (!(name in lookup)) {
            lookup[name] = 1;
            result.push(item);
        }
    }
    return result;
}

//by marco
function objectPluck(objects, field) {
    var result = [];
    for (i in objects) {
        result.push(objects[i][field]);
    }
    return result;
}


function objectSum(objects, field) {
    var arr = [];
    for (i in objects) {
        arr.push(objects[i][field]);
    }
    var result = arr.reduce((a, b) => a + b, 0);
    return result;
}



/* translates json array to html table format string. by marco
* requires jquery
* ex format: [{name:a},{name:b}]
* param1: json array
* param2: if you want to display a running number at first column
* param3: hide an specific column for some purpose
*/
function populateJsonArrayTable(jsonArray, withNumber, hiddenColumn){

    let thead = '';
    let tbody = '';

    $.each(jsonArray, function(key, value) {
        //get json array key
        thead = '';
        if(withNumber) thead += '<th>#</th>'; //if numbering enabled

        let columns = [];
        $.each(value, function(key, value){
            if(key != hiddenColumn){
                thead += '<th>' + key + '</th>';
                columns.push(key);
            }
        });

        let tcolumn = '';
        if(withNumber){ tcolumn += '<td>' + parseInt(key + 1) + '</td>'; } //if numbering enabled

        for(i in columns){
            tcolumn += '<td>' + value[columns[i]] + '</td>';
        }
        tbody += '<tr>' + tcolumn + '</tr>';
    });



    if(tbody == ''){
        tbody = '<tr><td colspan="" style="text-align: center">No data available.</tr>'
    }

    return '<thead>' +
            '<tr>' +
            thead +
            '</tr>' +
        '</thead>' +
        '<tbody>' +
            tbody +
        '</tbody>';
}