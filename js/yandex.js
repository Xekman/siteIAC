function show_map_point (coord)
{						if (!myMap)
						{
							myMap = new ymaps.Map(
							'map', 
							{
							center: coord, zoom: 15,controls: ['zoomControl', 'searchControl']
							}
							 );
							$("#toggle").attr('value', '������� ���������� ��');
							myPlacemark = new ymaps.Placemark(coord,{hintContent: '����������� ����� �� ��'}, {preset: "twirl#houseIcon", draggable: true, visible: true});
							myMap.geoObjects.add(myPlacemark);
							//����������� ������� ����������� �����
							myPlacemark.events.add("dragend", function (e) {
							coordinates = this.geometry.getCoordinates();
							savecoordinats();
							}, myPlacemark);
							//����������� ������� ������ �� �����
							myMap.events.add("click", function (e) {
							coordinates = e.get('coords');
							savecoordinats();
							});	
						}
						else
						{
							myMap.destroy();
							myMap = null;
							$("#toggle").attr('value', '�������� ����� �����');
							document.getElementById('map').style.width = "0px";
							document.getElementById('map').style.height = "0px";
							document.getElementById('yandex_location').value = "";
						}
}
function get_coordinates_geocoding(findaddress,coordid)
//��������� ��������� ������������ �� ������ � ��������� �� � input
{
	if (getValue(findaddress)=='')
	{
		document.getElementById(coordid).value = '';
		go();
	}
	
	var myGeocoder = ymaps.geocode(getValue(findaddress));
						myGeocoder.then(
						function (res)
						{
							if (res.geoObjects.get(0))
							{
								document.getElementById(coordid).value = res.geoObjects.get(0).geometry.getCoordinates();
								go();
							} else
							{
								document.getElementById(coordid).value = '';
								go();
							}
						},
						function (err) {
							
						}
						);
}

function get_geocoding(findaddress)
//��������� ��������� ������������ �� ������
{
	var myGeocoder = ymaps.geocode(findaddress);
						myGeocoder.then(
						function (res)
						{
							if (res.geoObjects.get(0))
							{
								coordinates = res.geoObjects.get(0).geometry.getCoordinates();
								show_map_point(coordinates);
							}
							else
							{
								get_geolocation();
							}
						},
						function (err) {
							alert('������ ����������� �������������� �� �� ������');
						}
						);
}

function get_geolocation()
//��������� ��������� ������������ �� ip
{
ymaps.geolocation.get({
        provider: 'yandex',
        mapStateAutoApply: true,
		autoReverseGeocode: false
    }).then(function (result) {
       coordinates = result.geoObjects.get(0).geometry.getCoordinates();
	   if (!coordinates)
	   {
		coordinates = '55.751574, 37.573856';
	   }
		show_map_point(coordinates);
    },
	function (err) {
							alert('������ ����������� �������������� �� �� ip');
						});
}
function savecoordinats (){	
		var new_coordinates = coordinates;
		myPlacemark.getOverlaySync().getData().geometry.setCoordinates(new_coordinates);
		document.getElementById("yandex_location").value = new_coordinates;
		
	}

