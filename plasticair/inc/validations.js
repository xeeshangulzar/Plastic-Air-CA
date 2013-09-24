function replaceSubstring(inputString, fromString, toString) {
   // Goes through the inputString and replaces every occurrence of fromString with toString
   var temp = inputString;
   if (fromString == "") {
      return inputString;
   }
   if (toString.indexOf(fromString) == -1) { // If the string being replaced is not a part of the replacement string (normal situation)
      while (temp.indexOf(fromString) != -1) {
         var toTheLeft = temp.substring(0, temp.indexOf(fromString));
         var toTheRight = temp.substring(temp.indexOf(fromString)+fromString.length, temp.length);
         temp = toTheLeft + toString + toTheRight;
      }
   } else { // String being replaced is part of replacement string (like "+" being replaced with "++") - prevent an infinite loop
      var midStrings = new Array("~", "`", "_", "^", "#");
      var midStringLen = 1;
      var midString = "";
      // Find a string that doesn't exist in the inputString to be used
      // as an "inbetween" string
      while (midString == "") {
         for (var i=0; i < midStrings.length; i++) {
            var tempMidString = "";
            for (var j=0; j < midStringLen; j++) { tempMidString += midStrings[i]; }
            if (fromString.indexOf(tempMidString) == -1) {
               midString = tempMidString;
               i = midStrings.length + 1;
            }
         }
      } // Keep on going until we build an "inbetween" string that doesn't exist
      // Now go through and do two replaces - first, replace the "fromString" with the "inbetween" string
      while (temp.indexOf(fromString) != -1) {
         var toTheLeft = temp.substring(0, temp.indexOf(fromString));
         var toTheRight = temp.substring(temp.indexOf(fromString)+fromString.length, temp.length);
         temp = toTheLeft + midString + toTheRight;
      }
      // Next, replace the "inbetween" string with the "toString"
      while (temp.indexOf(midString) != -1) {
         var toTheLeft = temp.substring(0, temp.indexOf(midString));
         var toTheRight = temp.substring(temp.indexOf(midString)+midString.length, temp.length);
         temp = toTheLeft + toString + toTheRight;
      }
   } // Ends the check to see if the string being replaced is part of the replacement string or not
   return temp; // Send the updated string back to the user
} // Ends the "replaceSubstring" function

//function IsNumeric(sText)
//{
//   var ValidChars = "0123456789.";
//   var IsNumber=true;
//   var Char;
//
// 
//   for (i = 0; i < sText.length && IsNumber == true; i++) 
//      { 
//      Char = sText.charAt(i); 
//      if (ValidChars.indexOf(Char) == -1) 
//         {
//         IsNumber = false;
//         }
//      }
//   return IsNumber;
//   
//   }

//-------------------------------------------------------------------
// Trim functions
//   Returns string with whitespace trimmed
//-------------------------------------------------------------------
function LTrim(str){
	if (str==null){return null;}
	for(var i=0;str.charAt(i)==" ";i++);
	return str.substring(i,str.length);
	}
function RTrim(str){
	if (str==null){return null;}
	for(var i=str.length-1;str.charAt(i)==" ";i--);
	return str.substring(0,i+1);
	}
function Trim(str){return LTrim(RTrim(str));}
function LTrimAll(str) {
	if (str==null){return str;}
	for (var i=0; str.charAt(i)==" " || str.charAt(i)=="\n" || str.charAt(i)=="\t"; i++);
	return str.substring(i,str.length);
	}
function RTrimAll(str) {
	if (str==null){return str;}
	for (var i=str.length-1; str.charAt(i)==" " || str.charAt(i)=="\n" || str.charAt(i)=="\t"; i--);
	return str.substring(0,i+1);
	}
function TrimAll(str) {
	return LTrimAll(RTrimAll(str));
	}
//-------------------------------------------------------------------
// isNull(value)
//   Returns true if value is null
//-------------------------------------------------------------------
function isNull(val){return(val==null);}

