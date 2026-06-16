function validateFormFields(fields) {
  let validationErrors = [];

  fields.forEach((each) => {
    console.log(each);
    let eleId = each.element;
    let element = document.getElementById(eleId);
    let value = element.value;
    let rules = each.rules;
    let errMsgs = each.errorMessages;
    console.log("error is", errMsgs);

    if (rules.required && !value) {
      handleErrorMsgs(element, validationErrors, errMsgs.reqErr);
    } else if (rules.regex && !checkRegex(value, rules.regex)) {
      handleErrorMsgs(element, validationErrors, errMsgs.regexErr);
    } else if (rules.minLength && value.length < rules.minLength) {
      handleErrorMsgs(element, validationErrors, errMsgs.minLenErr);
    } else if (rules.maxLength && value.length > rules.maxLength) {
      handleErrorMsgs(element, validationErrors, errMsgs.maxLenErr);
    } else {
      clearErrors(element);
    }
  });
  return validationErrors;
}

function handleErrorMsgs(ele, errArray, errMsg) {
  ele.classList.add("is-invalid");
  ele.nextElementSibling.innerHTML = errMsg;
  errArray.push(errMsg);
}
function clearErrors(ele) {
  ele.classList.remove("is-invalid");
  ele.classList.add("is-valid");
  ele.nextElementSibling.innerHTML = "";
}

function checkRegex(val, pattern) {
  return pattern.test(val);
}

function checkradio() {
  var getSelectedValue = document.querySelector('input[name="season"]:checked');
  if (getSelectedValue != null) {
    document.write("Radio button is selected");
  } else {
    document.write("Nothing has been selected");
  }
}

$("#addForm").click(function () {
  //Set the Valid Flag to True if one RadioButton from the Group of RadioButtons is checked.
  var isValid = $("input[name=radio_btn]").is(":checked");

  //Display error message if no RadioButton is checked.
  // $("#spnError")[0].style.display = isValid ? "none" : "block";
  if (isValid == "") {
    $("#errorText1").text("**required your Gender");
  }
  var fields = $("input[name='checkboxs']").serializeArray();
  if (fields.length === 0) {
    $("#errorText2").text("**select atleast one language");

    // cancel submit
    return false;
  }
});

// $('input [type="checkbox"]').click(function () {
//   if ($(this).is(":not(:checked)")) {
//     $("#errorText2").text("**required your skill");
//   }
// });
