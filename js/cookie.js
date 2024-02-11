function deleteCookie(name)
{
  var cookie_date = new Date ( );  // Текущая дата и время
  cookie_date.setTime ( cookie_date.getTime() - 1 );
  document.cookie = name += "=; expires=" + cookie_date.toGMTString();
}

function getCookie(name)
{
  var results = document.cookie.match ( '(^|;) ?' + name + '=([^;]*)(;|$)' );
 
  if ( results )
    return ( unescape ( results[2] ) );
  else
    return null;
}

function setCookie(name, value, exp_y, exp_m, exp_d, path, domain, secure)
{
  var cookie_string = name + "=" + escape ( value );
  if ( exp_y )
  {
    var expires = new Date ( exp_y, exp_m, exp_d );
    cookie_string += "; expires=" + expires.toGMTString();
  }
 
  if ( path )
        cookie_string += "; path=" + escape ( path );
 
  if ( domain )
        cookie_string += "; domain=" + escape ( domain );
  
  if ( secure )
        cookie_string += "; secure";
  
  document.cookie = cookie_string;
}