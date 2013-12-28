/* ========================== FILE INFORMATION ================================= 
phxEventManager :: date-selector.js

Maintains date integrity by adjusting day options based on month and day selections.
Script based on "True Date Selector" by: Lee Hinder, lee.hinder@ntlworld.com 
============================================================================= */

// February has 28 days unless the year is divisible by four. 
// If it is the turn of the century then the century year must also be 
// divisible by 400 when it has 29 days
function DaysInFebruary (year) 
{
   return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
}

// Function for returning how many days there are in a month including leap years
function DaysInMonth(WhichMonth, WhichYear) 
{
   var DaysInMonth = 31;
   if (WhichMonth == "4" || WhichMonth == "6" || WhichMonth == "9" || WhichMonth == "11")
      DaysInMonth = 30;
   if (WhichMonth == "2")
      DaysInMonth = DaysInFebruary( WhichYear );
   return DaysInMonth;
}

// Function to change the available days in a months
function ChangeOptionDays(formObj, prefix) 
{
   var DaysObject = eval("formObj." + prefix + "day");
   var MonthObject = eval("formObj." + prefix + "month");
   var YearObject = eval("formObj." + prefix + "year");
   
   var DaySelIdx = DaysObject.selectedIndex;
   Month = parseInt(MonthObject[MonthObject.selectedIndex].value);
   Year = parseInt(YearObject[YearObject.selectedIndex].value);

   var DaysForThisSelection = DaysInMonth(Month, Year);
   var CurrentDaysInSelection = DaysObject.length;
   if (CurrentDaysInSelection > DaysForThisSelection) 
   {
      for (i=0; i<(CurrentDaysInSelection-DaysForThisSelection); i++) 
      {
         DaysObject.options[DaysObject.options.length - 1] = null
      }
   }
   if (DaysForThisSelection > CurrentDaysInSelection) 
   {
      for (i=0; i<DaysForThisSelection; i++) 
      {
         DaysObject.options[i] = new Option(eval(i + 1));
      }
   }
   if (DaysObject.selectedIndex < 0) DaysObject.selectedIndex = 0;
   if (DaySelIdx >= DaysForThisSelection)
      DaysObject.selectedIndex = DaysForThisSelection-1;
   else
      DaysObject.selectedIndex = DaySelIdx;
}
