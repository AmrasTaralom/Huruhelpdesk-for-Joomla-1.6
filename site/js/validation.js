function verifystring(str,min,max)
{
// function to verify string meets requirements
// takes string and minimum and maximum length
// and returns true if string meets requirements
// use min=0 to allow empty strings
// use max=0 to allow unlimited strings

	var i, valchar;
		
	//check that we have the parameters we need and they are valid
	if (str.length < 0 || min < 0 || max < 0 || (min > max && max != 0))
	{
		return false;
	}

	//check the length or value against our min/max parameters
	//if max is set to 0, then there is no max length
	if (str.length < min) return false;
	if (max > 0 && str.length > max) return false;
	
	//if all tests passed, then we're okay
	return true;
}

function isEmail(useremail)
{
	var i, valchar, dom, isValid;

	valchar = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ.@_-0123456789";

	//allow for blank email address for anonymous users
	if ((useremail.length <= 0) || ((useremail.length == 1) && (useremail.indexOf(" ")==0)))
		{
		return true;
		}
			
	//if email is not blank, then make sure it appears valid
	//First check overall string for necessary properties
	//To possibly be an email address, the string must be between 5 and 255 characters long,
	//contain one, and only one @ that is not the first character and at least 4th from the end, have no _ after the @,
	//contain at least one . that is closer to the end than the @ and is not the first character and is at least 2nd from the end.
	//There can also not be two . together,  	

	if ((useremail.length >= 5) && (useremail.length < 255)
		&& (useremail.indexOf("@") > 0)	&& (useremail.lastIndexOf("@")<= useremail.length-4)
		&& (useremail.indexOf(".") > 0)	&& (useremail.lastIndexOf(".")<= useremail.length-2)
		&& (useremail.lastIndexOf(".") > useremail.lastIndexOf("@"))
		&& (useremail.lastIndexOf("@") > useremail.lastIndexOf("_"))
		&& (useremail.indexOf("..") < 0)
		&& (useremail.indexOf("@",useremail.indexOf("@")+1)))
		{

		//Now check each character of the domain portion of the address to make sure it is valid for an email address
		//(per RFC the username can contain any ascii 0-255, even though we have limited it to not containing an extra @)
		//A list of valid characters is defined above as a string.  Note that we user a text compare so we don't have to worry about case
		dom = useremail.substring(useremail.indexOf("@")+1);
		for (i=1; i<useremail.length; i++)
			{
			if (valchar.indexOf(dom.substring(i,i+1)) < 0)
				{
				//alert("The email address you entered does not appear to be valid.\nPlease enter a valid email address or leave the field blank.");
				return false;
				}
			}
		return true;
		}
	else
		{
		//alert("The email address you entered does not appear to be valid.\nPlease enter a valid email address or leave the field blank.");
		return false;
		}

	return true;
}

function isDateYMD(dateStr)
{
// ******************************************************************
// This function accepts a string variable and verifies if it is a
// proper date in the format yyyy-mm-dd (with either - or / separators). 
// After checking the format, it reformats the string and sends the 
// result the isDate() function for validation.
// ******************************************************************

	var datePat, matchArray;
	
	// is the format ok (mm-dd-yyyy)?
	datePat = /^(\d{4})([\/-])(\d{1,2})([\/-])(\d{1,2})$/;
	matchArray = dateStr.match(datePat);
	if (matchArray == null) 
	{
		return false;
	}

	// parse yyyy-mm-dd date into variables
	month = matchArray[3]; 
	day = matchArray[5];
	year = matchArray[1];
	
	if(isDate(month + '-' + day + '-' + year)) return true;
	else return false;
}


function isDate(dateStr) 
{
// ******************************************************************
// This function accepts a string variable and verifies if it is a
// proper date in the format mm-dd-yyyy (with either - or / separators). 
// Then it checks to make sure the month has the proper number of 
// days, based on which month it is. The function returns true if a 
// valid date, false if not.
// ******************************************************************

	var datePat, matchArray;
	
	// is the format ok (mm-dd-yyyy)?
	datePat = /^(\d{1,2})([\/-])(\d{1,2})([\/-])((\d{2})|(\d{4}))$/;
	matchArray = dateStr.match(datePat);
	if (matchArray == null) 
	{
		return false;
	}

	// parse mm-dd-yyyy date into variables
	month = matchArray[1]; 
	day = matchArray[3];
	year = matchArray[5];
	
	// check month range
	if (month < 1 || month > 12) 
	{ 
		return false;
	}
	
	// check day range
	if (day < 1 || day > 31) 
	{
		return false;
	}
	
	//check for months with only 30 days
	if ((month==4 || month==6 || month==9 || month==11) && day==31)
	{
		return false;
	}
	
	// check february
	if (month == 2) 
	{ 
		var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0));
		if (day > 29 || (day==29 && !isleap))
		{
			return false;
		}
	}
	
	// date is valid
	return true; 
}

function isPhone(str) 
{
	// Pattern matches (999)-999-9999, (999) 999-9999, (999)999-9999, 999-9999, 999 9999, 9999999, etc.
    //var regexp = /^((\((\d{3})\)|(\d{3}))[- ]?)?(\d{3})[- ]?(\d{4})$/;
    var regexp = /^((\((\d{3})\)|(\d{3}))[-\. ]?)?(\d{3})[-\. ]?(\d{4})[ ]?([xX ]?(\d{1,6}))?$/; //this allows extension numbers seperated by space or 'x'

    if (regexp.test(str)) 
    {
       	return true;
    }
    return false;
}

function isNum(num) 
{
    if (num == "") 
    {
        return false;
    }
    for (var i = 0; i < num.length; i++) 
    {
        if (num.charAt(i) < "0" || num.charAt(i) > "9") 
        {
            return false;
        }
    }
    return true;
}
