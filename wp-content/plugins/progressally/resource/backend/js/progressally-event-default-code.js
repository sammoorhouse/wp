var progressally_event_default_code = {"event":"<div class=\"progressally-event-container\" id=\"progressally-event-container---id--\">\
 <div id=\"progressally-event-container-readonly-view---id--\" style=\"display:none\">\
  <div class=\"progressally-event-header\">\
   <div class=\"progressally-edit-button\" progressally-event-edit=\"--id--\">Edit</div>\
   <div class=\"progressally-event-header-desc\">--id--. Event name</div>\
   <div style=\"clear:both\"></div>\
  </div>\
  <div class=\"progressally-event-readonly-view\">\
   <table class=\"progressally-setting-configure-table\">\
    <tr>\
     <td class=\"progressally-event-readonly-desc-trigger-col\">\
      <div class=\"progressally-event-readonly-desc\">When the user logs in</div>\
     </td>\
     <td class=\"progressally-event-readonly-desc-arrow-col\">\
      <div class=\"progressally-event-readonly-arrow\"></div>\
     </td>\
     <td class=\"progressally-event-readonly-desc-action-col\">\
      <div class=\"progressally-event-readonly-desc\">Add the following tag in the CRM:<ul><li class=\"progressally-readonly-error\">No tag is selected</li></div>\
     </td>\
    </tr>\
   </table>\
  </div>\
 </div>\
 <div id=\"progressally-event-container-edit-view---id--\" >\
  <div class=\"progressally-event-header\">\
   <span class=\"progressally-setting-section-header\">--id--. </span>\
   <input type=\"text\" progressally-param=\"name\" size=\"50\" value=\"Event name\" />\
  </div>\
  <div class=\"progressally-event-edit-view\">\
   <div class=\"progressally-setting-section\">\
    <div class=\"progressally-setting-section-header\">Event trigger condition</div>\
    <div class=\"progressally-setting-configure-block\">\
     <select progressally-param=\"select-trigger-type\" pa-dep-source=\"progressally-event-select-trigger-type---id--\">\
      <option selected=\"selected\" value=\"login\">when user logs in</option>\
      <option  value=\"visit\">when user visits a page</option>\
      <option  value=\"objective\">when user completes objective(s)</option>\
      <option  value=\"accessally\">through AccessAlly custom operation</option>\
     </select>\
    </div>\
\
    <div class=\"progressally-setting-configure-block\" hide-toggle style=\"display:none;\" pa-dep=\"progressally-event-select-trigger-type---id--\" pa-dep-value=\"visit\">\
     <div>Select the pages</div>\
     <div class=\"progressally-page-container\" id=\"progressally-event-page-list-container---id--\">\
      \
      <select variable-name=\"visit-page\" class=\"progressally-autocomplete-add\" entry-type=\"page\">\
       <option value=\"0\"></option>--page-selection--\
      </select>\
     </div>\
    </div>\
    <div class=\"progressally-setting-configure-block\" hide-toggle style=\"display:none;\" pa-dep=\"progressally-event-select-trigger-type---id--\" pa-dep-value=\"objective\">\
     <div>Select the page</div>\
     <select progressally-param=\"page-template-trigger-objective-page\" class=\"progressally-autocomplete-add full-width\" progressally-trigger-objective-update=\"--id--\">\
      <option value=\"0\"></option>--page-selection--\
     </select>\
     <div class=\"progressally-event-objective-list-container\" id=\"progressally-event-trigger-objective-list---id--\" style=\"display:none\">\
      \
     </div>\
    </div>\
   </div>\
   <div class=\"progressally-setting-section\">\
    <div class=\"progressally-setting-section-header\">How often can this event be triggered?</div>\
    <div class=\"progressally-setting-configure-block\">\
     <select progressally-param=\"select-trigger-freq\">\
      <option selected=\"selected\" value=\"once\">Once</option>\
      <option  value=\"infinite\">Each time the event happens</option>\
     </select>\
    </div>\
   </div>\
   <div class=\"progressally-setting-section\">\
    <div class=\"progressally-setting-section-header\">What action will take place when the event is triggered?</div>\
    <div class=\"progressally-setting-configure-block\">\
     <select progressally-param=\"select-action-type\" pa-dep-source=\"progressally-event-select-action-type---id--\">\
      <option selected=\"selected\" value=\"tag\">Add tag(s)</option>\
      <option  value=\"objective\">Check objectives as complete</option>\
     </select>\
    </div>\
    <div class=\"progressally-setting-configure-block\" hide-toggle hide-toggle pa-dep=\"progressally-event-select-action-type---id--\" pa-dep-value=\"tag\">\
     <div class=\"progressally-setting-section-sub-header\">Add the following tag(s) to the contact</div>\
     <div class=\"progressally-tag-container\" id=\"progressally-event-action-tag-list-container---id--\">\
      \
      <select variable-name=\"action-tag\" class=\"progressally-autocomplete-add progressally-tag-input\" entry-type=\"tag\">\
       <option value=\"\"></option>--tag-alphabetic-selection--\
      </select>\
     </div>\
    </div>\
    <div class=\"progressally-setting-configure-block\" hide-toggle style=\"display:none;\" pa-dep=\"progressally-event-select-action-type---id--\" pa-dep-value=\"objective\">\
     <div class=\"progressally-setting-section-sub-header\">Select the page and objective(s) to mark as completed</div>\
     <div class=\"progressally-setting-configure-block\">\
      <select class=\"progressally-autocomplete-add full-width\" progressally-param=\"page-template-action-objective-page\" progressally-action-objective-update=\"--id--\">\
       <option value=\"0\"></option>--page-selection--\
      </select>\
     </div>\
     <div class=\"progressally-event-objective-list-container\" id=\"progressally-event-action-objective-list---id--\" style=\"display:none\">\
      \
     </div>\
    </div>\
   </div>\
   <div class=\"progressally-setting-section\">\
    <div class=\"progressally-delete-button\" progressally-event-delete=\"--id--\">[-] Delete Event</div>\
    <div class=\"progressally-save-button\" progressally-event-save=\"--id--\">Save</div>\
    <div class=\"progressally-cancel-button\" progressally-event-cancel=\"--id--\">Cancel</div>\
    <div style=\"clear:both\"></div>\
   </div>\
  </div>\
 </div>\
</div>",};