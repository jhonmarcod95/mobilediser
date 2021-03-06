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
    var result = arr.reduce((a, b) => parseInt(a) + parseInt(b), 0);
    return result;
}

function dateTimeDifference(fromDateTime, toDateTime) {
    let ms = moment(toDateTime,"YYYY/MM/DD HH:mm a").diff(moment(fromDateTime,"YYYY/MM/DD HH:mm a"));
    if (ms < 0) ms = 0;
    return ms;
}

function getTimeStampHrs(timestamp){
    let d = moment.duration(timestamp);
    return Math.floor(d.asHours());

}

function getTimeStampMins(timestamp){
    return moment.utc(timestamp).format("mm");
}

function renderedText(rendered){
    let d = moment.duration(rendered);
    let hours = Math.floor(d.asHours());
    let minutes = moment.utc(rendered).format("mm");
    return hours + ' hrs ' + minutes + ' min';
}

function toBlankText(text) {
    if (typeof text == 'undefined' || text == null){
        return '';
    }
    return text;
}


function formSubmit(path, params, method) {
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);
    form.setAttribute("enctype", 'application/json');
    form.setAttribute("target", "_blank");

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    form.submit();
}

function showPageNavigation(data, prevButton, nextButton){
    var current_page = data.current_page;
    var last_page = data.last_page;
    var next_page_url = data.next_page_url;
    var prev_page_url = data.prev_page_url;
    var prev_page_button = '';
    var next_page_button = '';
    if(prev_page_url != null) prev_page_button = prevButton;
    if(next_page_url != null) next_page_button = nextButton;

    var page_nav = '' +
        '<div class="mailbox-controls pull-right">' +
        prev_page_button +
        '<span> Page ' + current_page + ' </span>' +
        '<span> of ' + last_page + ' </span>' +
        next_page_button +
        '</div>';

    var recordCount = 'Showing ' + data.data.length + ' of ' + data.total + ' entries';

    return '<div class="row"><div class="col-md-6">' + recordCount + '</div><div class="col-md-6">' + page_nav + '</div></div>';
}

/* translates json array to html table format string. by marco
* requires jquery
* ex format: [{name:a},{name:b}]
* param1: json array
* param2: if you want to display a running number at first column
* param3: hide an specific column for some purpose
*/
function populateJsonArrayTable(jsonArray, withNumber, hiddenColumn, footerCustom){

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
        footerCustom = '';
    }

    return '<thead>' +
            '<tr>' +
            thead +
            '</tr>' +
        '</thead>' +
        '<tbody>' +
            tbody +
        '</tbody>' +
        footerCustom;
}

function countJsonKeys(jsonArray) {
    let result = 0;
    $.each(jsonArray, function(key, value) {
        let columns = [];
        $.each(value, function(key, value){
            columns.push(key);
        });
        result = columns.length;
        return false;
    });
    return result;
}

function removeLastComma(value) {
    value = value.replace(/,\s*$/, "");
    return value;
}

function toTimeString(time){
    if(time != null) return moment('1995-12-30 ' + time).format('hh:mm a');
    return '';
}

function showSuccessAlert(text){
    var result = '<div class="alert bg-success alert-dismissible text-success small" role="alert">' + text + '<button type="button" class="close text-green" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    return result;
}

function showErrorAlert(data) {
    var errors = $.parseJSON(data.responseText);
    var errList = '';
    $.each(errors.errors, function (key, val) {
        errList += '<li>' + val + '</li>';
    });
    return '<div class="alert bg-danger text-danger"><button type="button" class="close text-red" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p><i class="fa fa-warning"></i><b style="margin-left: 6px">Error</b> ' + errors.message + '</p><ul class="small">' + errList + '</ul></div>';
}


function optionAll(){
    let $option = $("<option></option>");
    $option.val('%');
    $option.text('All');
    return $option;
}

function populateSelect(id, objs){
    for(let obj of objs){
        let $option = $("<option></option>");

        $option.val(obj[Object.keys(obj)[0]]);
        $option.text(obj[Object.keys(obj)[1]]);

        $("#" + id).append($option);
    }
}

function selectValues(id){
    let result = [];
    $('#' + id + " option").each(function()
    {
        if ($(this).val() !== '%'){
            result.push($(this).val());
        }

    });
    return result;
}

function arrayToSingleQuotes(array){ //ex: ['a', 'b', 'c'] => 'a','b','c'
    return array.map(arr => `'${arr}'`).join(',');
}