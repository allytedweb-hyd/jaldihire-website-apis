let regex = {
  allwhitespace: /(\s+)/gu,
  numbersregex: /^[0-9]*$/,
  mobileregex: /^[7-9][0-9]{0,8}$/,
  smallAlphabetsRegex: /^[a-z]*$/,
  capsAlphabetsRegex: /^[A-Z]*$/,
  alphabetsregex: /^[a-zA-Z ]*$/,
  alphaNumeric: /^([a-zA-Z\d_]){4,8}$/,
  emailregex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
  urlregex:
    /^(http[s]?:\/\/)?(www\.)?[a-zA-Z0-9]+([-]?\w+)\.([a-z]{2,12})(\.[a-z]{2,12})?$/,
  phone: /^\d{3}-\d{3}-\d{4}$/,
};
