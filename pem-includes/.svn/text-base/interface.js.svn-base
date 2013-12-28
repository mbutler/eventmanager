
function ie_day_bug_sync()
{
  alert("month: "+document.getElementById('date_begin_month').value+" day: "+document.getElementById('date_begin_day').value)

   document.getElementById('ie_bug_fix').value = document.getElementById('date_begin_day').value;
}

function confirm_submit(formname, dothis, message)
{
   if (confirm(message))
   {
      document.getElementById(formname).datasubmit.value = dothis;
      document.getElementById(formname).submit();
   }
}
function action_submit(formname, dothis)
{
   document.getElementById(formname).datasubmit.value = dothis;
   document.getElementById(formname).submit();
}

function field_behavior_visible(behavior)
{
   if (document.getElementById(behavior).selectedIndex == 0)
      document.getElementById(behavior).selectedIndex = 1;
}

function showIt(name)
{
   if(document.getElementById)
	 {
      document.getElementById(name).style.visibility='visible';
      document.getElementById(name).style.display='block';
   }
   else if(document.layers)
	 {
      document.layers[name].visibility='show';
      document.layers[name].display='block';
   }
   else if(document.all)
	 {
      document.all(name).style.visibility='visible';
      document.all(name).style.display='block';
   }
}

function hideIt(name)
{
   if(document.getElementById)
   {
       document.getElementById(name).style.visibility='hidden'
       document.getElementById(name).style.display='none'
   }
   else if(document.layers)
   {
      document.layers[name].visibility='hide'
      document.layers[name].display='none'
   }
   else if(document.all)
   {
      document.all(name).style.visibility='hidden'
      document.all(name).style.display='none'
   }
}

var Interval;
function showPopup(name, form)
{
   Interval = window.setInterval("checkScroll('" + name + "', '" + form + "')", 1000);
   showIt(name);
}

function hidePopup(name)
{
   clearInterval(Interval);
   hideIt(name);
}

function checkScroll(name, form)
{
   popUp = document.getElementById(name);
   popUp.style.top = document.documentElement.scrollTop + "px";
   popUp.style.right = "5px";

<!--
   if (popUp.offsetHeight > document.documentElement.clientHeight)
   {
      popUp.offsetHeight = (document.documentElement.clientHeight - 200) + "px";
   }
//-->
<!--  alert("currentStyle: " + popUp.style.height + ", style: " + popUp.style.height + ", offsetHeight: " + popUp.offsetHeight); //-->
<!-- popUp.style.top = Math.round((document.documentElement.clientHeight/3)-(popUp.style.height/2) + document.documentElement.scrollTop) + "px"; //-->
<!-- popUp.style.left = Math.round((document.documentElement.clientWidth/2)-(popUp.style.width/2)) + "px"; //-->
<!-- alert("client: " + document.documentElement.clientHeight + ", popup: " + popUp.style.height + ", scrolltop: " + document.documentElement.scrollTop); //-->
}

function getIt(name)
{
   if(document.getElementById)
   {
      return document.getElementById(name);
   }
   else if(document.layers)
   {
      return document.layers[name];
   }
   else if(document.all)
   {
      return document.all(name);
   }
}

function isVisible(name)
{
   ret = false;
   if(document.getElementById)
   {
      if(document.getElementById(name).style.visibility == 'visible') { ret = true; }
   }
   else if(document.layers)
   {
      if(document.layers[name].visibility='show') { ret = true; }
   }
   else if(document.all)
   {
      if(document.all(name).style.visibility='visible') { ret = true; }
   }
   return ret;
}

function toggleVisible(name)
{
   if (isVisible(name))
   {
      hideIt(name);
   }
   else
   {
      showIt(name);
   }
}

function toggleLayer(name, dotoggle)
{
   if (dotoggle == 1)
   {
      showIt(name);
   }
   else
   {
      hideIt(name);
   }
}

function toggleCheckbox(name)
{
   box = eval(name);
   box.checked = !box.checked;
}
function setCheckbox(name)
{
   box = eval(name);
   if (box.checked == false) box.checked = true;
}
function unsetCheckbox(name)
{
   box = eval(name);
   if (box.checked == true) box.checked = false;
}

function JumpTo(prefix, urlroot)
{
   yearselect = document.getElementById(prefix + "year");
   monthselect = document.getElementById(prefix + "month");
   dayselect = document.getElementById(prefix + "day");

   urlvars = "?y=" + yearselect.options[yearselect.selectedIndex].value;
   urlvars = urlvars + "&m=" + monthselect.options[monthselect.selectedIndex].value;
   urlvars = urlvars + "&d=" + dayselect.options[dayselect.selectedIndex].value;

   location= urlroot + urlvars;
}