//-------------------------------------------------------------------
// isBlank(value)
//   Returns true if value only contains spaces
//-------------------------------------------------------------------
function isBlank(val){
	if(val==null){return true;}
	for(var i=0;i<val.length;i++) {
		if ((val.charAt(i)!=' ')&&(val.charAt(i)!="\t")&&(val.charAt(i)!="\n")&&(val.charAt(i)!="\r")){return false;}
		}
	return true;
	}

//-------------------------------------------------------------------
// isInteger(value)
//   Returns true if value contains all digits
//-------------------------------------------------------------------
function isInteger(val){
	if (isBlank(val)){return false;}
	for(var i=0;i<val.length;i++){
		if(!isDigit(val.charAt(i))){return false;}
		}
	return true;
	}

//-------------------------------------------------------------------
// isNumeric(value)
//   Returns true if value contains a positive float value
//-------------------------------------------------------------------
function isNumeric(val){return(parseFloat(val,10)==(val*1));}

//-------------------------------------------------------------------
// isArray(obj)
// Returns true if the object is an array, else false
//-------------------------------------------------------------------
function isArray(obj){return(typeof(obj.length)=="undefined")?false:true;}

//-------------------------------------------------------------------
// isDigit(value)
//   Returns true if value is a 1-character digit
//-------------------------------------------------------------------
function isDigit(num) {
	if (num.length>1){return false;}
	var string="1234567890";
	if (string.indexOf(num)!=-1){return true;}
	return false;
	}

//-------------------------------------------------------------------
// setNullIfBlank(input_object)
//   Sets a form field to "" if it isBlank()
//-------------------------------------------------------------------
function setNullIfBlank(obj){if(isBlank(obj.value)){obj.value="";}}

//-------------------------------------------------------------------
// setFieldsToUpperCase(input_object)
//   Sets value of form field toUpperCase() for all fields passed
//-------------------------------------------------------------------
function setFieldsToUpperCase(){
	for(var i=0;i<arguments.length;i++) {
		arguments[i].value = arguments[i].value.toUpperCase();
		}
	}

//-------------------------------------------------------------------
// disallowBlank(input_object[,message[,true]])
//   Checks a form field for a blank value. Optionally alerts if 
//   blank and focuses
//-------------------------------------------------------------------
function disallowBlank(obj){
	var msg=(arguments.length>1)?arguments[1]:"";
	var dofocus=(arguments.length>2)?arguments[2]:false;
	if (isBlank(getInputValue(obj))){
		if(!isBlank(msg)){alert(msg);}
		if(dofocus){
			if (isArray(obj) && (typeof(obj.type)=="undefined")) {obj=obj[0];}
			if(obj.type=="text"||obj.type=="textarea"||obj.type=="password") { obj.select(); }
			obj.focus();
			}
		return true;
		}
	return false;
	}

//-------------------------------------------------------------------
// disallowModify(input_object[,message[,true]])
//   Checks a form field for a value different than defaultValue. 
//   Optionally alerts and focuses
//-------------------------------------------------------------------
function disallowModify(obj){
	var msg=(arguments.length>1)?arguments[1]:"";
	var dofocus=(arguments.length>2)?arguments[2]:false;
	if (getInputValue(obj)!=getInputDefaultValue(obj)){
		if(!isBlank(msg)){alert(msg);}
		if(dofocus){
			if (isArray(obj) && (typeof(obj.type)=="undefined")) {obj=obj[0];}
			if(obj.type=="text"||obj.type=="textarea"||obj.type=="password") { obj.select(); }
			obj.focus();
			}
		setInputValue(obj,getInputDefaultValue(obj));
		return true;
		}
	return false;
	}

//-------------------------------------------------------------------
// commifyArray(array)
//   Take an array of values and turn it into a comma-separated string
//-------------------------------------------------------------------
function commifyArray(obj){
	var s="";
	if(obj==null||obj.length<=0){return s;}
	for(var i=0;i<obj.length;i++){
		s=s+((s=="")?"":",")+obj[i].toString();
		}
	return s;
	}

