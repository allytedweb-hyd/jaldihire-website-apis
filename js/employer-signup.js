$("#submitForm").hide();

let validationRules = [
  {
    element: "companyname",
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
    element: "emp_email",
    rules: {
      required: true,
      regex: regex.emailregex,
      // maxLength: 20,
    },
    errorMessages: {
      reqErr: "*Email Id is Required",
      regexErr: "*Only accepts Email Id",
      // maxLenErr: "*Should not exceed to 20 char",
    },
  },
  {
    element: "emp_mob",
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
    element: "emp_gst",
    rules: {
      required: true,
    },
    errorMessages: {
      reqErr: "*Gst Number is required",
    },
  },
  {
    element: "package",
    rules: {
      required: true,
    },
    errorMessages: {
      reqErr: "*Package is required",
    },
  },
  {
    element: "packmonths",
    rules: {
      required: true,
    },
    errorMessages: {
      reqErr: "*Package is required",
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
