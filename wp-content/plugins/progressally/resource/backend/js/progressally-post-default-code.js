var progressally_post_default_code = {"objective":"<tr class=\"progressally-objective-list-row\" id=\"progressally-objective---id--\" element-id=\"--id--\">\
 <td class=\"objective-list-move-col\">\
  <input type='hidden' progressally-param='objective-order[]' value=\"--id--\" />\
  <div class=\"progressally-setting-list-order-move\"></div>\
 </td>\
 <td class=\"objective-list-type-col\">\
  <div class=\"objective-list-description-container\">\
   <select id=\"progressally-seek-type---id--\" progressally-objective-seek-type=\"--id--\" progressally-param=\"objectives[--id--][seek-type]\" pa-dep-source=\"progressally-seek-type---id--\">\
    <option value='none' selected=\"selected\">Text</option>\
<option value='vimeo' >Video - Vimeo</option>\
<option value='youtube' >Video - Youtube</option>\
<option value='wistia' >Video - Wistia</option>\
<option value='quiz' >Quiz</option>\
<option value='post' >Page / Post</option>\
<option value='note' >Note</option>\
   </select>\
  </div>\
 </td>\
 <td class=\"objective-list-description-col\">\
  <div class=\"objective-list-description-container\">\
   <input type=\"text\" class=\"full-width\" progressally-objective-name=\"--id--\" progressally-param=\"objectives[--id--][description]\" value=\"\" />\
   <div hide-toggle style=\"display:none;\" pa-dep=\"progressally-seek-type---id--\" pa-dep-value=\"vimeo,youtube,wistia\">\
    <div class=\"objective-list-seek-time-container\">\
     <label for=\"progressally-seek-video-id---id--\">Play Video #</label>\
     <input id=\"progressally-seek-video-id---id--\" size=\"2\" type='text' progressally-param='objectives[--id--][seek-id]' value=\"0\" />\
     At\
     <input size=\"3\" type='text' progressally-param='objectives[--id--][seek-time-minute]' value=\"0\" />\
     minutes\
     <input size=\"2\" type='text' progressally-param='objectives[--id--][seek-time-second]' value=\"0\" />\
     seconds\
    </div>\
    <div class=\"objective-list-seek-time-container\">\
     <input id=\"progressally-checked-complete-video---id--\" progressally-objective-video-complete=\"--id--\" pa-dep-source=\"progressally-checked-complete-video---id--\" type=\"checkbox\"\
         progressally-param='objectives[--id--][checked-complete-video]'  value=\"yes\" />\
     <label for=\"progressally-checked-complete-video---id--\">This objective will be marked complete when the video is watched to...</label>\
    </div>\
    <div class=\"objective-list-seek-time-container\" hide-toggle style=\"display:none;\" pa-dep=\"progressally-checked-complete-video---id--\" pa-dep-value=\"yes\">\
     <input size=\"3\" type='text' progressally-param='objectives[--id--][complete-time-minute]' value=\"0\" />\
     minutes\
     <input size=\"2\" type='text' progressally-param='objectives[--id--][complete-time-second]' value=\"0\" />\
     seconds\
    </div>\
   </div>\
   <div class=\"objective-list-post-select-container\" hide-toggle style=\"display:none;\" pa-dep=\"progressally-seek-type---id--\" pa-dep-value=\"post\">\
    <select progressally-param=\"objectives[--id--][ref-post-id]\" class=\"progressally-autocomplete-add full-width\">\
     <option value=\"-1\"></option>\
     --select-page-options--\
    </select>\
   </div>\
   <div class=\"objective-list-note-select-container\" hide-toggle style=\"display:none;\" pa-dep=\"progressally-seek-type---id--\" pa-dep-value=\"note\">\
    <select progressally-param=\"objectives[--id--][note-id]\" progressally-objective-note-select=\"--id--\" class=\"full-width\">\
     <option value=\"0\"></option>\
     --select-note-options--\
    </select>\
   </div>\
  </div>\
 </td>\
 <td class=\"objective-list-delete-col\"><div class=\"progressally-delete-button progressally-objective-delete\" progressally-delete-element=\"#progressally-objective---id--\">Delete</div></td>\
</tr>","question":"<div class=\"progressally-setting-question-block \" id=\"progressally-question-block---qid--\">\
 <input type=\"hidden\" progressally-param=\"quiz[question-order][]\" value=\"--qid--\" />\
 <div class=\"progressally-setting-question-header\" progressally-toggle-target=\"#progressally-question-toggle---qid--\" id=\"progressally-setting-question-header---qid--\">\
  <div class=\"progressally-view-toggle-block\">\
   <input progressally-param=\"quiz[question][--qid--][checked-is-open]\"  type=\"checkbox\" value=\"yes\"\
       toggle-class=\"progressally-question-opened\" progressally-toggle-element=\"#progressally-question-block---qid--\" min-height=\"40\"\
       min-height-element=\"#progressally-setting-question-header---qid--\" class=\"progressally-quiz-question-toggle\"\
       pa-dep-source=\"progressally-question-toggle---qid--\" id=\"progressally-question-toggle---qid--\">\
   <label hide-toggle pa-dep=\"progressally-question-toggle---qid--\" pa-dep-value=\"no\">&#x25BC;</label>\
   <label hide-toggle style=\"display:none;\" pa-dep=\"progressally-question-toggle---qid--\" pa-dep-value=\"yes\">&#x25B2;</label>\
  </div>\
  <div class=\"progressally-setting-quiz-order-move\"></div>\
  <div class=\"progressally-quiz-question\" progressally-quiz-update-target=\"question-html---qid--\"></div>\
  <div style=\"clear:both;\"></div>\
  <div class=\"progressally-setting-configure-block\">\
   <ul class=\"progressally-quiz-display\" id=\"progressally-quiz-preview-vertical---qid--\"\
    hide-toggle style=\"display:none;\" pa-dep=\"progressally-quiz-choice-display\" pa-dep-value=\"vertical\">\
    <li class=\"progressally-quiz-choice-item progressally-quiz-choice-dependent---qid---1\" id=\"progressally-quiz-choice-preview---qid---1\">\
 <input type=\"radio\" name=\"progressally-question---qid--\" id=\"progressally-quiz-preview---qid---1\" />\
 <label for=\"progressally-quiz-preview---qid---1\" progressally-quiz-update-target=\"choice-html---qid---1\"></label>\
</li><li class=\"progressally-quiz-choice-item progressally-quiz-choice-dependent---qid---2\" id=\"progressally-quiz-choice-preview---qid---2\">\
 <input type=\"radio\" name=\"progressally-question---qid--\" id=\"progressally-quiz-preview---qid---2\" />\
 <label for=\"progressally-quiz-preview---qid---2\" progressally-quiz-update-target=\"choice-html---qid---2\"></label>\
</li><li class=\"progressally-quiz-choice-item progressally-quiz-choice-dependent---qid---3\" id=\"progressally-quiz-choice-preview---qid---3\">\
 <input type=\"radio\" name=\"progressally-question---qid--\" id=\"progressally-quiz-preview---qid---3\" />\
 <label for=\"progressally-quiz-preview---qid---3\" progressally-quiz-update-target=\"choice-html---qid---3\"></label>\
</li><li class=\"progressally-quiz-choice-item progressally-quiz-choice-dependent---qid---4\" id=\"progressally-quiz-choice-preview---qid---4\">\
 <input type=\"radio\" name=\"progressally-question---qid--\" id=\"progressally-quiz-preview---qid---4\" />\
 <label for=\"progressally-quiz-preview---qid---4\" progressally-quiz-update-target=\"choice-html---qid---4\"></label>\
</li>\
   </ul>\
   <table class=\"progressally-quiz-display-horizontal\" hide-toggle style=\"display:none;\" pa-dep=\"progressally-quiz-choice-display\" pa-dep-value=\"horizontal\">\
    <tbody>\
     <tr id=\"progressally-quiz-preview-horizontal---qid--\">\
      <td class=\"progressally-quiz-choice-item progressally-quiz-choice-item-horizontal progressally-quiz-choice-dependent---qid---1\" id=\"progressally-quiz-choice-preview-horizontal---qid---1\">\
 <input type=\"radio\" name=\"progressally-question---qid--\" id=\"progressally-quiz-preview-horizontal---qid---1\"\
     class=\"progressally-quiz-choice-input\" />\
 <label for=\"progressally-quiz-preview-horizontal---qid---1\" progressally-quiz-update-target=\"choice-html---qid---1\"\
     class=\"progressally-quiz-choice-label\"></label>\
</td><td class=\"progressally-quiz-choice-item progressally-quiz-choice-item-horizontal progressally-quiz-choice-dependent---qid---2\" id=\"progressally-quiz-choice-preview-horizontal---qid---2\">\
 <input type=\"radio\" name=\"progressally-question---qid--\" id=\"progressally-quiz-preview-horizontal---qid---2\"\
     class=\"progressally-quiz-choice-input\" />\
 <label for=\"progressally-quiz-preview-horizontal---qid---2\" progressally-quiz-update-target=\"choice-html---qid---2\"\
     class=\"progressally-quiz-choice-label\"></label>\
</td><td class=\"progressally-quiz-choice-item progressally-quiz-choice-item-horizontal progressally-quiz-choice-dependent---qid---3\" id=\"progressally-quiz-choice-preview-horizontal---qid---3\">\
 <input type=\"radio\" name=\"progressally-question---qid--\" id=\"progressally-quiz-preview-horizontal---qid---3\"\
     class=\"progressally-quiz-choice-input\" />\
 <label for=\"progressally-quiz-preview-horizontal---qid---3\" progressally-quiz-update-target=\"choice-html---qid---3\"\
     class=\"progressally-quiz-choice-label\"></label>\
</td><td class=\"progressally-quiz-choice-item progressally-quiz-choice-item-horizontal progressally-quiz-choice-dependent---qid---4\" id=\"progressally-quiz-choice-preview-horizontal---qid---4\">\
 <input type=\"radio\" name=\"progressally-question---qid--\" id=\"progressally-quiz-preview-horizontal---qid---4\"\
     class=\"progressally-quiz-choice-input\" />\
 <label for=\"progressally-quiz-preview-horizontal---qid---4\" progressally-quiz-update-target=\"choice-html---qid---4\"\
     class=\"progressally-quiz-choice-label\"></label>\
</td>\
     </tr>\
    </tbody>\
   </table>\
  </div>\
 </div>\
 <div hide-toggle style=\"display:none;\" pa-dep=\"progressally-question-toggle---qid--\" pa-dep-value=\"yes\">\
  <div class=\"progressally-setting-configure-block\">\
   <div class=\"progressally-setting-configure-block progressally-quiz-header\">Question Text (HTML code allowed)</div>\
   <textarea class=\"full-width\" progressally-quiz-update-source=\"question-html---qid--\" progressally-param=\"quiz[question][--qid--][question-html]\" rows=\"5\">Question text goes here?</textarea>\
  </div>\
  <div class=\"progressally-setting-configure-block\" s--quiz-type-survey--w hide-toggle pa-dep=\"progressally-quiz-type\" pa-dep-value=\"survey\">\
   <div class=\"progressally-setting-configure-block progressally-quiz-header\">Outcome weight</div>\
   <div class=\"progressally-setting-section-help-text\">You can assign higher weight to a question so the answer will count for more.</div>\
   <input size=\"4\" type=\"text\" progressally-param=\"quiz[question][--qid--][survey-weight]\" value=\"1\"/>\
  </div>\
  <div class=\"progressally-setting-configure-block\">\
   <div class=\"progressally-setting-configure-block\">\
    <span class=\"progressally-quiz-header\">Choices</span>\
    <div class=\"progressally-replace-choice-link\"\
      progressally-warning=\"The existing choices will be removed. Continue?\" progressally-replace-choice-1-10=\"--qid--\"\
      hide-toggle style=\"display:none;\" pa-dep=\"progressally-quiz-choice-display\" pa-dep-value=\"horizontal\">Replace existing choices with an &#39;1&#39; - &#39;10&#39; scale</div>\
    <div style=\"clear:both\"></div>\
   </div>\
   <input type=\"hidden\" id=\"progressally-max-choice-id---qid--\" value=\"4\" />\
   <div class=\"progressally-quiz-choice-container\">\
    <table class=\"progressally-quiz-choice-listing\">\
     <tbody id=\"progressally-quiz-choice-listing-container---qid--\">\
      <tr class=\"progressally-quiz-choice-header\">\
       <td class=\"progressally-quiz-item-icon-col\"></td>\
       <td class=\"progressally-quiz-item-icon-description-col\"></td>\
       <td class=\"progressally-quiz-item-correct-col\" s--quiz-type-score--w hide-toggle pa-dep=\"progressally-quiz-type\" pa-dep-value=\"score\">\
        MARK CORRECT ANSWER\
       </td>\
       <td class=\"progressally-quiz-item-outcome-col\" s--quiz-type-survey--w hide-toggle pa-dep=\"progressally-quiz-type\" pa-dep-value=\"survey\">\
        OUTCOME\
       </td>\
       <td class=\"progressally-quiz-item-outcome-col\" s--quiz-type-segment--w hide-toggle pa-dep=\"progressally-quiz-type\" pa-dep-value=\"segment\">\
        SCORE\
       </td>\
       <td class=\"progressally-quiz-item-delete-col\"></td>\
      </tr>\
      <tr class=\"progressally-quiz-choice-item progressally-quiz-choice-dependent---qid---1\" id=\"progressally-quiz-choice---qid---1\">\
 <td class=\"progressally-quiz-item-icon-col\">*</td>\
 <td class=\"progressally-quiz-item-description-col\">\
  <input type=\"hidden\" progressally-param=\"quiz[question][--qid--][order][]\"\
      value=\"1\" />\
  <input class=\"full-width\" progressally-quiz-update-source=\"choice-html---qid---1\" type=\"text\" progressally-param=\"quiz[question][--qid--][choice][1][html]\"\
      value=\"Choice text goes here\" />\
 </td>\
 <td class=\"progressally-quiz-item-correct-col\" s--quiz-type-score--w hide-toggle pa-dep=\"progressally-quiz-type\" pa-dep-value=\"score\">\
  <input class=\"progressally-quiz-item-correct\" type=\"radio\" id=\"progressally-quiz-correct-checkbox---qid---1\"\
      name=\"quiz[question][--qid--][radio-correct]\"\
      checked=\"checked\" value=\"1\" />\
  <label for=\"progressally-quiz-correct-checkbox---qid---1\" class=\"progressally-quiz-item-correct-label\"></label>\
 </td>\
 <td class=\"progressally-quiz-item-outcome-col\" s--quiz-type-survey--w hide-toggle pa-dep=\"progressally-quiz-type\" pa-dep-value=\"survey\">\
  <select class=\"full-width progressally-outcome-selection\"\
      progressally-param=\"quiz[question][--qid--][choice][1][select-survey-outcome]\">\
   <option value=\"\">None</option>\
   --outcome-options--\
  </select>\
 </td>\
 <td class=\"progressally-quiz-item-outcome-col\" s--quiz-type-segment--w hide-toggle pa-dep=\"progressally-quiz-type\" pa-dep-value=\"segment\">\
  <input class=\"full-width\" type=\"text\" progressally-param=\"quiz[question][--qid--][choice][1][segment-score]\"\
      value=\"1\" />\
 </td>\
 <td class=\"progressally-quiz-item-delete-col\">\
  <div class=\"progressally-quiz-image-add-button progressally-add-choice\" question-id=\"--qid--\" choice-id=\"1\"></div>\
  <div class=\"progressally-quiz-image-delete-button progressally-quiz-choice-delete\" question-id=\"--qid--\" choice-id=\"1\"\
    progressally-delete-warning=\"Deleting a choice cannot be undone. Continue?\"></div>\
 </td>\
</tr><tr class=\"progressally-quiz-choice-item progressally-quiz-choice-dependent---qid---2\" id=\"progressally-quiz-choice---qid---2\">\
 <td class=\"progressally-quiz-item-icon-col\">*</td>\
 <td class=\"progressally-quiz-item-description-col\">\
  <input type=\"hidden\" progressally-param=\"quiz[question][--qid--][order][]\"\
      value=\"2\" />\
  <input class=\"full-width\" progressally-quiz-update-source=\"choice-html---qid---2\" type=\"text\" progressally-param=\"quiz[question][--qid--][choice][2][html]\"\
      value=\"Choice text goes here\" />\
 </td>\
 <td class=\"progressally-quiz-item-correct-col\" s--quiz-type-score--w hide-toggle pa-dep=\"progressally-quiz-type\" pa-dep-value=\"score\">\
  <input class=\"progressally-quiz-item-correct\" type=\"radio\" id=\"progressally-quiz-correct-checkbox---qid---2\"\
      name=\"quiz[question][--qid--][radio-correct]\"\
       value=\"2\" />\
  <label for=\"progressally-quiz-correct-checkbox---qid---2\" class=\"progressally-quiz-item-correct-label\"></label>\
 </td>\
 <td class=\"progressally-quiz-item-outcome-col\" s--quiz-type-survey--w hide-toggle pa-dep=\"progressally-quiz-type\" pa-dep-value=\"survey\">\
  <select class=\"full-width progressally-outcome-selection\"\
      progressally-param=\"quiz[question][--qid--][choice][2][select-survey-outcome]\">\
   <option value=\"\">None</option>\
   --outcome-options--\
  </select>\
 </td>\
 <td class=\"progressally-quiz-item-outcome-col\" s--quiz-type-segment--w hide-toggle pa-dep=\"progressally-quiz-type\" pa-dep-value=\"segment\">\
  <input class=\"full-width\" type=\"text\" progressally-param=\"quiz[question][--qid--][choice][2][segment-score]\"\
      value=\"1\" />\
 </td>\
 <td class=\"progressally-quiz-item-delete-col\">\
  <div class=\"progressally-quiz-image-add-button progressally-add-choice\" question-id=\"--qid--\" choice-id=\"2\"></div>\
  <div class=\"progressally-quiz-image-delete-button progressally-quiz-choice-delete\" question-id=\"--qid--\" choice-id=\"2\"\
    progressally-delete-warning=\"Deleting a choice cannot be undone. Continue?\"></div>\
 </td>\
</tr><tr class=\"progressally-quiz-choice-item progressally-quiz-choice-dependent---qid---3\" id=\"progressally-quiz-choice---qid---3\">\
 <td class=\"progressally-quiz-item-icon-col\">*</td>\
 <td class=\"progressally-quiz-item-description-col\">\
  <input type=\"hidden\" progressally-param=\"quiz[question][--qid--][order][]\"\
      value=\"3\" />\
  <input class=\"full-width\" progressally-quiz-update-source=\"choice-html---qid---3\" type=\"text\" progressally-param=\"quiz[question][--qid--][choice][3][html]\"\
      value=\"Choice text goes here\" />\
 </td>\
 <td class=\"progressally-quiz-item-correct-col\" s--quiz-type-score--w hide-toggle pa-dep=\"progressally-quiz-type\" pa-dep-value=\"score\">\
  <input class=\"progressally-quiz-item-correct\" type=\"radio\" id=\"progressally-quiz-correct-checkbox---qid---3\"\
      name=\"quiz[question][--qid--][radio-correct]\"\
       value=\"3\" />\
  <label for=\"progressally-quiz-correct-checkbox---qid---3\" class=\"progressally-quiz-item-correct-label\"></label>\
 </td>\
 <td class=\"progressally-quiz-item-outcome-col\" s--quiz-type-survey--w hide-toggle pa-dep=\"progressally-quiz-type\" pa-dep-value=\"survey\">\
  <select class=\"full-width progressally-outcome-selection\"\
      progressally-param=\"quiz[question][--qid--][choice][3][select-survey-outcome]\">\
   <option value=\"\">None</option>\
   --outcome-options--\
  </select>\
 </td>\
 <td class=\"progressally-quiz-item-outcome-col\" s--quiz-type-segment--w hide-toggle pa-dep=\"progressally-quiz-type\" pa-dep-value=\"segment\">\
  <input class=\"full-width\" type=\"text\" progressally-param=\"quiz[question][--qid--][choice][3][segment-score]\"\
      value=\"1\" />\
 </td>\
 <td class=\"progressally-quiz-item-delete-col\">\
  <div class=\"progressally-quiz-image-add-button progressally-add-choice\" question-id=\"--qid--\" choice-id=\"3\"></div>\
  <div class=\"progressally-quiz-image-delete-button progressally-quiz-choice-delete\" question-id=\"--qid--\" choice-id=\"3\"\
    progressally-delete-warning=\"Deleting a choice cannot be undone. Continue?\"></div>\
 </td>\
</tr><tr class=\"progressally-quiz-choice-item progressally-quiz-choice-dependent---qid---4\" id=\"progressally-quiz-choice---qid---4\">\
 <td class=\"progressally-quiz-item-icon-col\">*</td>\
 <td class=\"progressally-quiz-item-description-col\">\
  <input type=\"hidden\" progressally-param=\"quiz[question][--qid--][order][]\"\
      value=\"4\" />\
  <input class=\"full-width\" progressally-quiz-update-source=\"choice-html---qid---4\" type=\"text\" progressally-param=\"quiz[question][--qid--][choice][4][html]\"\
      value=\"Choice text goes here\" />\
 </td>\
 <td class=\"progressally-quiz-item-correct-col\" s--quiz-type-score--w hide-toggle pa-dep=\"progressally-quiz-type\" pa-dep-value=\"score\">\
  <input class=\"progressally-quiz-item-correct\" type=\"radio\" id=\"progressally-quiz-correct-checkbox---qid---4\"\
      name=\"quiz[question][--qid--][radio-correct]\"\
       value=\"4\" />\
  <label for=\"progressally-quiz-correct-checkbox---qid---4\" class=\"progressally-quiz-item-correct-label\"></label>\
 </td>\
 <td class=\"progressally-quiz-item-outcome-col\" s--quiz-type-survey--w hide-toggle pa-dep=\"progressally-quiz-type\" pa-dep-value=\"survey\">\
  <select class=\"full-width progressally-outcome-selection\"\
      progressally-param=\"quiz[question][--qid--][choice][4][select-survey-outcome]\">\
   <option value=\"\">None</option>\
   --outcome-options--\
  </select>\
 </td>\
 <td class=\"progressally-quiz-item-outcome-col\" s--quiz-type-segment--w hide-toggle pa-dep=\"progressally-quiz-type\" pa-dep-value=\"segment\">\
  <input class=\"full-width\" type=\"text\" progressally-param=\"quiz[question][--qid--][choice][4][segment-score]\"\
      value=\"1\" />\
 </td>\
 <td class=\"progressally-quiz-item-delete-col\">\
  <div class=\"progressally-quiz-image-add-button progressally-add-choice\" question-id=\"--qid--\" choice-id=\"4\"></div>\
  <div class=\"progressally-quiz-image-delete-button progressally-quiz-choice-delete\" question-id=\"--qid--\" choice-id=\"4\"\
    progressally-delete-warning=\"Deleting a choice cannot be undone. Continue?\"></div>\
 </td>\
</tr>\
     </tbody>\
    </table>\
   </div>\
  </div>\
  <div class=\"progressally-setting-configure-block\" s--quiz-type-score--w hide-toggle pa-dep=\"progressally-quiz-type\" pa-dep-value=\"score\">\
   <div class=\"progressally-setting-configure-block progressally-quiz-header\">Incorrect Answer Message Text (HTML code allowed)</div>\
   <textarea class=\"full-width\" progressally-param=\"quiz[question][--qid--][incorrect-message-html]\" rows=\"5\">Incorrect.</textarea>\
  </div>\
  <div>\
   <div class=\"progressally-clone-button\" progressally-clone-question=\"--qid--\">Clone Question</div>\
   <div class=\"progressally-delete-button progressally-float-right\" progressally-delete-element=\"#progressally-question-block---qid--\"\
     progressally-delete-warning=\"Deleting a question cannot be undone. Continue?\">[-] Delete Question</div>\
   <div style=\"clear:both\"></div>\
  </div>\
 </div>\
</div>","choice":"<tr class=\"progressally-quiz-choice-item progressally-quiz-choice-dependent---qid-----cid--\" id=\"progressally-quiz-choice---qid-----cid--\">\
 <td class=\"progressally-quiz-item-icon-col\">*</td>\
 <td class=\"progressally-quiz-item-description-col\">\
  <input type=\"hidden\" progressally-param=\"quiz[question][--qid--][order][]\"\
      value=\"--cid--\" />\
  <input class=\"full-width\" progressally-quiz-update-source=\"choice-html---qid-----cid--\" type=\"text\" progressally-param=\"quiz[question][--qid--][choice][--cid--][html]\"\
      value=\"--clabel--\" />\
 </td>\
 <td class=\"progressally-quiz-item-correct-col\" s--quiz-type-score--w hide-toggle pa-dep=\"progressally-quiz-type\" pa-dep-value=\"score\">\
  <input class=\"progressally-quiz-item-correct\" type=\"radio\" id=\"progressally-quiz-correct-checkbox---qid-----cid--\"\
      name=\"quiz[question][--qid--][radio-correct]\"\
       value=\"--cid--\" />\
  <label for=\"progressally-quiz-correct-checkbox---qid-----cid--\" class=\"progressally-quiz-item-correct-label\"></label>\
 </td>\
 <td class=\"progressally-quiz-item-outcome-col\" s--quiz-type-survey--w hide-toggle pa-dep=\"progressally-quiz-type\" pa-dep-value=\"survey\">\
  <select class=\"full-width progressally-outcome-selection\"\
      progressally-param=\"quiz[question][--qid--][choice][--cid--][select-survey-outcome]\">\
   <option value=\"\">None</option>\
   --outcome-options--\
  </select>\
 </td>\
 <td class=\"progressally-quiz-item-outcome-col\" s--quiz-type-segment--w hide-toggle pa-dep=\"progressally-quiz-type\" pa-dep-value=\"segment\">\
  <input class=\"full-width\" type=\"text\" progressally-param=\"quiz[question][--qid--][choice][--cid--][segment-score]\"\
      value=\"--segment-score--\" />\
 </td>\
 <td class=\"progressally-quiz-item-delete-col\">\
  <div class=\"progressally-quiz-image-add-button progressally-add-choice\" question-id=\"--qid--\" choice-id=\"--cid--\"></div>\
  <div class=\"progressally-quiz-image-delete-button progressally-quiz-choice-delete\" question-id=\"--qid--\" choice-id=\"--cid--\"\
    progressally-delete-warning=\"Deleting a choice cannot be undone. Continue?\"></div>\
 </td>\
</tr>","choice-label":"Choice text goes here","choice-preview-vertical":"<li class=\"progressally-quiz-choice-item progressally-quiz-choice-dependent---qid-----cid--\" id=\"progressally-quiz-choice-preview---qid-----cid--\">\
 <input type=\"radio\" name=\"progressally-question---qid--\" id=\"progressally-quiz-preview---qid-----cid--\" />\
 <label for=\"progressally-quiz-preview---qid-----cid--\" progressally-quiz-update-target=\"choice-html---qid-----cid--\"></label>\
</li>","choice-preview-horizontal":"<td class=\"progressally-quiz-choice-item progressally-quiz-choice-item-horizontal progressally-quiz-choice-dependent---qid-----cid--\" id=\"progressally-quiz-choice-preview-horizontal---qid-----cid--\">\
 <input type=\"radio\" name=\"progressally-question---qid--\" id=\"progressally-quiz-preview-horizontal---qid-----cid--\"\
     class=\"progressally-quiz-choice-input\" />\
 <label for=\"progressally-quiz-preview-horizontal---qid-----cid--\" progressally-quiz-update-target=\"choice-html---qid-----cid--\"\
     class=\"progressally-quiz-choice-label\"></label>\
</td>","outcome":"<div class=\"progressally-setting-outcome-block\" id=\"progressally-quiz-outcome---outcome-id--\">\
 <div class=\"progressally-outcome-header\" toggle-target=\"#progressally-outcome-toggle---outcome-id--\" id=\"progressally-outcome-header---outcome-id--\">\
  <div class=\"progressally-view-toggle-block\">\
   <input progressally-param=\"quiz[survey-outcome][--outcome-id--][checked-is-open]\"  type=\"checkbox\" value=\"yes\" toggle-group=\"progressally-outcome\"\
       toggle-class=\"progressally-item-opened\" progressally-toggle-element=\"#progressally-quiz-outcome---outcome-id--\" min-height=\"40\" min-height-element=\"#progressally-outcome-header---outcome-id--\"\
       pa-dep-source=\"progressally-outcome-toggle---outcome-id--\" id=\"progressally-outcome-toggle---outcome-id--\">\
   <label hide-toggle pa-dep=\"progressally-outcome-toggle---outcome-id--\" pa-dep-value=\"no\">&#x25BC;</label>\
   <label hide-toggle style=\"display:none;\" pa-dep=\"progressally-outcome-toggle---outcome-id--\" pa-dep-value=\"yes\">&#x25B2;</label>\
  </div>\
  <div class=\"progressally-name-display-block\">\
   <div class=\"progressally-name-display\" progressally-click-edit-show=\"--outcome-id--\">\
    <table class=\"progressally-header-table\">\
     <tbody>\
      <tr>\
       <td class=\"progressally-number-col\">Outcome --outcome-id--. </td>\
       <td class=\"progressally-name-label-col\"><div class=\"progressally-name-label\" progressally-click-edit-display=\"--outcome-id--\">Survey Outcome</div></td>\
       <td class=\"progressally-name-edit-col\"><div class=\"progressally-pencil-icon\" progressally-click-edit-trigger=\"--outcome-id--\"></div></td>\
      </tr>\
     </tbody>\
    </table>\
   </div>\
   <input progressally-param=\"quiz[survey-outcome][--outcome-id--][name]\" class=\"progressally-name-edit progressally-outcome-name-edit full-width\" progressally-click-edit-input=\"--outcome-id--\"\
       style=\"display:none;\" value=\"Survey Outcome\" type=\"text\" />\
  </div>\
 </div>\
 <div hide-toggle style=\"display:none;\" pa-dep=\"progressally-outcome-toggle---outcome-id--\" pa-dep-value=\"yes\">\
  <div class='progressally-outcome-section'>\
   <div class=\"progressally-setting-section-help-text\">You can use HTML code in the outcome code.</div>\
   <textarea class=\"full-width\" progressally-param=\"quiz[survey-outcome][--outcome-id--][html]\" rows=\"5\">&lt;div class=&quot;progressally-quiz-result&quot;&gt;Outcome Text&lt;/div&gt;</textarea>\
  </div>\
  <div class=\"progressally-outcome-section\" --has-valid-popup-selection-->\
   <div class=\"progressally-setting-section-sub-header\">Show a PopupAlly Pro popup with this outcome</div>\
   <div class=\"progressally-setting-section-help-text\">\
    <div class=\"progressally-info-icon\"></div>\
    Learn more in our <a href=\"https://access.accessally.com/progressally-quiz-outcome-optin\" target=\"_blank\">video tutorial</a>.\
   </div>\
   <div class=\"progressally-setting-configure-block\">\
    <select progressally-param=\"quiz[survey-outcome][--outcome-id--][select-popup-type]\"\
      pa-dep-source=\"progressally-survey-outcome---outcome-id---select-popup-type\">\
     <option selected=\"selected\" value=\"none\">Do not show a popup</option>\
     <option  value=\"popup\">Show the selected popup</option>\
     <option  value=\"embedded\">Add the selected popup as an embedded opt-in at the end of the outcome text</option>\
    </select>\
   </div>\
   <div class=\"progressally-setting-configure-block\"\
     hide-toggle style=\"display:none;\" pa-dep=\"progressally-survey-outcome---outcome-id---select-popup-type\" pa-dep-value-not=\"none\">\
    Popup to show\
    <select progressally-param=\"quiz[survey-outcome][--outcome-id--][optin-popup]\">\
     <option value=\"\"></option>\
     --popup-selection--\
    </select>\
   </div>\
  </div>\
  <div class=\"progressally-outcome-section\" --has-valid-tag-selection-->\
   <div class=\"progressally-setting-configure-block\">\
    <div class=\"progressally-setting-section-sub-header\">(Optional) Apply a tag when this outcome is reached:</div>\
    <select class=\"progressally-autocomplete-add progressally-tag-input full-width\" progressally-param=\"quiz[survey-outcome][--outcome-id--][access-tag]\">\
     <option value=\"\"></option>\
     --tag-selection--\
    </select>\
   </div>\
  </div>\
  <div class=\"progressally-outcome-section\" --has-valid-field-selection-->\
   <div class=\"progressally-setting-configure-block\">\
    <div class=\"progressally-setting-section-sub-header\">(Optional) Assign the outcome score to the custom field</div>\
    <select class=\"full-width\" progressally-param=\"quiz[survey-outcome][--outcome-id--][value-field]\">\
     <option value=\"\"></option>\
     --field-selection--\
    </select>\
   </div>\
  </div>\
 </div>\
</div>","outcome-option":"<option progressally-outcome-choice=\"--outcome-id--\" value=\"--outcome-id--\" s--select-survey-outcome---outcome-id----d>--outcome-id--. Survey Outcome</option>","grade-outcome":"<div class=\"progressally-setting-outcome-block \" id=\"progressally-grade-outcome---outcome-id--\">\
 <div class=\"progressally-outcome-header\" toggle-target=\"#progressally-grade-outcome-toggle---outcome-id--\" id=\"progressally-grade-outcome-header---outcome-id--\">\
  <div class=\"progressally-view-toggle-block\">\
   <input progressally-param=\"quiz[grade-outcome][--outcome-id--][checked-is-open]\"  type=\"checkbox\" value=\"yes\" toggle-group=\"progressally-grade-outcome\"\
       toggle-class=\"progressally-item-opened\" progressally-toggle-element=\"#progressally-grade-outcome---outcome-id--\" min-height=\"40\" min-height-element=\"#progressally-grade-outcome-header---outcome-id--\"\
       pa-dep-source=\"progressally-grade-outcome-toggle---outcome-id--\" id=\"progressally-grade-outcome-toggle---outcome-id--\">\
   <label hide-toggle pa-dep=\"progressally-grade-outcome-toggle---outcome-id--\" pa-dep-value=\"no\">&#x25BC;</label>\
   <label hide-toggle style=\"display:none;\" pa-dep=\"progressally-grade-outcome-toggle---outcome-id--\" pa-dep-value=\"yes\">&#x25B2;</label>\
  </div>\
  <div class=\"progressally-name-display-block\">\
   <div class=\"progressally-name-display\">\
    <table class=\"progressally-header-table\">\
     <tbody>\
      <tr>\
       <td class=\"progressally-name-label-col\"><div class=\"progressally-name-label\" id=\"progressally-grade-outcome---outcome-id---title\"></div></td>\
      </tr>\
     </tbody>\
    </table>\
   </div>\
  </div>\
 </div>\
 <div hide-toggle style=\"display:none;\" pa-dep=\"progressally-grade-outcome-toggle---outcome-id--\" pa-dep-value=\"yes\">\
  <div class='progressally-outcome-section'>\
   <strong>If the learner scores <input class=\"progressally-quiz-outcome-score\" progressally-param=\"quiz[grade-outcome][--outcome-id--][min-score]\" size=\"3\" type=\"text\"\
        value=\"100\"\
       outcome-id=\"--outcome-id--\" id=\"progressally-grade-outcome---outcome-id---min\" /> % or higher, the outcome code below will show:</strong>\
  </div>\
  <div class=\"progressally-outcome-section\">\
   <input name=\"quiz[grade-outcome-threshold-id]\"  class=\"progressally-grade-outcome-threshold\"\
       type=\"radio\" id=\"progressally-grade-outcome---outcome-id---threshold\" value=\"--outcome-id--\" />\
   <label for=\"progressally-grade-outcome---outcome-id---threshold\">Use this score as pass/fail threshold</label>\
   <div class=\"progressally-setting-section-help-text\">A quiz objective can only be fulfilled when the grade passes or equals to the threshold.</div>\
  </div>\
  <div class=\"progressally-outcome-section\">\
   <div class=\"progressally-setting-section-help-text\">\
    You can use HTML code in the outcome code. \"{[percentage]}\" can be used to add the score percentage to display the learner's results.\
   </div>\
   <textarea class=\"full-width\" progressally-param=\"quiz[grade-outcome][--outcome-id--][html]\" rows=\"5\">&lt;div class=&quot;progressally-quiz-result&quot;&gt;&lt;hr/&gt;Congratulations! You scored {[percentage]}!&lt;/div&gt;</textarea>\
  </div>\
  <div class=\"progressally-outcome-section\" --has-valid-popup-selection-->\
   <div class=\"progressally-setting-section-sub-header\">Show a PopupAlly Pro popup with this outcome</div>\
   <div class=\"progressally-setting-section-help-text\">\
    <div class=\"progressally-info-icon\"></div>\
    Learn more in our <a href=\"https://access.accessally.com/progressally-quiz-outcome-optin\" target=\"_blank\">video tutorial</a>.\
   </div>\
   <div class=\"progressally-setting-configure-block\">\
    <select progressally-param=\"quiz[grade-outcome][--outcome-id--][select-popup-type]\"\
      pa-dep-source=\"progressally-grade-outcome---outcome-id---select-popup-type\">\
     <option selected=\"selected\" value=\"none\">Do not show a popup</option>\
     <option  value=\"popup\">Show the selected popup</option>\
     <option  value=\"embedded\">Add the selected popup as an embedded opt-in at the end of the outcome text</option>\
    </select>\
   </div>\
   <div class=\"progressally-setting-configure-block\"\
     hide-toggle style=\"display:none;\" pa-dep=\"progressally-grade-outcome---outcome-id---select-popup-type\" pa-dep-value-not=\"none\">\
    Popup to show\
    <select progressally-param=\"quiz[grade-outcome][--outcome-id--][optin-popup]\">\
     <option value=\"\"></option>\
     --popup-selection--\
    </select>\
   </div>\
  </div>\
  <div>\
   <div class=\"progressally-delete-button progressally-float-right progressally-quiz-grade-outcome-delete-button\" outcome-id=\"--outcome-id--\"\
     progressally-delete-warning=\"Deleting an outcome cannot be undone. Continue?\">[-] Delete Outcome</div>\
   <div style=\"clear:both\"></div>\
  </div>\
 </div>\
</div>","segment-outcome":"<div class=\"progressally-setting-outcome-block \" id=\"progressally-segment-outcome---outcome-id--\">\
 <div class=\"progressally-outcome-header\" toggle-target=\"#progressally-segment-outcome-toggle---outcome-id--\" id=\"progressally-segment-outcome-header---outcome-id--\">\
  <div class=\"progressally-view-toggle-block\">\
   <input progressally-param=\"quiz[segment-outcome][--outcome-id--][checked-is-open]\"  type=\"checkbox\" value=\"yes\" toggle-group=\"progressally-segment-outcome\"\
       toggle-class=\"progressally-item-opened\" progressally-toggle-element=\"#progressally-segment-outcome---outcome-id--\" min-height=\"40\" min-height-element=\"#progressally-segment-outcome-header---outcome-id--\"\
       pa-dep-source=\"progressally-segment-outcome-toggle---outcome-id--\" id=\"progressally-segment-outcome-toggle---outcome-id--\">\
   <label hide-toggle pa-dep=\"progressally-segment-outcome-toggle---outcome-id--\" pa-dep-value=\"no\">&#x25BC;</label>\
   <label hide-toggle style=\"display:none;\" pa-dep=\"progressally-segment-outcome-toggle---outcome-id--\" pa-dep-value=\"yes\">&#x25B2;</label>\
  </div>\
  <div class=\"progressally-name-display-block\">\
   <div class=\"progressally-name-display\">\
    <table class=\"progressally-header-table\">\
     <tbody>\
      <tr>\
       <td class=\"progressally-name-label-col\"><div class=\"progressally-name-label\" id=\"progressally-segment-outcome---outcome-id---title\"></div></td>\
      </tr>\
     </tbody>\
    </table>\
   </div>\
  </div>\
 </div>\
 <div hide-toggle style=\"display:none;\" pa-dep=\"progressally-segment-outcome-toggle---outcome-id--\" pa-dep-value=\"yes\">\
  <div class='progressally-outcome-section'>\
   <strong>If the learner scores <input class=\"progressally-quiz-outcome-segment\" progressally-param=\"quiz[segment-outcome][--outcome-id--][min-score]\" size=\"3\" type=\"text\"\
        value=\"0\"\
       outcome-id=\"--outcome-id--\" id=\"progressally-segment-outcome---outcome-id---min\" /> or higher, the outcome code below will show:</strong>\
  </div>\
  <div class='progressally-outcome-section'>\
   <div class=\"progressally-setting-section-help-text\">\
    You can use HTML code in the outcome code. \"{[score]}\" can be used to add the score to display the learner's results.\
   </div>\
   <textarea class=\"full-width\" progressally-param=\"quiz[segment-outcome][--outcome-id--][html]\" rows=\"5\">&lt;div class=&quot;progressally-quiz-result&quot;&gt;&lt;hr/&gt;You scored {[score]}!&lt;/div&gt;</textarea>\
  </div>\
  <div class=\"progressally-outcome-section\" --has-valid-popup-selection-->\
   <div class=\"progressally-setting-section-sub-header\">Show a PopupAlly Pro popup with this outcome</div>\
   <div class=\"progressally-setting-section-help-text\">\
    <div class=\"progressally-info-icon\"></div>\
    Learn more in our <a href=\"https://access.accessally.com/progressally-quiz-outcome-optin\" target=\"_blank\">video tutorial</a>.\
   </div>\
   <div class=\"progressally-setting-configure-block\">\
    <select progressally-param=\"quiz[segment-outcome][--outcome-id--][select-popup-type]\"\
      pa-dep-source=\"progressally-segment-outcome---outcome-id---select-popup-type\">\
     <option selected=\"selected\" value=\"none\">Do not show a popup</option>\
     <option  value=\"popup\">Show the selected popup</option>\
     <option  value=\"embedded\">Add the selected popup as an embedded opt-in at the end of the outcome text</option>\
    </select>\
   </div>\
   <div class=\"progressally-setting-configure-block\"\
     hide-toggle style=\"display:none;\" pa-dep=\"progressally-segment-outcome---outcome-id---select-popup-type\" pa-dep-value-not=\"none\">\
    Popup to show\
    <select progressally-param=\"quiz[segment-outcome][--outcome-id--][optin-popup]\">\
     <option value=\"\"></option>\
     --popup-selection--\
    </select>\
   </div>\
  </div>\
  <div class=\"progressally-outcome-section\" --has-valid-tag-selection-->\
   <div class=\"progressally-setting-configure-block\">\
    <div class=\"progressally-setting-section-sub-header\">Apply a tag when this outcome is reached:</div>\
    <select class=\"progressally-autocomplete-add progressally-tag-input full-width\" progressally-param=\"quiz[segment-outcome][--outcome-id--][access-tag]\">\
     <option value=\"\"></option>\
     --tag-selection--\
    </select>\
   </div>\
  </div>\
  <div>\
   <div class=\"progressally-delete-button progressally-float-right progressally-quiz-segment-outcome-delete-button\" outcome-id=\"--outcome-id--\"\
     progressally-delete-warning=\"Deleting an outcome cannot be undone. Continue?\">[-] Delete Outcome</div>\
   <div style=\"clear:both\"></div>\
  </div>\
 </div>\
</div>","note":"<div class=\"progressally-setting-accordion-block \" id=\"progressally-note-block---note-id--\">\
 <div class=\"progressally-setting-accordion-header\" progressally-toggle-target=\"#progressally-note-toggle---note-id--\" id=\"progressally-setting-note-header---note-id--\">\
  <div class=\"progressally-view-toggle-block\">\
   <input progressally-param=\"notes[--note-id--][checked-is-open]\"  type=\"checkbox\" value=\"yes\"\
       toggle-class=\"progressally-accordion-opened\" progressally-toggle-element=\"#progressally-note-block---note-id--\" min-height=\"40\"\
       min-height-element=\"#progressally-setting-note-header---note-id--\"\
       pa-dep-source=\"progressally-note-toggle---note-id--\" id=\"progressally-note-toggle---note-id--\">\
   <label hide-toggle pa-dep=\"progressally-note-toggle---note-id--\" pa-dep-value=\"no\">&#x25BC;</label>\
   <label hide-toggle style=\"display:none;\" pa-dep=\"progressally-note-toggle---note-id--\" pa-dep-value=\"yes\">&#x25B2;</label>\
  </div>\
  <div class=\"progressally-name-display-block\">\
   <div class=\"progressally-name-display\" progressally-click-edit-show=\"note-name---note-id--\">\
    <table class=\"progressally-header-table\">\
     <tbody>\
      <tr>\
       <td class=\"progressally-note-number-col\">--note-id--. </td>\
       <td class=\"progressally-name-label-col\"><div class=\"progressally-name-label\" progressally-click-edit-display=\"note-name---note-id--\">User-specific note</div></td>\
       <td class=\"progressally-name-edit-col\"><div class=\"progressally-pencil-icon\" progressally-click-edit-trigger=\"note-name---note-id--\"></div></td>\
      </tr>\
     </tbody>\
    </table>\
   </div>\
   <input progressally-param=\"notes[--note-id--][name]\" class=\"progressally-name-edit progressally-note-name full-width\" progressally-note-name-input=\"--note-id--\" progressally-click-edit-input=\"note-name---note-id--\"\
       style=\"display:none;\" value=\"User-specific note\" type=\"text\" />\
  </div>\
  <div style=\"clear:both;\"></div>\
 </div>\
 <div class=\"progressally-setting-accordion-setting-section\" hide-toggle style=\"display:none;\" pa-dep=\"progressally-note-toggle---note-id--\" pa-dep-value=\"yes\">\
  <div class=\"progressally-setting-configure-block\">\
   <div class=\"progressally-setting-configure-block\">\
    <div class=\"progressally-setting-section-header\">Give your note some context</div>\
    <div class=\"progressally-setting-section-help-text\">Ask a question or start with a prompt (HTML code allowed).</div>\
   </div>\
   <div class=\"progressally-setting-configure-block\">\
    <textarea class=\"full-width\" progressally-param=\"notes[--note-id--][title]\" rows=\"5\">Private Note</textarea>\
   </div>\
  </div>\
  <div class=\"progressally-setting-configure-block\">\
   <div class=\"progressally-setting-configure-block\">\
    <div class=\"progressally-setting-section-header\">Placeholder text</div>\
   </div>\
   <div class=\"progressally-setting-configure-block\">\
    <input class=\"full-width\" type=\"text\" progressally-param=\"notes[--note-id--][placeholder]\" value=\"Click here to enter your note\" />\
   </div>\
  </div>\
  <div class=\"progressally-setting-configure-block\">\
   <div class=\"progressally-setting-configure-block\">\
    <div class=\"progressally-setting-section-header\">Type</div>\
   </div>\
   <div class=\"progressally-setting-configure-block\">\
    <select progressally-param=\"notes[--note-id--][select-type]\" progressally-note-type=\"--note-id--\" pa-dep-source=\"progressally-note-select-type---note-id--\">\
     <option selected=\"selected\" value=\"note\">Private course note</option>\
     <option  value=\"qa\">User-specific question and answer</option>\
     <option  value=\"admin\">Admin-initiated comment</option>\
     <option  value=\"approve\">Admin reviewed answer</option>\
     <option  value=\"custom\">Custom</option>\
    </select>\
   </div>\
   <div class=\"progressally-inline-help-text\" hide-toggle pa-dep=\"progressally-note-select-type---note-id--\" pa-dep-value=\"note\">\
    <p><strong>Private course note</strong> allows a user to write down notes specific to the module / course.</p>\
    <ul class=\"progressally-list\">\
     <li>The admin(s) will not be notified when the user writes down a note.</li>\
     <li>Users can update their own notes as often as needed.</li>\
    </ul>\
   </div>\
   <div class=\"progressally-setting-configure-block\" hide-toggle style=\"display:none;\" pa-dep=\"progressally-note-select-type---note-id--\" pa-dep-value=\"qa\">\
    <div class=\"progressally-inline-help-text\">\
     <p><strong>User-specific question and answer</strong> allows admin(s) to reply to user-specific questions.</p>\
     <ul class=\"progressally-list\">\
      <li>Admin(s) will be notified when the user writes down a question.</li>\
      <li>Users will be notified when the admin(s) reply.</li>\
      <li>Users cannot change the question once the admin(s) have replied.</li>\
     </ul>\
    </div>\
   </div>\
   <div class=\"progressally-inline-help-text\" hide-toggle style=\"display:none;\" pa-dep=\"progressally-note-select-type---note-id--\" pa-dep-value=\"admin\">\
    <p><strong>Admin-initiated comment</strong> allows admins to add a comment / content to a page that is specific to each user.</p>\
    <ul class=\"progressally-list\">\
     <li>Users will not be notified when the comment is created.</li>\
     <li>Users cannot reply to the comment.</li>\
    </ul>\
   </div>\
   <div class=\"progressally-inline-help-text\" hide-toggle style=\"display:none;\" pa-dep=\"progressally-note-select-type---note-id--\" pa-dep-value=\"approve\">\
    <p><strong>Admin reviewed answer</strong> is used with the <strong>Note</strong> objective, which is only marked as completed when the admin(s) approve the answer.</p>\
    <ul class=\"progressally-list\">\
     <li>Admin(s) will be notified when the user submits a note.</li>\
     <li>Users will be notified when the admin(s) reply.</li>\
     <li>Users cannot change the note once the admin(s) have replied.</li>\
    </ul>\
   </div>\
   <div class=\"progressally-setting-configure-block\" hide-toggle style=\"display:none;\" pa-dep=\"progressally-note-select-type---note-id--\" pa-dep-value=\"custom\">\
    <div class=\"progressally-setting-configure-block\">\
     <input type=\"checkbox\" progressally-param=\"notes[--note-id--][checked-admin-initiated]\"  value=\"yes\" id=\"progressally-note-admin-initiated---note-id--\" />\
     <label for=\"progressally-note-admin-initiated---note-id--\">Admin-initiated</label>\
    </div>\
    <div class=\"progressally-setting-configure-block\">\
     <input type=\"checkbox\" progressally-param=\"notes[--note-id--][checked-approve]\"  value=\"yes\" id=\"progressally-note-approve---note-id--\" />\
     <label for=\"progressally-note-approve---note-id--\">Require Admin approval to complete the corresponding objective.</label>\
    </div>\
    <div class=\"progressally-setting-configure-block\">\
     <input type=\"text\" progressally-param=\"notes[--note-id--][num-reply]\" size=\"4\" value=\"0\" id=\"progressally-note-num-reply---note-id--\" />\
     <label for=\"progressally-note-num-reply---note-id--\">Max number of user replies (enter -1 for unlimited)</label>\
    </div>\
    <div class=\"progressally-setting-configure-block\">\
     <input type=\"checkbox\" progressally-param=\"notes[--note-id--][checked-notify-admin]\"  value=\"yes\" id=\"progressally-note-notify-admin---note-id--\" />\
     <label for=\"progressally-note-notify-admin---note-id--\">Notify admins when users create a comment / reply</label>\
    </div>\
    <div class=\"progressally-setting-configure-block\">\
     <input type=\"checkbox\" progressally-param=\"notes[--note-id--][checked-notify-user]\"  value=\"yes\" id=\"progressally-note-notify-user---note-id--\" pa-dep-source=\"progressally-note-notify-user---note-id--\" />\
     <label for=\"progressally-note-notify-user---note-id--\">Notify users when admins reply (does not apply to the first admin-initiated comment)</label>\
    </div>\
   </div>\
   <div class=\"progressally-setting-email-customization-block\" hide-toggle style=\"display:none;\" pa-dep=\"progressally-note-notify-user---note-id--\" pa-dep-value=\"yes\">\
    <input type=\"checkbox\" progressally-param=\"notes[--note-id--][checked-custom-email]\" id=\"progressally-custom-email---note-id--\" value='yes'\
        pa-dep-source=\"progressally-custom-email---note-id--\"  />\
    <label for=\"progressally-custom-email---note-id--\">Customize the notification email</label>\
    <div hide-toggle style=\"display:none;\" pa-dep=\"progressally-custom-email---note-id--\" pa-dep-value=\"yes\">\
     <div class=\"progressally-setting-configure-block\">\
      <div class=\"progressally-setting-section-sub-header\">Email subject</div>\
      <input type=\"text\" class=\"full-width\" progressally-param=\"notes[--note-id--][custom-email-subject]\"\
          value=\"[--blog-title--] You have a new reply!\" />\
     </div>\
     <div class=\"progressally-setting-configure-block\">\
      <div class=\"progressally-setting-section-sub-header\">Email content</div>\
      <textarea rows=\"20\" class=\"full-width\" progressally-param=\"notes[--note-id--][custom-email-content]\"\">&lt;table cellpadding=&quot;0&quot; cellspacing=&quot;0&quot; border=&quot;0&quot; align=&quot;center&quot; style=&quot;width:100%;max-width:600px&quot;&gt;\n &lt;tbody&gt;\n    &lt;tr&gt;\n      &lt;td style=&quot;font-size:1px;line-height:1px&quot; height=&quot;10&quot;&gt;&amp;nbsp;&lt;/td&gt;\n    &lt;/tr&gt;\n &lt;tr&gt;\n      &lt;td&gt;&lt;a target=&quot;_blank&quot; href=&quot;{[post-raw-link]}&quot;&gt;Click here&lt;/a&gt; to see the reply&lt;/td&gt;\n &lt;/tr&gt;\n    &lt;tr&gt;\n      &lt;td style=&quot;font-size:1px;line-height:1px&quot; height=&quot;20&quot;&gt;&amp;nbsp;&lt;/td&gt;\n    &lt;/tr&gt;\n    &lt;tr&gt;\n      &lt;td style=&quot;color:#000000;font-size:24px&quot;&gt;Note Details&lt;/td&gt;\n    &lt;/tr&gt;\n    &lt;tr&gt;\n      &lt;td style=&quot;font-size:1px;line-height:1px&quot; height=&quot;20&quot;&gt;&amp;nbsp;&lt;/td&gt;\n    &lt;/tr&gt;\n {[note-details]}\n &lt;tr&gt;\n      &lt;td&gt;&lt;a target=&quot;_blank&quot; href=&quot;{[post-raw-link]}&quot;&gt;Click here&lt;/a&gt; to see the reply&lt;/td&gt;\n &lt;/tr&gt;\n  &lt;/tbody&gt;\n&lt;/table&gt;</textarea>\
     </div>\
    </div>\
   </div>\
  </div>\
  <div>\
   <div class=\"progressally-delete-button progressally-note-delete progressally-float-right\" progressally-delete-element=\"#progressally-note-block---note-id--\"\
     progressally-delete-warning=\"Deleting a note cannot be undone. Continue?\" progressally-private-note-delete=\"--note-id--\">[-] Delete Note</div>\
   <div style=\"display:none\" class=\"progressally-setting-note-usage progressally-float-right\" progressally-private-note-in-use=\"--note-id--\"></div>\
   <div style=\"clear:both\"></div>\
  </div>\
 </div>\
</div>","cert":"<div class=\"progressally-setting-accordion-block \" id=\"progressally-certificate-block---certificate-id--\">\
 <div class=\"progressally-setting-accordion-header\" progressally-toggle-target=\"#progressally-certificate-toggle---certificate-id--\" id=\"progressally-setting-certificate-header---certificate-id--\">\
  <div class=\"progressally-view-toggle-block\">\
   <input progressally-param=\"cert[--certificate-id--][checked-is-open]\"  type=\"checkbox\" value=\"yes\"\
       toggle-class=\"progressally-accordion-opened\" progressally-toggle-element=\"#progressally-certificate-block---certificate-id--\" min-height=\"40\"\
       min-height-element=\"#progressally-setting-certificate-header---certificate-id--\"\
       pa-dep-source=\"progressally-certificate-toggle---certificate-id--\" id=\"progressally-certificate-toggle---certificate-id--\">\
   <label hide-toggle pa-dep=\"progressally-certificate-toggle---certificate-id--\" pa-dep-value=\"no\">&#x25BC;</label>\
   <label hide-toggle style=\"display:none;\" pa-dep=\"progressally-certificate-toggle---certificate-id--\" pa-dep-value=\"yes\">&#x25B2;</label>\
  </div>\
  <div class=\"progressally-name-display-block\">\
   <div class=\"progressally-name-display\" progressally-click-edit-show=\"certificate-name---certificate-id--\">\
    <table class=\"progressally-header-table\">\
     <tbody>\
      <tr>\
       <td class=\"progressally-certificate-number-col\">--certificate-id--. </td>\
       <td class=\"progressally-name-label-col\"><div class=\"progressally-name-label\" progressally-click-edit-display=\"certificate-name---certificate-id--\">ProgressAlly Certificate</div></td>\
       <td class=\"progressally-name-edit-col\"><div class=\"progressally-pencil-icon\" progressally-click-edit-trigger=\"certificate-name---certificate-id--\"></div></td>\
      </tr>\
     </tbody>\
    </table>\
   </div>\
   <input progressally-param=\"cert[--certificate-id--][name]\" class=\"progressally-name-edit progressally-certificate-name full-width\" progressally-certificate-name-input=\"--certificate-id--\" progressally-click-edit-input=\"certificate-name---certificate-id--\"\
       style=\"display:none;\" value=\"ProgressAlly Certificate\" type=\"text\" />\
  </div>\
  <div style=\"clear:both;\"></div>\
 </div>\
 <div class=\"progressally-setting-accordion-setting-section\" hide-toggle style=\"display:none;\" pa-dep=\"progressally-certificate-toggle---certificate-id--\" pa-dep-value=\"yes\">\
  <div class=\"progressally-setting-configure-block\" progressally-certificate-upload-block=\"--certificate-id--\" >\
   <div class=\"progressally-setting-section-sub-header\">\
    Upload a PDF template\
   </div>\
   <div>\
    <input type=\"file\" progressally-certificate-upload=\"--certificate-id--\" accept=\".pdf\" />\
   </div>\
   <a href=\"#\" progressally-certificate-switch-customization=\"--certificate-id--\" style=\"display:none;\">Customize current template</a>\
  </div>\
  <div progressally-certificate-customization-block=\"--certificate-id--\" style=\"display:none;\">\
   <a href=\"#\" progressally-certificate-switch-upload=\"--certificate-id--\">Upload a new template</a>\
   <div class=\"progressally-setting-certficiate-header-block\">\
    <input type=\"hidden\" progressally-certificate-file-path=\"--certificate-id--\" progressally-param=\"cert[--certificate-id--][file-path]\"\
        value=\"\"/>\
    <input type=\"hidden\" progressally-certificate-width=\"--certificate-id--\" progressally-param=\"cert[--certificate-id--][width]\"\
        value=\"0\"/>\
    <input type=\"hidden\" progressally-certificate-height=\"--certificate-id--\" progressally-param=\"cert[--certificate-id--][height]\"\
        value=\"0\"/>\
    <table class=\"progressally-setting-configure-table\" style=\"margin:0 0 15px 0\">\
     <tbody>\
      <tr>\
       <td style=\"width:180px\"><div class=\"progressally-setting-section-sub-header\">Certificate File Name</div></td>\
       <td>\
        <input type=\"text\" class=\"full-width\" progressally-certificate-file-name=\"--certificate-id--\" progressally-param=\"cert[--certificate-id--][file-name]\"\
            value=\"\"/>\
       </td>\
       <td style=\"width:30px\">.pdf</td>\
      </tr>\
     </tbody>\
    </table>\
    <input type=\"hidden\" progressally-param=\"cert[--certificate-id--][max-elem]\" progressally-certificate-element-max=\"--certificate-id--\" value=\"0\" />\
    <a class=\"progressally-button progressally-float-right\" target=\"_blank\" href=\"#\" progressally-certificate-download=\"--certificate-id--\">Download Test Certificate</a>\
    <div class=\"progressally-button\" progressally-certificate-add-element=\"--certificate-id--\">[+] Add New Customization</div>\
    <div style=\"clear:both\"></div>\
   </div>\
   <div class=\"progressally-setting-configure-block\" id=\"progressally-certificate-preview-block\">\
    <div class=\"progressally-setting-configure-block\">\
     <div class=\"progressally-setting-section-sub-header\">\
      Preview\
     </div>\
     <div class=\"progressally-setting-section-help-text\">The preview is designed to assist you with positioning the text. Please see the resulting PDF by clicking on the download link.</div>\
    </div>\
    <div progressally-certificate-customization=\"--certificate-id--\">\
     \
    </div>\
    <div class=\"progressally-setting-configure-block\">\
     <span style=\"line-height:30px;\">Please press the</span>\
     <img src=\"--plugin-uri--/resource/backend/img/cert-zoom-button.png\" width=\"30\" height=\"30\" style=\"vertical-align:bottom;\" />\
     <span style=\"line-height:30px;\">button to resize the PDF document in the preview window before adding customizations.</span>\
    </div>\
    <div class=\"progressally-setting-configure-block\">\
     <div class=\"progressally-certificate-preview-container\" progressally-certificate-preview-container=\"--certificate-id--\" style=\"width:600px;height:0px;\">\
      <div class=\"progressally-certificate-pdf-container\" progressally-certificate-pdf-container=\"--certificate-id--\">\
       \
      </div>\
      <div class=\"progressally-certificate-pdf-customization\" progressally-certificate-pdf-customization=\"--certificate-id--\">\
       \
      </div>\
     </div>\
    </div>\
   </div>\
  </div>\
  <div>\
   <div class=\"progressally-delete-button progressally-certificate-delete progressally-float-right\" progressally-delete-element=\"#progressally-certificate-block---certificate-id--\"\
     progressally-delete-warning=\"Deleting a certificate cannot be undone. Continue?\">[-] Delete Certificate</div>\
   <div style=\"clear:both\"></div>\
  </div>\
 </div>\
</div>","cert-element":"<div class=\"progressally-certificate-customation-details\" progressally-certificate-preview-details=\"--certificate-id-----element-id--\">\
 <div>\
  <div class=\"progressally-small-delete-button progressally-float-right\" progressally-certificate-element-delete=\"--certificate-id-----element-id--\"\
    progressally-delete-warning=\"Deleting a customization cannot be undone. Continue?\">[-] Delete Customization</div>\
  <div class=\"progressally-setting-section-sub-header\">Customization --element-id--</div>\
  <div style=\"clear:both\"></div>\
 </div>\
 <input type=\"hidden\" progressally-certificate-preview-element-x=\"--certificate-id-----element-id--\" progressally-param=\"cert[--certificate-id--][custom][--element-id--][x]\" value=\"10\"\
     progressally-certificate-preview-mm=\"--certificate-id-----element-id--\" progressally-certificate-id=\"--certificate-id--\" preview-attribute=\"left\" />\
 <input type=\"hidden\" progressally-certificate-preview-element-y=\"--certificate-id-----element-id--\" progressally-param=\"cert[--certificate-id--][custom][--element-id--][y]\" value=\"10\"\
     progressally-certificate-preview-mm=\"--certificate-id-----element-id--\" progressally-certificate-id=\"--certificate-id--\" preview-attribute=\"top\" />\
 <div class=\"progressally-setting-configure-block\">\
  <div class=\"progressally-certificate-parameter-block\">\
   What text to add?\
   <select progressally-param=\"cert[--certificate-id--][custom][--element-id--][select-type]\" pa-dep-source=\"progressally-certificate-customization-select-type---certificate-id-----element-id--\"\
     progressally-certificate-customize-type=\"--certificate-id-----element-id--\">\
    <option selected=\"selected\" value=\"full-name\">Full Name</option><option  value=\"first-name\">First Name</option><option  value=\"last-name\">Last Name</option><option  value=\"full-date\">Date (YYYY-MM-DD)</option><option  value=\"year\">Year (YYYY)</option><option  value=\"month\">Month (MM)</option><option  value=\"lmonth\">Month (January - December)</option><option  value=\"day\">Day (DD)</option><option  value=\"days\">Day (1st - 31st)</option><option  value=\"dayw\">Day of the week (Monday - Sunday)</option>\
    <option  value=\"custom\">Custom (for developers only)</option>\
   </select>\
  </div>\
  <div class=\"progressally-certificate-parameter-block\" \
    hide-toggle style=\"display:none;\" pa-dep=\"progressally-certificate-customization-select-type---certificate-id-----element-id--\" pa-dep-value=\"full-date,year,month,lmonth,day,days,dayw\">\
   Show\
   <select progressally-param=\"cert[--certificate-id--][custom][--element-id--][select-date-type]\">\
    <option value=\"current\" selected=\"selected\">Download time</option>\
    <option value=\"complete\" >Checklist completion time</option>\
   </select>\
  </div>\
  <div class=\"progressally-certificate-parameter-block\" hide-toggle style=\"display:none;\" pa-dep=\"progressally-certificate-customization-select-type---certificate-id-----element-id--\" pa-dep-value=\"custom\">\
   Custom text\
   <input size=\"30\" hide-toggle style=\"display:none;\" pa-dep=\"progressally-certificate-customization-select-type---certificate-id-----element-id--\" pa-dep-value=\"custom\"\
       type=\"text\" progressally-param=\"cert[--certificate-id--][custom][--element-id--][custom-value]\" value=\"\" />\
  </div>\
 </div>\
 <div class=\"progressally-setting-configure-block\">\
  <div class=\"progressally-certificate-parameter-block\">\
   Width\
   <input type=\"text\" size=\"8\" progressally-certificate-preview-element-width=\"--certificate-id-----element-id--\" progressally-param=\"cert[--certificate-id--][custom][--element-id--][w]\" value=\"50\"\
       progressally-certificate-preview-mm=\"--certificate-id-----element-id--\" progressally-certificate-id=\"--certificate-id--\" preview-attribute=\"width\" /> mm\
  </div>\
  <div class=\"progressally-certificate-parameter-block\">\
   Text Color\
   <input class=\"nqpc-picker-input-iyxm\" progressally-certificate-preview=\"#progressally-certificate-element---certificate-id-----element-id--\" preview-attribute=\"color\"\
       size=\"8\" type=\"text\" progressally-param=\"cert[--certificate-id--][custom][--element-id--][color]\" value=\"#111111\" />\
  </div>\
  <div class=\"progressally-certificate-parameter-block\">\
   Text Font\
   <select progressally-param=\"cert[--certificate-id--][custom][--element-id--][select-font]\" progressally-certificate-preview-font=\"#progressally-certificate-element---certificate-id-----element-id--\">\
    <option selected=\"selected\" value=\"helvetica\">Arial / Helvetica</option><option  value=\"times\">Times New Roman</option><option  value=\"Georgia\">Georgia</option><option  value=\"Tahoma\">Tahoma / Geneva</option><option  value=\"TrebuchetMS\">Trebuchet MS</option>\
   </select>\
  </div>\
  <div class=\"progressally-certificate-parameter-block\">\
   Font Size\
   <input type=\"text\" size=\"6\" progressally-param=\"cert[--certificate-id--][custom][--element-id--][font-size]\" value=\"20\"\
       progressally-certificate-preview-pt=\"--certificate-id-----element-id--\" progressally-certificate-id=\"--certificate-id--\" preview-attribute=\"font-size\" />\
  </div>\
  <div class=\"progressally-certificate-parameter-block\">\
   Text Alignment\
   <select progressally-param=\"cert[--certificate-id--][custom][--element-id--][select-align]\" progressally-certificate-preview=\"#progressally-certificate-element---certificate-id-----element-id--\" preview-attribute=\"text-align\">\
    <option  value=\"left\">Left</option>\
    <option selected=\"selected\" value=\"center\">Center</option>\
    <option  value=\"right\">Right</option>\
   </select>\
  </div>\
 </div>\
 <table class=\"progressally-post-setting-table\">\
  <tbody>\
   <tr>\
    <td style=\"width:200px\">\
     Enter sample text to test\
    </td>\
    <td>\
     <input type=\"text\" class=\"full-width\" value=\"\" progressally-certificate-preview-val=\"#progressally-certificate-element---certificate-id-----element-id--\"\
         progressally-certificate-customize-preview=\"--certificate-id-----element-id--\" />\
    </td>\
   </tr>\
  </tbody>\
 </table>\
</div>","cert-preview":"<div class=\"progressally-certificate-preview-element\" style='width:0px;left:0px;top:0px;font-size:0px;line-height:0px;color:#111111;text-align:center;font-family:Arial, Helvetica, sans-serif;' progressally-certificate-id=\"--certificate-id--\" progressally-certificate-preview-element=\"--certificate-id-----element-id--\" unselectable=\"on\" id=\"progressally-certificate-element---certificate-id-----element-id--\">\
 <div class=\"progressally-preview-text\"></div>\
 <div class=\"progressally-preview-label\">--element-id--</div>\
 <div class=\"progressally-preview-noclick-overlay\"></div>\
</div>","social-sharing":"<div class=\"progressally-setting-accordion-block \" id=\"progressally-share-block---share-id--\">\
 <div class=\"progressally-setting-accordion-header\" progressally-toggle-target=\"#progressally-share-toggle---share-id--\" id=\"progressally-setting-share-header---share-id--\">\
  <div class=\"progressally-view-toggle-block\">\
   <input progressally-param=\"social-sharing[shares][--share-id--][checked-is-open]\"  type=\"checkbox\" value=\"yes\"\
       toggle-class=\"progressally-accordion-opened\" progressally-toggle-element=\"#progressally-share-block---share-id--\" min-height=\"40\"\
       min-height-element=\"#progressally-setting-share-header---share-id--\"\
       pa-dep-source=\"progressally-share-toggle---share-id--\" id=\"progressally-share-toggle---share-id--\">\
   <label hide-toggle pa-dep=\"progressally-share-toggle---share-id--\" pa-dep-value=\"no\">&#x25BC;</label>\
   <label hide-toggle style=\"display:none;\" pa-dep=\"progressally-share-toggle---share-id--\" pa-dep-value=\"yes\">&#x25B2;</label>\
  </div>\
  <div class=\"progressally-name-display-block\">\
   <div class=\"progressally-name-display\" progressally-click-edit-show=\"share-name---share-id--\">\
    <table class=\"progressally-header-table\">\
     <tbody>\
      <tr>\
       <td class=\"progressally-share-number-col\">--share-id--. </td>\
       <td class=\"progressally-name-label-col\"><div class=\"progressally-name-label\" progressally-click-edit-display=\"share-name---share-id--\">Social Sharing</div></td>\
       <td class=\"progressally-name-edit-col\"><div class=\"progressally-pencil-icon\" progressally-click-edit-trigger=\"share-name---share-id--\"></div></td>\
      </tr>\
     </tbody>\
    </table>\
   </div>\
   <input progressally-param=\"social-sharing[shares][--share-id--][name]\" class=\"progressally-name-edit progressally-share-name full-width\" progressally-share-name-input=\"--share-id--\" progressally-click-edit-input=\"share-name---share-id--\"\
       style=\"display:none;\" value=\"Social Sharing\" type=\"text\" />\
  </div>\
  <div style=\"clear:both;\"></div>\
 </div>\
 <div class=\"progressally-setting-accordion-setting-section\" hide-toggle style=\"display:none;\" pa-dep=\"progressally-share-toggle---share-id--\" pa-dep-value=\"yes\">\
  <table class=\"progressally-setting-configure-table\" >\
   <tbody>\
    <tr class=\"progressally-setting-configure-table-row\">\
     <th scope=\"row\" class=\"progressally-setting-configure-table-header-col\">\
      Link To Share on Social\
     </th>\
     <td>\
      <input class=\"full-width\" type=\"text\" progressally-param=\"social-sharing[shares][--share-id--][sharing-url]\" value=\"\" />\
     </td>\
    </tr>\
    <tr class=\"progressally-setting-configure-table-row\">\
     <th scope=\"row\" class=\"progressally-setting-configure-table-header-col\">\
      Link Description\
     </th>\
     <td>\
      <input class=\"full-width\" type=\"text\" progressally-param=\"social-sharing[shares][--share-id--][sharing-text]\" value=\"\" />\
     </td>\
    </tr>\
    <tr class=\"progressally-setting-configure-table-row\">\
     <th scope=\"row\" class=\"progressally-setting-configure-table-header-col\">\
      Image URL To Accompany Your Link\
     </th>\
     <td>\
      <input class=\"full-width\" type=\"text\" progressally-param=\"social-sharing[shares][--share-id--][sharing-image]\" value=\"\" />\
     </td>\
    </tr>\
   </tbody>\
  </table>\
  <div>\
   <div class=\"progressally-delete-button progressally-share-delete progressally-float-right\" progressally-delete-element=\"#progressally-share-block---share-id--\"\
     progressally-delete-warning=\"Deleting a sharing cannot be undone. Continue?\" progressally-social-sharing-delete=\"--share-id--\">[-] Delete Sharing</div>\
   <div style=\"clear:both\"></div>\
  </div>\
 </div>\
</div>",};