function mirrordateselect(datesource, datetarget)
{
   sourceyear = document.getElementById(datesource + "_year");
   sourcemonth = document.getElementById(datesource + "_month");
   sourceday = document.getElementById(datesource + "_day");
   targetyear = document.getElementById(datetarget + "_year");
   targetmonth = document.getElementById(datetarget + "_month");
   targetday = document.getElementById(datetarget + "_day");

   targetyear.selectedIndex = sourceyear.selectedIndex;
   targetmonth.selectedIndex = sourcemonth.selectedIndex;
   targetday.selectedIndex = sourceday.selectedIndex;
}

function setdateselect(datetarget, yearvalue, monthvalue, dayvalue)
{
   targetyear = document.getElementById(datetarget + "_year");
   targetmonth = document.getElementById(datetarget + "_month");
   targetday = document.getElementById(datetarget + "_day");

   for (i = 0; i < targetyear.length; i++)
   {
      if (targetyear[i].value == yearvalue) { targetyear.selectedIndex = i; }
   }
   for (i = 0; i < targetmonth.length; i++)
   {
      if (targetmonth[i].value == monthvalue) { targetmonth.selectedIndex = i; }
   }
   for (i = 0; i < targetday.length; i++)
   {
      if (targetday[i].value == dayvalue) { targetday.selectedIndex = i; }
   }
}

function Select_Value_Set(SelectName, Value)
{
   eval('SelectObject = document.' + SelectName + ';');
}






function unsetCatFilters(num)
{
   for (i = 2; i <= num; i++)
   {
      unsetCheckbox('document.submitform.category' + i)
   }
   for (i = 100; i <= 102; i++)
   {
      unsetCheckbox('document.submitform.category' + i)
   }
}

function unsetSpaceFilters()
{
   var argv = unsetSpaceFilters.arguments;
   var argc = argv.length;

   for (i = 0; i < argc; i++)
   {
      unsetCheckbox('document.submitform.space' + argv[i])
   }
}

function checkAllFilters()
{
   var argv = checkAllFilters.arguments;
   var argc = argv.length;

   newval = 1;
   for (i = 0; i < argc; i++)
   {
      box = eval('document.submitform.all' + argv[i]);
      if (!box.checked) newval  = 0;
   }
   document.getElementById('totalspaces').value = newval;
}

function setAllFilters()
{
   document.getElementById('totalspaces').value = 1;
}

function unsetAllFilters()
{
   document.getElementById('totalspaces').value = 0;
}

function sourcetypeaction()
{
   datasubmit = document.getElementById("datasubmit");
   datasubmit.value = "stage2";
//   mode = datasubmit.value;
//   if (mode == "stage1") mode = "stage2";
   action_submit('submitform', datasubmit.value);
}

function clearSelectPrompt(thisselect)
{
   if (thisselect.options[0].value == 0) thisselect.options[0] = null;
}

function toggleRecurranceDuration()
{
   thisselect = document.getElementById("recurring_duration");
   if (thisselect.options[thisselect.selectedIndex].value == "aftern")
   {
      showIt("recur_times_select");
      hideIt("recur_till_select");
   }
   else if (thisselect.options[thisselect.selectedIndex].value == "bydate")
   {
      showIt("recur_till_select");
      hideIt("recur_times_select");
   }
   else
   {
      hideIt("recur_till_select");
      hideIt("recur_times_select");
   }
}

function toggleBookLimit()
{
   thisselect = document.getElementById("book_range_type");
   if (thisselect.options[1].value == "between")
   {
      thisselect.options[0] = null;
   }
   if (thisselect.options[thisselect.selectedIndex].value == "advance")
   {
      showIt("limitbyadvance");
      hideIt("limitbyrange");
   }
   else
   {
      showIt("limitbyrange");
      hideIt("limitbyadvance");
   }
}

function toggleMultiDay()
{
   thisselect = document.getElementById("book_range_type");
   if (thisselect.options[1].value == "between")
   {
      thisselect.options[0] = null;
   }
   if (thisselect.options[thisselect.selectedIndex].value == "advance")
   {
      showIt("limitbyadvance");
      hideIt("limitbyrange");
   }
   else
   {
      showIt("limitbyrange");
      hideIt("limitbyadvance");
   }
}

function count_submit(formname, dothis, amount)
{
   document.getElementById(formname).datasubmit.value = "hold-" + dothis;
   document.getElementById(formname).option_count.value = amount;
   document.getElementById(formname).submit();
}


function toggleCheckAll(form)
{
   for (i = 0; i < document.getElementById(form).elements.length; i++)
   {
      if(document.getElementById(form).elements[i].type == "checkbox")
      {
         document.getElementById(form).elements[i].checked = !(document.getElementById(form).elements[i].checked);
      }
   }
}