//-------------------------------------------------------------------
// getSingleInputValue(input_object,use_default)
//   Utility function used by others
//-------------------------------------------------------------------
function getSingleInputValue(obj,use_default) {
	switch(obj.type){
		case 'radio': case 'checkbox': return(((use_default)?obj.defaultChecked:obj.checked)?obj.value:null);
		case 'text': case 'hidden': case 'textarea': return(use_default)?obj.defaultValue:obj.value;
		case 'password': return((use_default)?null:obj.value);
		case 'select-one':
			if (obj.options==null) { return null; }
			if(use_default){
				var o=obj.options;
				for(var i=0;i<o.length;i++){if(o[i].defaultSelected){return o[i].value;}}
				return o[0].value;
				}
			if (obj.selectedIndex<0){return null;}
			return(obj.options.length>0)?obj.options[obj.selectedIndex].value:null;
		case 'select-multiple': 
			if (obj.options==null) { return null; }
			var values=new Array();
			for(var i=0;i<obj.options.length;i++) {
				if((use_default&&obj.options[i].defaultSelected)||(!use_default&&obj.options[i].selected)) {
					values[values.length]=obj.options[i].value;
					}
				}
			return (values.length==0)?null:commifyArray(values);
		}
	alert("FATAL ERROR: Field type "+obj.type+" is not supported for this function");
	return null;
	}

//-------------------------------------------------------------------
// getSingleInputText(input_object,use_default)
//   Utility function used by others
//-------------------------------------------------------------------
function getSingleInputText(obj,use_default) {
	switch(obj.type){
		case 'radio': case 'checkbox': 	return "";
		case 'text': case 'hidden': case 'textarea': return(use_default)?obj.defaultValue:obj.value;
		case 'password': return((use_default)?null:obj.value);
		case 'select-one':
			if (obj.options==null) { return null; }
			if(use_default){
				var o=obj.options;
				for(var i=0;i<o.length;i++){if(o[i].defaultSelected){return o[i].text;}}
				return o[0].text;
				}
			if (obj.selectedIndex<0){return null;}
			return(obj.options.length>0)?obj.options[obj.selectedIndex].text:null;
		case 'select-multiple': 
			if (obj.options==null) { return null; }
			var values=new Array();
			for(var i=0;i<obj.options.length;i++) {
				if((use_default&&obj.options[i].defaultSelected)||(!use_default&&obj.options[i].selected)) {
					values[values.length]=obj.options[i].text;
					}
				}
			return (values.length==0)?null:commifyArray(values);
		}
	alert("FATAL ERROR: Field type "+obj.type+" is not supported for this function");
	return null;
	}

//-------------------------------------------------------------------
// setSingleInputValue(input_object,value)
//   Utility function used by others
//-------------------------------------------------------------------
function setSingleInputValue(obj,value) {
	switch(obj.type){
		case 'radio': case 'checkbox': if(obj.value==value){obj.checked=true;return true;}else{obj.checked=false;return false;}
		case 'text': case 'hidden': case 'textarea': case 'password': obj.value=value;return true;
		case 'select-one': case 'select-multiple': 
			var o=obj.options;
			for(var i=0;i<o.length;i++){
				if(o[i].value==value){o[i].selected=true;}
				else{o[i].selected=false;}
				}
			return true;
		}
	alert("FATAL ERROR: Field type "+obj.type+" is not supported for this function");
	return false;
	}

//-------------------------------------------------------------------
// getInputValue(input_object)
//   Get the value of any form input field
//   Multiple-select fields are returned as comma-separated values
//   (Doesn't support input types: button,file,reset,submit)
//-------------------------------------------------------------------
function getInputValue(obj) {
	var use_default=(arguments.length>1)?arguments[1]:false;
	if (isArray(obj) && (typeof(obj.type)=="undefined")) {
		var values=new Array();
		for(var i=0;i<obj.length;i++){
			var v=getSingleInputValue(obj[i],use_default);
			if(v!=null){values[values.length]=v;}
			}
		return commifyArray(values);
		}
	return getSingleInputValue(obj,use_default);
	}

//-------------------------------------------------------------------
// getInputText(input_object)
//   Get the displayed text of any form input field
//   Multiple-select fields are returned as comma-separated values
//   (Doesn't support input types: button,file,reset,submit)
//-------------------------------------------------------------------
function getInputText(obj) {
	var use_default=(arguments.length>1)?arguments[1]:false;
	if (isArray(obj) && (typeof(obj.type)=="undefined")) {
		var values=new Array();
		for(var i=0;i<obj.length;i++){
			var v=getSingleInputText(obj[i],use_default);
			if(v!=null){values[values.length]=v;}
			}
		return commifyArray(values);
		}
	return getSingleInputText(obj,use_default);
	}

