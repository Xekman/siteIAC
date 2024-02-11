function show_map_point (coord)
{						if (!myMap)
						{
							myMap = new ymaps.Map(
							'map', 
							{
							center: coord, zoom: 15,controls: ['zoomControl', 'searchControl']
							}
							 );
							$("#toggle").attr('value', 'Удалить координаты ОА');
							myPlacemark = new ymaps.Placemark(coord,{hintContent: 'Переместите метку на ОА'}, {preset: "twirl#houseIcon", draggable: true, visible: true});
							myMap.geoObjects.add(myPlacemark);
							//Отслеживаем событие перемещения метки
							myPlacemark.events.add("dragend", function (e) {
							coordinates = this.geometry.getCoordinates();
							savecoordinats();
							}, myPlacemark);
							//Отслеживаем событие щелчка по карте
							myMap.events.add("click", function (e) {
							coordinates = e.get('coords');
							savecoordinats();
							});	
						}
						else
						{
							myMap.destroy();
							myMap = null;
							$("#toggle").attr('value', 'Показать карту снова');
							document.getElementById('map').style.width = "0px";
							document.getElementById('map').style.height = "0px";
							document.getElementById('yandex_location').value = "";
						}
}
function get_coordinates_geocoding(findaddress,coordid)
//получение координат пользователя по адресу и помещение их в input
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
//получение координат пользователя по адресу
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
							alert('Ошибка определения местоположения ОА по адресу');
						}
						);
}

function get_geolocation()
//получение координат пользователя по ip
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
							alert('Ошибка определения местоположения ОА по ip');
						});
}
function savecoordinats (){	
		var new_coordinates = coordinates;
		myPlacemark.getOverlaySync().getData().geometry.setCoordinates(new_coordinates);
		document.getElementById("yandex_location").value = new_coordinates;
		
	}