function setTextValue(name, value)
{
   ret = false;
   if(document.getElementById)
   {
      document.getElementById(name).value = value;
      ret = true;
   }
   else if(document.layers)
   {
      document.layers[name].value = value;
      ret = true;
   }
   else if(document.all)
   {
      document.all(name).value = value;
      ret = true;
   }
   return ret;
}

function styleselect()
{
   if (!document.getElementById && !document.createTextNode) { return; }

   var pem_selectclass = 'pem_select';     // class to identify selects
   var pem_listclass = 'pem_list_select';  // class to identify ULs
   var pem_boxclass = 'dropcontainer';     // parent element
   var pem_triggeron = 'activetrigger';    // class for the active trigger link
   var pem_triggeroff = 'trigger';         // class for the inactive trigger link
   var pem_dropdownclosed = 'dropdownhidden';  // closed dropdown
   var pem_dropdownopen = 'dropdownvisible';   // open dropdown

   var count=0;
   var toreplace=new Array();
   var sels=document.getElementsByTagName('select');

   for(var i=0; i < sels.length; i++)
   {
      if (pem_check(sels[i], pem_selectclass))
      {
         var hiddenfield=document.createElement('input');
         hiddenfield.name=sels[i].name;
         hiddenfield.type='hidden';
         hiddenfield.id=sels[i].id;
         hiddenfield.value=sels[i].options[0].value;
         sels[i].parentNode.insertBefore(hiddenfield, sels[i])
         var trigger=document.createElement('a');
         pem_addclass(trigger, pem_triggeroff);
         trigger.href='#';
         trigger.onclick=function()
         {
            pem_swapclass(this, pem_triggeroff, pem_triggeron)
            pem_swapclass(this.parentNode.getElementsByTagName('ul')[0], pem_dropdownclosed, pem_dropdownopen);
            return false;
         }
         trigger.appendChild(document.createTextNode(sels[i].options[0].text));
         sels[i].parentNode.insertBefore(trigger, sels[i]);
         var replaceUL=document.createElement('ul');
         for(var j=0; j < sels[i].getElementsByTagName('option').length; j++)
         {
            var newli=document.createElement('li');
            var newa=document.createElement('a');
            newli.v=sels[i].getElementsByTagName('option')[j].value;
            newli.elm=hiddenfield;
            newli.istrigger=trigger;
            newa.href='#';
            newa.appendChild(document.createTextNode(
            sels[i].getElementsByTagName('option')[j].text));
            newli.onclick=function()
            {
               this.elm.value=this.v;
               pem_swapclass(this.istrigger, pem_triggeron, pem_triggeroff);
               pem_swapclass(this.parentNode, pem_dropdownopen, pem_dropdownclosed)
               this.istrigger.firstChild.nodeValue=this.firstChild.firstChild.nodeValue;
               return false;
            }
            newli.appendChild(newa);
            replaceUL.appendChild(newli);
         }
         pem_addclass(replaceUL, pem_dropdownclosed);
         var div=document.createElement('div');
         div.appendChild(replaceUL);
         pem_addclass(div, pem_boxclass);
         sels[i].parentNode.insertBefore(div, sels[i])
         toreplace[count]=sels[i];
         count++;
      }
   }

   var uls=document.getElementsByTagName('ul');
   for(var i=0; i < uls.length; i++)
   {
      if(pem_check(uls[i], pem_listclass))
      {
         var newform=document.createElement('form');
         var newselect=document.createElement('select');
         for(j=0; j < uls[i].getElementsByTagName('a').length; j++)
         {
            var newopt=document.createElement('option');
            newopt.value=uls[i].getElementsByTagName('a')[j].href;
            newopt.appendChild(document.createTextNode(uls[i].getElementsByTagName('a')[j].innerHTML));
            newselect.appendChild(newopt);
         }
         newselect.onchange=function()
         {
            window.location=this.options[this.selectedIndex].value;
         }
         newform.appendChild(newselect);
         uls[i].parentNode.insertBefore(newform, uls[i]);
         toreplace[count]=uls[i];
         count++;
      }
   }
   for(i=0; i < count; i++)
   {
      toreplace[i].parentNode.removeChild(toreplace[i]);
   }

   function pem_check(o, c)
   {
       return new RegExp('\\b'+c+'\\b').test(o.className);
   }

   function pem_swapclass(o, c1, c2)
   {
      var cn=o.className
      o.className=!pem_check(o, c1)?cn.replace(c2, c1):cn.replace(c1, c2);
   }

   function pem_addclass(o, c)
   {
      if(!pem_check(o, c)){o.className+=o.className==''?c:' '+c; }
   }
}

window.onload = function()
{
   styleselect();
}