//-------------------------------------------------------------------
// getInputDefaultValue(input_object)
//   Get the default value of any form input field when it was created
//   Multiple-select fields are returned as comma-separated values
//   (Doesn't support input types: button,file,password,reset,submit)
//-------------------------------------------------------------------
function getInputDefaultValue(obj){return getInputValue(obj,true);}

//-------------------------------------------------------------------
// isChanged(input_object)
//   Returns true if input object's value has changed since it was
//   created.
//-------------------------------------------------------------------
function isChanged(obj){return(getInputValue(obj)!=getInputDefaultValue(obj));}

//-------------------------------------------------------------------
// setInputValue(obj,value)
//   Set the value of any form field. In cases where no matching value
//   is available (select, radio, etc) then no option will be selected
//   (Doesn't support input types: button,file,password,reset,submit)
//-------------------------------------------------------------------
function setInputValue(obj,value) {
	var use_default=(arguments.length>1)?arguments[1]:false;
	if(isArray(obj)&&(typeof(obj.type)=="undefined")){
		for(var i=0;i<obj.length;i++){setSingleInputValue(obj[i],value);}
		}
	else{setSingleInputValue(obj,value);}
	}
	
//-------------------------------------------------------------------
// isFormModified(form_object,hidden_fields,ignore_fields)
//   Check to see if anything in a form has been changed. By default
//   it will check all visible form elements and ignore all hidden 
//   fields. 
//   You can pass a comma-separated list of field names to check in
//   addition to visible fields (for hiddens, etc).
//   You can also pass a comma-separated list of field names to be
//   ignored in the check.
//-------------------------------------------------------------------
function isFormModified(theform,hidden_fields,ignore_fields){
	if(hidden_fields==null){hidden_fields="";}
	if(ignore_fields==null){ignore_fields="";}
	var hiddenFields=new Object();
	var ignoreFields=new Object();
	var i,field;
	var hidden_fields_array=hidden_fields.split(',');
	for (i=0;i<hidden_fields_array.length;i++) {
		hiddenFields[Trim(hidden_fields_array[i])]=true;
		}
	var ignore_fields_array=ignore_fields.split(',');
	for (i=0;i<ignore_fields_array.length;i++) {
		ignoreFields[Trim(ignore_fields_array[i])]=true;
		}
	for (i=0;i<theform.elements.length;i++) {
		var changed=false;
		var name=theform.elements[i].name;
		if(!isBlank(name)){
			var type=theform[name].type;
			if(!ignoreFields[name]){
				if(type=="hidden"&&hiddenFields[name]){changed=isChanged(theform[name]);}
				else if(type=="hidden"){changed=false;}
				else {changed=isChanged(theform[name]);}
				}
			}
		if(changed){return true;}
		}
		return false;
	}

function isDate(dateStr) {

var datePat = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;
var matchArray = dateStr.match(datePat); // is the format ok?

if (matchArray == null) {
//alert("Please enter date as either mm/dd/yyyy or mm-dd-yyyy.");
return false;
}

month = matchArray[1]; // p@rse date into variables
day = matchArray[3];
year = matchArray[5];

if (month < 1 || month > 12) { // check month range
alert("Month must be between 1 and 12.");
return false;
}

if (day < 1 || day > 31) {
alert("Day must be between 1 and 31.");
return false;
}

if ((month==4 || month==6 || month==9 || month==11) && day==31) {
alert("Month "+month+" doesn`t have 31 days!")
return false;
}

if (month == 2) { // check for february 29th
var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0));
if (day > 29 || (day==29 && !isleap)) {
alert("February " + year + " doesn`t have " + day + " days!");
return false;
}
}
return true; // date is valid
}


