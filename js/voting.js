var buttonSetup, voteItUp, hideElements, validateEmail;

var version = '3',
    votes = {
      "email": "",
      "primary": "",
      "secondary": "",
      "tertiary": ""
    },
    template = $("#talentPanelTemplate").html(),
    output = $("#talent"),
    footer = $("#footer"),
    emailField = $('#email'),
    voteButton = $('#voteButton'),
    emailContainer = $('#emailContainer'),
    emailLabel = $('#emailContainer label'),
    emailReg = /[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/igm;


$(function(){
  // $('#flush').on('click', function(e){
  //   e.preventDefault();
  //   $.jStorage.flush();
  //   window.location = 'index.html';
  // });
  if ($.jStorage.get('voted') === true && $.jStorage.get('voted') !== null ) {
    output.html("<strong>You've already voted. Thanks so much!</strong>");
    hideElements();
    return false;
  }
  else {
    $.getJSON("data/talent.json?v="+version, function(data) {
      output.html($.mustache(template, data));
      buttonSetup();
    });

    voteButton.on('click', function(e){
      e.preventDefault();

      voteItUp();

      if (votes.email !== '') {
        $.ajax('submit.php', {
          cache: false,
          data: votes,
          type: 'post',
          success: function(d){
            if (d.status === '1') {
              output.html("<strong>" + d.message + "</strong>");
              hideElements();
              $.jStorage.set('voted', true);
            }
            else if (d.status === '-1') {
              output.html("<strong>" + d.message + "</strong>");
              hideElements();
              $.jStorage.set('voted', true);
            }
          }
        });
      }
      return false;
    });
  }
});

buttonSetup = function() {
  var btnClasses, btnTypes, voteTypes;
  btnTypes = [".loveit", ".likeit", ".indifferent"];
  btnClasses = ["panel-success-on", "panel-info-on", "panel-default-on"];
  voteTypes = ["primary", "secondary", "tertiary"];

  $.each(btnTypes, function(i, type) {
    $(type).on("click", function(e) {
      e.preventDefault();
      var _talentID = $(this).data("id");
      $("#talent-" + _talentID).removeClass("panel-info panel-success-on panel-info-on panel-default-on")
                               .addClass(btnClasses[i])
                               .data("voted", true)
                               .data("voteType", voteTypes[i]);
      return false;
    });
  });
};

voteItUp = function() {
  $.each($(".talent"), function(i, talent) {
    var _talent, _talentID, _voteType;
    _talent = $(talent);
    _talentID = _talent.data("id");
    _voteType = _talent.data("voteType");
    if (_talent.data("voted") === true) {
      switch (_voteType) {
        case "primary":
          votes.primary += _talentID;
        break;
        case "secondary":
          votes.secondary += _talentID;
        break;
        case "tertiary":
          votes.tertiary += _talentID;
        break;
      }
    } else {
      votes.tertiary += _talentID;
    }
  });
  if (validateEmail(emailField.val())) {
    emailContainer.removeClass('has-error');
    emailLabel.html('');
    votes.email = emailField.val();
  }
  else {
    emailContainer.addClass('has-error');
    emailLabel.html('Please enter a valid email address');
  }
};

hideElements = function() {
  emailField.hide();
  voteButton.hide();
  emailLabel.hide();
  $('#instructions').hide();
};

validateEmail = function(email) {
  return emailReg.test(email);
};