var prefsLoaded = false;
var currentFontSize = 12;
var currentFontType = 1;
var currentStyle = "Normal";
// var currentWidth = 990;

function revertStyles() {
   currentFontType = 1;
   setFontFace(1);
   currentFontSize = 12;
   changeFontSize(0);
   currentStyle = "Normal";
   setColor("Normal");
//    currentWidth = 990;
//    setWidth(990);
   }
function togglePrint() {
   if(currentStyle == "Normal") {
//        alert('Print Toggled');
     setColor("Print");
         }
   else {
//        alert('Normal Toggled');
     setColor("Normal");
     }
   }
function toggleColors() {
   if(currentStyle == "Normal") {
     setColor("Print");
         }
   else {
     setColor("Normal");
     }
   }
function setColor(color){
   if(color != "Normal"){
      document.body.className = 'Print';
//    alert('document.body.className is Print');
      currentStyle = "Print";
   }else{
      document.body.className = '';
//    alert('document.body.className is blank');
      currentStyle = "Normal";
   }
}
// function toggleWidth(){
//   currentWidth = parseInt(currentWidth);
//   var newWidth = 990;
//   if(currentWidth == 990){
//      newWidth = 1200;
//   }
//   setWidth(newWidth);
//   currentWidth = newWidth;
//}
//function setWidth(width){
//   if(width != 990){
//      newWidth = 1200;
//      document.body.style.width = '90%';
//   }else{
//      document.body.style.width = '990px';
//   }
//}
function changeFontSize(sizeDifference){
   currentFontSize = parseInt(currentFontSize) + parseInt(sizeDifference);

   if(currentFontType == 1){
      if(currentFontSize > 16){
         currentFontSize = 16;
      }else if(currentFontSize < 8){
         currentFontSize = 8;
      }
   }else{
      if(currentFontSize > 19){
         currentFontSize = 19;
      }else if(currentFontSize < 8){
         currentFontSize = 8;
      }
   }
   setFontSize(currentFontSize);
};
function setFontSize(fontSize){
   var stObj = (document.getElementById) ? document.getElementById('fullpage') : document.all('fullpage');
   stObj.style.fontSize = fontSize + 'px';
};
function toggleSerif(){
   currentFontType = parseInt(currentFontType);
   if(currentFontType == 1){
      currentFontType = 2;
   }else{
      currentFontType = 1;
   }
   setFontFace(currentFontType);
};
function setFontFace(fontType){
   var stObj = (document.getElementById) ? document.getElementById('fullpage') : document.all('fullpage');
   if(fontType == 2){
      stObj.style.fontFamily = 'georgia,times,times new roman,serif';
      changeFontSize(1);
   }else{
      stObj.style.fontFamily = 'verdana,geneva,arial,helvetica,sans-serif';
      changeFontSize(-1);
   }
}

function createCookie(name,value,days) {
  if (days) {
   var date = new Date();
   date.setTime(date.getTime()+(days*24*60*60*1000));
   var expires = "; expires="+date.toGMTString();
  }
  else expires = "";
  document.cookie = name+"="+value+expires+"; path=/";
};

function readCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i=0;i < ca.length;i++) {
   var c = ca[i];
   while (c.charAt(0)==' ') c = c.substring(1,c.length);
   if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
  }
  return null;
};

// window.onload = setUserOptions;

function setUserOptions(){
   if(!prefsLoaded){
      cookie = readCookie("fontFace");
      currentFontType = cookie ? cookie : 1;
      setFontFace(currentFontType);

      cookie = readCookie("fontSize");
      currentFontSize = cookie ? cookie : 12;
      setFontSize(currentFontSize);

//      cookie = readCookie("pageWidth");
//      currentWidth = cookie ? cookie : 990;
//      setWidth(currentWidth);

      cookie = readCookie("pageColor");
      currentStyle = cookie ? cookie : "Normal";
      setColor(currentStyle);

      prefsLoaded = true;
   }

}

// window.onunload = saveSettings;

function saveSettings()
{
  createCookie("fontSize", currentFontSize, 365);
  createCookie("fontFace", currentFontType, 365);
//  createCookie("pageWidth", currentWidth, 365);
  createCookie("pageColor", currentStyle, 365);
}

function popupImage(url, x, y, scroll)
{
  ua = window.navigator.userAgent;
  NS  = (document.layers) ? 1 : 0;
  PCIE = (ua.indexOf( "MSIE " ) && ua.indexOf("Win")) ? 1 : 0;

  if(NS || PCIE) { x += 15; y += 15; } // Grrr...

  pictureWindow = open(url, "pictureWindow", "width=" + x + ",height=" + y + ",scrollbars=" + ((scroll) ? "yes" : "no") + ",status=no,toolbar=no,resizable=yes");

  return false;
}