<!-- Begin
function isValidDate(dateStr) {
// Checks for the following valid date formats:
// MM/DD/YY   MM/DD/YYYY   MM-DD-YY   MM-DD-YYYY

var datePat = /^(\d{1,2})(\/|-)(\d{1,2})\2(\d{4})$/; // requires 4 digit year

var matchArray = dateStr.match(datePat); // is the format ok?
if (matchArray == null) {
//alert(dateStr + " Date is not in a valid format.")
return true;
}
month = matchArray[1]; // parse date into variables
day = matchArray[3];
year = matchArray[4];
if (month < 1 || month > 12) { // check month range
//alert("Month must be between 1 and 12.");
return true;
}
if (day < 1 || day > 31) {
//alert("Day must be between 1 and 31.");
return true;
}
if ((month==4 || month==6 || month==9 || month==11) && day==31) {
//alert("Month "+month+" doesn't have 31 days!")
return true;
}
if (month == 2) { // check for february 29th
var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0));
if (day>29 || (day==29 && !isleap)) {
//alert("February " + year + " doesn't have " + day + " days!");
return true;
   }
}
return true;
}

function isValidTime2(timeStr) {
// Checks if time is in HH:MM:SS AM/PM format.
// The seconds and AM/PM are optional.

var timePat = /^(\d{1,2}):(\d{2})(:(\d{2}))?(\s?(AM|am|PM|pm))?$/;

var matchArray = timeStr.match(timePat);
if (matchArray == null) {
//alert("Time is not in a valid format.");
return false;
}
hour = matchArray[1];
minute = matchArray[2];
second = matchArray[4];
ampm = matchArray[6];

if (second=="") { second = null; }
if (ampm=="") { ampm = null }

if (hour < 1  || hour > 12) {
alert("Hour must be between 1 and 12.");
return false;
}
if (ampm == null)
	{
	alert("You must specify AM or PM.");
	return false;
	}
if (minute < 0 || minute > 59) {
alert ("Minute must be between 0 and 59.");
return false;
}
if (second != null && (second < 0 || second > 59)) {
alert ("Second must be between 0 and 59.");
return false;
}
return true;
}

function isValidTime(timeStr) {
// Checks if time is in HH:MM:SS AM/PM format.
// The seconds and AM/PM are optional.

var timePat = /^(\d{1,2}):(\d{2})(:(\d{2}))?(\s?(AM|am|PM|pm))?$/;

var matchArray = timeStr.match(timePat);
if (matchArray == null) {
//alert("Time is not in a valid format.");
return true;
}
hour = matchArray[1];
minute = matchArray[2];
second = matchArray[4];
ampm = matchArray[6];

if (second=="") { second = null; }
if (ampm=="") { ampm = null }

if (hour < 0  || hour > 23) {
//alert("Hour must be between 1 and 12. (or 0 and 23 for military time)");
return true;
}
if (hour <= 12 && ampm == null) {
if (confirm("Please indicate which time format you are using.  OK = Standard Time, CANCEL = Military Time")) {
//alert("You must specify AM or PM.");
return true;
   }
}
if  (hour > 12 && ampm != null) {
//alert("You can't specify AM or PM for military time.");
return true;
}
if (minute < 0 || minute > 59) {
//alert ("Minute must be between 0 and 59.");
return true;
}
if (second != null && (second < 0 || second > 59)) {
//alert ("Second must be between 0 and 59.");
return true;
}
return true;
}


function ParseItJS(InputValue, DecPlaces)
	{
		if (isNumeric(InputValue)==false)
			{
				return ''
			}

		InputValue = InputValue.toString()

		if (InputValue.indexOf('.') == 0)
			{
			InputValue = '0' + InputValue
			}

		if (InputValue.indexOf('.') >= 0)
			{
				InputValue = InputValue + '0000000000'
			}
		else
			{
				InputValue = InputValue + '.0000000000'
			}			

		if (isNumeric(InputValue))
			{
				InputValue = InputValue.substring(0, InputValue.indexOf('.') + DecPlaces + 1)
				return InputValue
			}

		return ''
}

