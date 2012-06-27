/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN videoshow
 * @version $Id: jsn_is_utils.js 6505 2011-05-31 11:01:31Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
var JSNISUtils = {
	getCookie: function(cookieName)
	{
		var name, value;
		var arrayCookie = document.cookie.split(";");
		
		for (var i=0; i < arrayCookie.length; i++)
		{
			name 	= arrayCookie[i].substr(0,arrayCookie[i].indexOf("="));
			value 	= arrayCookie[i].substr(arrayCookie[i].indexOf("=")+1);
			name 	= name.replace(/^\s+|\s+$/g,"");
			
			if (name == cookieName)
		    {
				return unescape(value);
		    }
		}
	},

	setCookie: function(cookieName, value, exdays, exminutes)
	{
		var date = new Date();
		var expire = null;
		
		if (exdays && !exminutes)
		{
			date.setDate(date.getDate() + exdays);
			expire = date.toUTCString();
		}
		else if (exminutes && !exdays)
		{
			date.setTime(date.getTime() + 60 * exminutes * 1000);
			expire = date.toGMTString();
		}
		
		var cookieValue = escape(value) + ((expire==null) ? "" : "; expires="+expire);
		
		document.cookie = cookieName + "=" + cookieValue;
	}
};