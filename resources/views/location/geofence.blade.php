<html>
<head>
@include('layouts.head')
<!-- NOTE: two libraries to load are comma-separated; otherwise last mention of the query string arg overwrites the previous -->
    <script type="text/javascript"
            src="http://maps.google.com/maps/api/js?key=AIzaSyCVhXP3qqWTbQnr-VtTdl0anZZJT3cP9Q0&sensor=false&v=3.21.5a&libraries=drawing&signed_in=true&libraries=places,drawing"></script>
    <script src="https://cdn.rawgit.com/googlemaps/v3-utility-library/master/infobox/src/infobox.js"></script>
    <style type="text/css">
        #map {
            height: 93%;
        }

        #panel {
            width: 300px;
            font-family: Arial, sans-serif;
            font-size: 13px;
            float: right;
            margin: 10px;
        }

        #color-palette {
            clear: both;
        }

        .color-button {
            width: 14px;
            height: 14px;
            font-size: 0;
            margin: 2px;
            float: left;
            cursor: pointer;
        }

        #delete-button {
            margin-top: 5px;
        }
    </style>
    <script type="text/javascript">
        var drawingManager;
        var selectedShape;
        var colors = ['#1E90FF', '#FF1493', '#32CD32', '#FF8C00', '#4B0082', '#CD5C5C'];
        var selectedColor;
        var colorButtons = {};

        var lastSelectedShape;
        var geofences = [];
        var labels = {};

        function showGeofences(){
            @foreach($customerGeofences as $customerGeofence)
            var geofence_{{ $customerGeofence->id }} = new google.maps.Circle({
                    strokeColor: '{{ $customerGeofence->color }}',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: '{{ $customerGeofence->color }}',
                    fillOpacity: 0.35,
                    editable: false,
                    map: map,
                    center: {lat: {{ $customerGeofence->geo_center_lat }}, lng: {{ $customerGeofence->geo_center_lng }}},
                    radius: {{ $customerGeofence->radius }},
                });

            //Geofence Click Event
            google.maps.event.addListener(geofence_{{ $customerGeofence->id }}, 'click', function () {
                clearSelection();
                selectedShape = geofence_{{ $customerGeofence->id }}; //set shape for editing
                lastSelectedShape = selectedShape; //click save changes without AOE focus
                geofence_{{ $customerGeofence->id }}.setEditable(true); //disable
                getCoordinates(geofence_{{ $customerGeofence->id }}); //get latest coordinates if changed


                $("#customer").val("{{ $customerGeofence->customer_code }}");
                $("#select2-customer-container").html("{{ $customerGeofence->name }} - {{ $customerGeofence->branch }}");

                $("#customer_code_update").val('1'); //for update tagging
                $("#customer_id_update").val('{{ $customerGeofence->customer_code }}'); //customer code to update
                $("#color").val('{{ $customerGeofence->color }}');
            });

            google.maps.event.addListener(geofence_{{ $customerGeofence->id }}, 'bounds_changed', function() {
                var bounds = geofence_{{ $customerGeofence->id }}.getBounds().getCenter();

                setLabelLocation(
                    '{{ $customerGeofence->id }}',
                    '{{ $customerGeofence->name }} - {{ $customerGeofence->branch }}',
                    bounds.lat(),
                    bounds.lng(),
                    '{{ $customerGeofence->color }}',
                    true
                );
            });

            geofences.push(geofence_{{ $customerGeofence->id }});

            setLabelLocation(
                    '{{ $customerGeofence->id }}',
                    '{{ $customerGeofence->name }} - {{ $customerGeofence->branch }}',
                    '{{ $customerGeofence->geo_center_lat }}',
                    '{{ $customerGeofence->geo_center_lng }}',
                    '{{ $customerGeofence->color }}',
                    false
            );

            @endforeach
        }

        //Place Label Inside Circle
        function setLabelLocation(id, name, lat, lng, color, isChanged){
            if(isChanged){
                labels['label_' + id].open(null);
            }

            var marker = new google.maps.Marker({});
            marker.setVisible(false);
            var labelText = name;
            var myOptions = {
                content: labelText,
                boxStyle: {
                    border: "solid",
                    borderColor: color,
                    textAlign: "center",
                    fontSize: "7pt",
                    width: "100px",
                    color: color,
                    background: "#ffffff",
                },
                disableAutoPan: true,
                position: new google.maps.LatLng(lat - (lat * 0.000015), lng),
                closeBoxURL: "",
                isHidden: false,
                pane: "floatPane",
                enableEventPropagation: true
            };
            labels['label_' + id] = new InfoBox(myOptions);
            labels['label_' + id].open(map);
        }

        function initialize() {
            map = new google.maps.Map(document.getElementById('map'), { //var
                zoom: 14,//10,
                center: new google.maps.LatLng(14.632608, 120.946881),//(22.344, 114.048),
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                disableDefaultUI: false,
                zoomControl: true
            });
            // curposdiv = document.getElementById('curpos');
            curseldiv = document.getElementById('cursel');
            var polyOptions = {
                strokeWeight: 0,
                fillOpacity: 0.45,
                editable: true
            };

            // Creates a drawing manager attached to the map that allows the user to draw
            // markers, lines, and shapes.
            drawingManager = new google.maps.drawing.DrawingManager({
                // drawingMode: google.maps.drawing.OverlayType.CIRCLE,
                drawingControlOptions: {
                    drawingModes: ['circle']
                },
                markerOptions: {
                    draggable: true,
                    editable: true,
                },
                polylineOptions: {
                    editable: true
                },
                rectangleOptions: polyOptions,
                circleOptions: polyOptions,
                polygonOptions: polyOptions,
                map: map
            });

            showGeofences();



            google.maps.event.addListener(drawingManager, 'overlaycomplete', function (e) {
                //~ if (e.type != google.maps.drawing.OverlayType.MARKER) {
                var isNotMarker = (e.type != google.maps.drawing.OverlayType.MARKER);
                // Switch back to non-drawing mode after drawing a shape.
                drawingManager.setDrawingMode(null);
                // Add an event listener that selects the newly-drawn shape when the user
                // mouses down on it.
                var newShape = e.overlay;
                newShape.type = e.type;

                lastSelectedShape = newShape;

                google.maps.event.addListener(newShape, 'click', function () {
                    lastSelectedShape = newShape;
                    setSelection(newShape, isNotMarker);
                    $("#customer_code_update").val('0');
                });
                setSelection(newShape, isNotMarker);
            });

            $("#customer_code_update").val('0');

            // Clear the current selection when the drawing mode is changed, or when the
            // map is clicked.
            google.maps.event.addListener(drawingManager, 'drawingmode_changed', clearSelection);
            google.maps.event.addListener(map, 'click', clearSelection);


            google.maps.event.addDomListener(document.getElementById('delete-button'), 'click', deleteSelectedShape);
            buildColorPalette();
            //~ initSearch();
            // Create the search box and link it to the UI element.
            input = /** @type {HTMLInputElement} */( //var
                document.getElementById('pac-input'));
            // map.controls[google.maps.ControlPosition.TOP_RIGHT].push(input);

            //
            var DelPlcButDiv = document.createElement('div');
            //~ DelPlcButDiv.style.color = 'rgb(25,25,25)'; // no effect?
            DelPlcButDiv.style.backgroundColor = '#fff';
            DelPlcButDiv.style.cursor = 'pointer';
            DelPlcButDiv.innerHTML = '';
            map.controls[google.maps.ControlPosition.TOP_RIGHT].push(DelPlcButDiv);
            google.maps.event.addDomListener(DelPlcButDiv, 'click', deletePlacesSearchResults);
            searchBox = new google.maps.places.SearchBox( //var
                /** @type {HTMLInputElement} */(input));
            // Listen for the event fired when the user selects an item from the
            // pick list. Retrieve the matching places for that item.
            google.maps.event.addListener(searchBox, 'places_changed', function () {
                var places = searchBox.getPlaces();
                if (places.length == 0) {
                    return;
                }
                for (var i = 0, marker; marker = placeMarkers[i]; i++) {
                    marker.setMap(null);
                }
                // For each place, get the icon, place name, and location.
                placeMarkers = [];
                var bounds = new google.maps.LatLngBounds();
                for (var i = 0, place; place = places[i]; i++) {
                    var image = {
                        url: place.icon,
                        size: new google.maps.Size(71, 71),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(17, 34),
                        scaledSize: new google.maps.Size(25, 25)
                    };
                    // Create a marker for each place.
                    var marker = new google.maps.Marker({
                        map: map,
                        icon: image,
                        title: place.name,
                        position: place.geometry.location,
                    });
                    placeMarkers.push(marker);
                    bounds.extend(place.geometry.location);
                }
                map.fitBounds(bounds);
            });
            // Bias the SearchBox results towards places that are within the bounds of the
            // current map's viewport.
            google.maps.event.addListener(map, 'bounds_changed', function () {
                var bounds = map.getBounds();
                searchBox.setBounds(bounds);
                // curposdiv.innerHTML = "<b>curpos</b> Z: " + map.getZoom() + " C: " + map.getCenter().toUrlValue();
            }); //////////////////////

        }

        function clearSelection() {
            if (selectedShape) {
                if (typeof selectedShape.setEditable == 'function') {
                    selectedShape.setEditable(false);
                }
                selectedShape = null;
            }

            //unselect all geofences from customer geofence table
            for(var i in geofences) {
                geofences[i].setEditable(false);
            }

            setHiddenCoordinatesValues(null, null, null, null, null);
        }

        function getCoordinates(shape) {

            posstr = "" + selectedShape.position;
            if (typeof selectedShape.position == 'object') {
                posstr = selectedShape.position.toUrlValue();
            }
            pathstr = "" + selectedShape.getPath;
            if (typeof selectedShape.getPath == 'function') {
                pathstr = "[ ";
                for (var i = 0; i < selectedShape.getPath().getLength(); i++) {
                    // .toUrlValue(5) limits number of decimals, default is 6 but can do more
                    pathstr += selectedShape.getPath().getAt(i).toUrlValue() + " , ";
                }
                pathstr += "]";
            }
            bndstr = "" + selectedShape.getBounds;
            cntstr = "" + selectedShape.getBounds;

            if (typeof selectedShape.getBounds == 'function') {
                var tmpbounds = selectedShape.getBounds();
                cntstr = "" + tmpbounds.getCenter().toUrlValue();
                bndstr = "[NE: " + tmpbounds.getNorthEast().toUrlValue() + " SW: " + tmpbounds.getSouthWest().toUrlValue() + "]";
            }
            cntrstr = "" + selectedShape.getCenter;
            if (typeof selectedShape.getCenter == 'function') {
                cntrstr = "" + selectedShape.getCenter().toUrlValue();

            }
            radstr = "" + selectedShape.getRadius;
            if (typeof selectedShape.getRadius == 'function') {
                radstr = "" + selectedShape.getRadius();
            }

            // curseldiv.innerHTML = "<b>cursel</b>: " + selectedShape.type + " " + selectedShape + "; <i>pos</i>: " + posstr + " ; <i>path</i>: " + pathstr + " ; <i>bounds</i>: " + bndstr + " ; <i>Cb</i>: " + cntstr + " ; <i>radius</i>: " + radstr + " ; <i>Cr</i>: " + cntrstr;

            var centerCoordinates = tmpbounds.getCenter();
            var outerCoordinates = tmpbounds.getNorthEast();

            setHiddenCoordinatesValues(
                centerCoordinates.lat(),
                centerCoordinates.lng(),
                outerCoordinates.lat(),
                outerCoordinates.lng(),
                radstr
            );
        }

        function setHiddenCoordinatesValues(center_lat, center_lng, outer_lat, outer_lng, radius) {

            $("#center_lat").val(center_lat);
            $("#center_lng").val(center_lng);
            $("#outer_lat").val(outer_lat);
            $("#outer_lng").val(outer_lng);
            $("#radius").val(radius);
        }

        function setSelection(shape, isNotMarker) {
            clearSelection();
            selectedShape = shape;
            if (isNotMarker)
                shape.setEditable(true);
            selectColor(shape.get('fillColor') || shape.get('strokeColor'));
            getCoordinates(shape);
        }

        function deleteSelectedShape() {
            if (selectedShape) {
                selectedShape.setMap(null);
            }
        }

        function selectColor(color) {
            selectedColor = color;
            for (var i = 0; i < colors.length; ++i) {
                var currColor = colors[i];
                colorButtons[currColor].style.border = currColor == color ? '2px solid #789' : '2px solid #fff';
            }
            // Retrieves the current options from the drawing manager and replaces the
            // stroke or fill color as appropriate.
            var polylineOptions = drawingManager.get('polylineOptions');
            polylineOptions.strokeColor = color;
            drawingManager.set('polylineOptions', polylineOptions);
            var rectangleOptions = drawingManager.get('rectangleOptions');
            rectangleOptions.fillColor = color;
            drawingManager.set('rectangleOptions', rectangleOptions);
            var circleOptions = drawingManager.get('circleOptions');
            circleOptions.fillColor = color;
            drawingManager.set('circleOptions', circleOptions);
            var polygonOptions = drawingManager.get('polygonOptions');
            polygonOptions.fillColor = color;
            drawingManager.set('polygonOptions', polygonOptions);

            //set color
            $("#color").val(color);
        }

        function setSelectedShapeColor(color) {
            if (selectedShape) {
                if (selectedShape.type == google.maps.drawing.OverlayType.POLYLINE) {
                    selectedShape.set('strokeColor', color);
                } else {
                    selectedShape.set('fillColor', color);
                }
            }

            //set color
            $("#color").val(color);
        }

        function makeColorButton(color) {
            var button = document.createElement('span');
            button.className = 'color-button';
            button.style.backgroundColor = color;
            google.maps.event.addDomListener(button, 'click', function () {
                selectColor(color);
                setSelectedShapeColor(color);
            });
            return button;
        }

        function buildColorPalette() {
            var colorPalette = document.getElementById('color-palette');
            for (var i = 0; i < colors.length; ++i) {
                var currColor = colors[i];
                var colorButton = makeColorButton(currColor);
                colorPalette.appendChild(colorButton);
                colorButtons[currColor] = colorButton;
            }
            selectColor(colors[0]);
        }

        /////////////////////////////////////
        var map; //= new google.maps.Map(document.getElementById('map'), {
        // these must have global refs too!:
        var placeMarkers = [];
        var input;
        var searchBox;
        // var curposdiv;
        var curseldiv;

        function deletePlacesSearchResults() {
            for (var i = 0, marker; marker = placeMarkers[i]; i++) {
                marker.setMap(null);
            }
            placeMarkers = [];
            input.value = ''; // clear the box too
        }

        /////////////////////////////////////

        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