function ParseItJs1(s_input){
	s_input=ParseItJS(s_input, 2);
	arr_pay=s_input.toString().split('.');
	payment2 = arr_pay.length > 1 ? '.' + arr_pay[1] : '';
	n_length=arr_pay[0].toString().length;
	payment1="";
	while (n_length>0){
		payment1=(n_length-3>0?",":"")+arr_pay[0].toString().substring((n_length-3<0?0:(n_length-3)), n_length)+payment1;
	  	n_length-=3;
	   }
	  return payment1+payment2;
	}
function dateDiff(dateform, decimalplaces) {
date1 = new Date();
date2 = new Date();
diff  = new Date();

if (isValidDate(dateform.TravelDate.value) && isValidTime(dateform.TravelTime.value)) { // Validates first date 
date1temp = new Date(dateform.TravelDate.value + " " + dateform.TravelTime.value);
date1.setTime(date1temp.getTime());
}
else return false; // otherwise exits

if (isValidDate(dateform.ReturnDate.value) && isValidTime(dateform.ReturnTime.value)) { // Validates second date 
date2temp = new Date(dateform.ReturnDate.value + " " + dateform.ReturnTime.value);
date2.setTime(date2temp.getTime());
}
else return false; // otherwise exits

// sets difference date to difference of first date and second date

diff.setTime(Math.abs(date1.getTime() - date2.getTime()));

timediff = diff.getTime();

weeks = Math.floor(timediff / (1000 * 60 * 60 * 24 * 7));
timediff -= weeks * (1000 * 60 * 60 * 24 * 7);

days = Math.floor(timediff / (1000 * 60 * 60 * 24)); 
timediff -= days * (1000 * 60 * 60 * 24);

hours = Math.floor(timediff / (1000 * 60 * 60)); 
timediff -= hours * (1000 * 60 * 60);

mins = Math.floor(timediff / (1000 * 60)); 
timediff -= mins * (1000 * 60);

secs = Math.floor(timediff / 1000); 
timediff -= secs * 1000;

//dateform.NumberOfDays.value = weeks + " weeks, " + days + " days, " + hours + " hours, " + mins + " minutes, and " + secs + " seconds";
if ((isDate(dateform.TravelDate.value)==true) && (isDate(dateform.ReturnDate.value)==true)) 
	{
	//if (dateform.RateType(0).checked==true) // condition removed per FogBugz entry 107
	//	{
		if (((weeks * 7) + days)==0) 
			{
			dateform.NumberOfDays.value = 1; 
			if ((isNumeric(Trim(dateform.VoucherRate.value))==true) && (isInteger(Trim(dateform.NumberOfDays.value))==true) && (isInteger(Trim(dateform.NumberOfItems.value))==true)) 
				{
				dateform.GrossAmount.value=ParseItJS(((parseFloat(Trim(dateform.VoucherRate.value)) * parseInt(Trim(dateform.NumberOfDays.value)) * parseInt(Trim(dateform.NumberOfItems.value)))), decimalplaces);
				}
			} 
		else 
			{
			dateform.NumberOfDays.value = ((weeks * 7) + days); 
			if ((isNumeric(Trim(dateform.VoucherRate.value))==true) && (isInteger(Trim(dateform.NumberOfDays.value))==true) && (isInteger(Trim(dateform.NumberOfItems.value))==true)) 
				{
				dateform.GrossAmount.value=ParseItJS(((parseFloat(Trim(dateform.VoucherRate.value)) * parseInt(Trim(dateform.NumberOfDays.value)) * parseInt(Trim(dateform.NumberOfItems.value)))), decimalplaces);
					}
			}
	//	}
	}

return true; // form should never submit, returns false
}
//  End -->

function IsTime(strTime)
	{
		var strTestTime = new String(strTime);
		strTestTime.toUpperCase();

		var bolTime = false;

		if (strTestTime.indexOf("PM",1) != -1 || strTestTime.indexOf("AM",1))
			bolTime = true;

		if (bolTime && strTestTime.indexOf(":",0) == 0)
			bolTime = false;

		var nColonPlace = strTestTime.indexOf(":",1);
		if (bolTime && ((parseInt(nColonPlace) + 5) < (strTestTime.length - 1) || (parseInt(nColonPlace) + 4) > (strTestTime.length - 1)))
			bolTime = false;


		return bolTime;
	}
function addCommas(nStr)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}
