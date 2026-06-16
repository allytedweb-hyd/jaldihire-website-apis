$("#submitForm").hide();

let validationRules = [
  {
    element: "full_name",
    rules: {
      required: true,
      regex: regex.alphabetsregex,
      minLength: 3,
    },
    errorMessages: {
      reqErr: "*Name is Required",
      regexErr: "*Only accepts Alphabets",
      minLenErr: "*Should have minimum of 3 char",
    },
  },

  {
    element: "mail",
    rules: {
      required: true,
      regex: regex.emailregex,
    },
    errorMessages: {
      reqErr: "*Email Id is Required",
      regexErr: "*Only accepts Email Id",
    },
  },
  {
    element: "mobile_number",
    rules: {
      required: true,
      regex: regex.numbersregex,
      minLength: 10,
      maxLength: 10,
    },
    errorMessages: {
      reqErr: "*Mobile Number is Required",
      regexErr: "*Only accepts mobile Number",
      minLenErr: "*Should have minimum of 10 char",
      maxLenErr: "*Should not exceed to 10 char",
    },
  },
  {
    element: "password",
    rules: {
      required: true,
    },
    errorMessages: {
      reqErr: "*Password is required",
    },
  },
  {
    element: "cnf_password",
    rules: {
      required: true,
    },
    errorMessages: {
      reqErr: "*Confirm Password is required",
    },
  },
];

$("#addForm").click(function () {
  let isFormValid = validateFormFields(validationRules);
  if (isFormValid.length > 0) {
    return;
  } else {
    $("#addForm").hide();
    $("#submitForm").show();
    $("#submitForm").trigger("click");
  }
});