</head>

@extends('layouts.app')
@section('content')

    <div id="panel">
        <div class="form-group">
            {!! Form::text('search', '', ['class' => 'form-control', 'id' => 'pac-input', 'maxlength' => '255', 'placeholder' => 'Search Places']) !!}
        </div>

        {!! Form::open(['url' => '/locations/geofences/save', 'method' => 'POST']) !!}
        <div>
            {{--<button id="delete-button">Delete Selected Shape</button>--}}

            {!! Form::hidden('customer_id_update', '', ['id' => 'customer_id_update']) !!}
            {!! Form::hidden('center_lat', '', ['id' => 'center_lat']) !!}
            {!! Form::hidden('center_lng', '', ['id' => 'center_lng']) !!}
            {!! Form::hidden('outer_lat', '', ['id' => 'outer_lat']) !!}
            {!! Form::hidden('outer_lng', '', ['id' => 'outer_lng']) !!}
            {!! Form::hidden('radius', '', ['id' => 'radius']) !!}
            {!! Form::hidden('color', '', ['id' => 'color']) !!}
            {!! Form::hidden('customer_code_update', '', ['id' => 'customer_code_update']) !!}

            <div class="form-group">
                <label>Customers : </label>
                {!! Form::select('customer_code', $customers, null, ['class' => 'form-control select2', 'id' => 'customer', 'onchange' => '$("#customer_code_update").val(\'0\');']) !!}
            </div>

            <div id="color-palette"></div><br>
            <div class="form-group">
                {!! Form::submit('Save Changes', ['id' => 'delete-button', 'class' => 'btn btn-primary', 'onclick' => 'getCoordinates(lastSelectedShape);']) !!}
            </div>
        </div>
        {!! Form::close() !!}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    <div id="map"></div>

@endsection
</html>