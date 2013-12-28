   window.addEvent("domready", function(){
      var today = new Date();

      var calenderbegin = new Calendar("calendarbegin", "calbegin_toggler", {inputField:{date:'date_begin_day',
                                                   month:'date_begin_month',
                                                   year:'date_begin_year'},
                                                   inputType:'select',
                                                   allowWeekendSelection:true,
                                                   allowDaysOffSelection:true,
                                                   selectedDate:'today',
                                                   idPrefix:'calbegin',
                                                   numMonths:6
                                                });

      var calenderend = new Calendar("calendarend", "calend_toggler", {inputField:{date:'date_end_day',
                                                   month:'date_end_month',
                                                   year:'date_end_year'},
                                                   inputType:'select',
                                                   allowWeekendSelection:true,
                                                   allowDaysOffSelection:true,
                                                   selectedDate:'today',
                                                   idPrefix:'calend',
                                                   numMonths:6
                                                });

   });