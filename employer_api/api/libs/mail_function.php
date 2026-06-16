<?php




function jobPstMail($body)
{
  return '
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <!DOCTYPE html>
  <!DOCTYPE html>
  <html>
    <head>
      <link rel="preconnect" href="https://fonts.googleapis.com" />
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
      <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet"
      />
      <link
        rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
      />
      <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
      <script
        src="https://kit.fontawesome.com/5f59ca6ad3.js"
        crossorigin="anonymous"
      ></script>
      <style>
        body {
          background: #bfcbcc;
          font-family: "Poppins", sans-serif;
        }
        button {
          border: navajowhite;
          padding: 10px;
          background-color: #2281ff;
          border-radius: 10px;
          color: #fff;
          font-weight: 600;
          width: 35%;
          margin: 12px auto 25px;
        }
        hr {
          border: 1px solid #ed9292;
          width: 50%;
        }
        h1,h2,h3,h4,h5,h6,p{
          color:#000;
        }
      </style>
    </head>
    <body>
      <div class="container" style="background-color:#F2F6FC">
        <div class="row">
          <div class="col-md-12">
            <div
              class="main-out"
              style="
                margin: auto;
                text-align: center;
                max-width: 50% !important;
                padding: 50px;
              "
            >
              <div
                style="
                  background: black;
                  display: flex;
                  align-items: center;
                  padding: 7px 10px 0px;
                  color: #fff;
                  border-radius: 5px 5px 0px 0px;
                "
              >
  
                <div
                  class="title"
                  style="margin-bottom: 5px; display: flex; align-items: center; width:100%;"
                >
                  <img
                    src="	https://admin.jaldihire.com/assets/images/logos/new-logo.png"
                    alt=""
                    style="height: 50px"
                  />
  
                  
                    <img
                      src="https://admin.jaldihire.com/assets/images/jaldi-icon.png"
                      style="height: 20px; padding-top:12px;"
                    />
                </div>
  
                <div style = "
                width:100%;">
                  <p
                    style="
                      line-height: 19px;
                      font-size: 11px;
                      margin-bottom: 2px;
                      text-align: end;
                      word-spacing: 2px;
                      color:#fff;
                    "
                  >
                    Need Help:
                    <span style="color: #F7E54E">Contact Us</span>
                    <br />Customer Number:3456784567
                  </p>
                </div>
              </div>
  
              <div
                class="mail-box1"
                style="
                  background: #fff;
                  padding: 28px;
                  box-shadow:0px 5px 15px #000 !important;
                  border-radius: 5px;
                  border: 3px solid #f7f7f7;
                "
              >
  
  
              
                <div style = "text-align:left;">
                ' . $body . '
                  <br /><br />
              </div>
  
  
                
  
                <button 
                
                  style="
                    color: white;
                    width: auto;
                    font-weight: 600;
                    padding: 10px 15px;
                    background-color: black;
                    display: flex;
                    position: relative;
                    right: 12px;
                    border-radius: 6px;
                    margin: 12px auto 25px;
                  "
                >
                <a 
                href="https://jaldihire.com.com/workspaces/" target="_blank"
                style = "text-decoration:none; color: #fff;">
               Open Agent Dashboard
              </a>
                </button>
  
  
                <div>
                
  
                  <div
                    style="
                      text-align: start;
  
                      font-size: 12px;
                    "
                  >
                    <p style = "text-align:center">With Regards- <br>Jaldhire Team</p>
                   
                  </div>
  
                 
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </body>
  </html>
  ';
}

function applicantSchedule($body)
{
  return '
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <!DOCTYPE html>
  <!DOCTYPE html>
  <html>
    <head>
      <link rel="preconnect" href="https://fonts.googleapis.com" />
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
      <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet"
      />
      <link
        rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
      />
      <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
      <script
        src="https://kit.fontawesome.com/5f59ca6ad3.js"
        crossorigin="anonymous"
      ></script>
      <style>
        body {
          background: #bfcbcc;
          font-family: "Poppins", sans-serif;
        }
        button {
          border: navajowhite;
          padding: 10px;
          background-color: #2281ff;
          border-radius: 10px;
          color: #fff;
          font-weight: 600;
          width: 35%;
          margin: 12px auto 25px;
        }
        hr {
          border: 1px solid #ed9292;
          width: 50%;
        }
        h1,h2,h3,h4,h5,h6,p{
          color:#000;
        }
      </style>
    </head>
    <body>
      <div class="container" style="background-color:#F2F6FC">
        <div class="row">
          <div class="col-md-12">
            <div
              class="main-out"
              style="
                margin: auto;
                text-align: center;
                max-width: 50% !important;
                padding: 50px;
              "
            >
              <div
                style="
                  background: black;
                  display: flex;
                  align-items: center;
                  padding: 7px 10px 0px;
                  color: #fff;
                  border-radius: 5px 5px 0px 0px;
                "
              >
  
                <div
                  class="title"
                  style="margin-bottom: 5px; display: flex; align-items: center; width:100%;"
                >
                  <img
                    src="	https://admin.jaldihire.com/assets/images/logos/new-logo.png"
                    alt=""
                    style="height: 50px"
                  />
  
                  
                    <img
                      src="https://admin.jaldihire.com/assets/images/jaldi-icon.png"
                      style="height: 20px; padding-top:12px;"
                    />
                </div>
  
                <div style = "
                width:100%;">
                  <p
                    style="
                      line-height: 19px;
                      font-size: 11px;
                      margin-bottom: 2px;
                      text-align: end;
                      word-spacing: 2px;
                      color:#fff;
                    "
                  >
                    Need Help:
                    <span style="color: #F7E54E">Contact Us</span>
                    <br />Customer Number:3456784567
                  </p>
                </div>
              </div>
  
              <div
                class="mail-box1"
                style="
                  background: #fff;
                  padding: 28px;
                  box-shadow:0px 5px 15px #000 !important;
                  border-radius: 5px;
                  border: 3px solid #f7f7f7;
                "
              >
  
  
              
                <div style = "text-align:left;">
                ' . $body . '
                  <br /><br />
              </div>
  
  
                
  
                <button 
                
                  style="
                    color: white;
                    width: auto;
                    font-weight: 600;
                    padding: 10px 15px;
                    background-color: black;
                    display: flex;
                    position: relative;
                    right: 12px;
                    border-radius: 6px;
                    margin: 12px auto 25px;
                  "
                >
                <a 
                href="https://applicant.jaldihire.com/" target="_blank"
                style = "text-decoration:none; color: #fff;">
               Open Applicant Dashboard
              </a>
                </button>
  
  
                <div>
                
  
                  <div
                    style="
                      text-align: start;
  
                      font-size: 12px;
                    "
                  >
                    <p style = "text-align:center">With Regards- <br>Jaldhire Team</p>
                   
                  </div>
  
                 
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </body>
  </html>
  ';
}
function recruiterBody($body)
{
  return '
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <!DOCTYPE html>
  <!DOCTYPE html>
  <html>
    <head>
      <link rel="preconnect" href="https://fonts.googleapis.com" />
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
      <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet"
      />
      <link
        rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
      />
      <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
      <script
        src="https://kit.fontawesome.com/5f59ca6ad3.js"
        crossorigin="anonymous"
      ></script>
      <style>
        body {
          background: #bfcbcc;
          font-family: "Poppins", sans-serif;
        }
        button {
          border: navajowhite;
          padding: 10px;
          background-color: #2281ff;
          border-radius: 10px;
          color: #fff;
          font-weight: 600;
          width: 35%;
          margin: 12px auto 25px;
        }
        hr {
          border: 1px solid #ed9292;
          width: 50%;
        }
        h1,h2,h3,h4,h5,h6,p{
          color:#000;
        }
      </style>
    </head>
    <body>
      <div class="container" style="background-color:#F2F6FC">
        <div class="row">
          <div class="col-md-12">
            <div
              class="main-out"
              style="
                margin: auto;
                text-align: center;
                max-width: 50% !important;
                padding: 50px;
              "
            >
              <div
                style="
                  background: black;
                  display: flex;
                  align-items: center;
                  padding: 7px 10px 0px;
                  color: #fff;
                  border-radius: 5px 5px 0px 0px;
                "
              >
  
                <div
                  class="title"
                  style="margin-bottom: 5px; display: flex; align-items: center; width:100%;"
                >
                  <img
                    src="https://admin.jaldihire.com/assets/images/logos/new-logo.png"
                    alt=""
                    style="height: 50px"
                  />
  
                  
                    <img
                      src="https://admin.jaldihire.com/assets/images/jaldi-icon.png"
                      style="height: 20px; padding-top:12px;"
                    />
                </div>
  
                <div style = "
                width:100%;">
                  <p
                    style="
                      line-height: 19px;
                      font-size: 11px;
                      margin-bottom: 2px;
                      text-align: end;
                      word-spacing: 2px;
                      color:#fff;
                    "
                  >
                    Need Help:
                    <span style="color: #F7E54E">Contact Us</span>
                    <br />Customer Number:3456784567
                  </p>
                </div>
              </div>
  
              <div
                class="mail-box1"
                style="
                  background: #fff;
                  padding: 28px;
                  box-shadow:0px 5px 15px #000 !important;
                  border-radius: 5px;
                  border: 3px solid #f7f7f7;
                "
              >
  
  
              
                <div style = "text-align:left;">
                ' . $body . '
                  <br /><br />
              </div>
  
  
                
  
                <button 
                
                  style="
                    color: white;
                    width: auto;
                    font-weight: 600;
                    padding: 10px 15px;
                    background-color: black;
                    display: flex;
                    position: relative;
                    right: 12px;
                    border-radius: 6px;
                    margin: 12px auto 25px;
                  "
                >
                <a 
                href="https://jaldihire.com/workspaces/" target="_blank"
                style = "text-decoration:none; color: #fff;">
               Open Dashboard
              </a>
                </button>
  
  
                <div>
                
  
                  <div
                    style="
                      text-align: start;
  
                      font-size: 12px;
                    "
                  >
                    <p style = "text-align:center">With Regards- <br>Jaldhire Team</p>
                   
                  </div>
  
                 
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </body>
  </html>
  ';
}


function agentwithoutjob($body)
{
  return '
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <!DOCTYPE html>
  <!DOCTYPE html>
  <html
  xmlns="http://www.w3.org/1999/xhtml"
  xmlns:v="urn:schemas-microsoft-com:vml"
  xmlns:o="urn:schemas-microsoft-com:office:office"
>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="x-apple-disable-message-reformatting" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <title></title>
    <!--[if !mso]><!-->
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
      rel="stylesheet"
    />
    <!--<![endif]-->
    <style type="text/css">
      @import url("https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap");
      body {
        margin: 0;
        padding: 0;
        font-family: "Roboto", sans-serif !important;
      }
      @media only screen and (min-width: 620px) {
        .u-row {
          width: 600px !important;
        }

        .u-row .u-col {
          vertical-align: top;
        }

        .u-row .u-col-33p33 {
          width: 199.98px !important;
        }

        .u-row .u-col-66p67 {
          width: 400.02px !important;
        }

        .u-row .u-col-100 {
          width: 600px !important;
        }
      }

      @media (max-width: 620px) {
        .u-row-container {
          max-width: 100% !important;
          padding-left: 0px !important;
          padding-right: 0px !important;
        }

        .u-row .u-col {
          min-width: 320px !important;
          max-width: 100% !important;
          display: block !important;
        }

        .u-row {
          width: 100% !important;
        }

        .u-col {
          width: 100% !important;
        }

        .u-col > div {
          margin: 0 auto;
        }
      }

      body {
        margin: 0;
        padding: 0;
        font-family: "Roboto", sans-serif;
      }

      table,
      tr,
      td {
        vertical-align: top;
        border-collapse: collapse;
      }

      p {
        margin: 0;
      }

      .ie-container table,
      .mso-container table {
        table-layout: fixed;
      }

      * {
        line-height: inherit;
      }

      a[x-apple-data-detectors="true"] {
        color: inherit !important;
        text-decoration: none !important;
      }

      table,
      td {
        color: #000000;
      }

      #u_body a {
        color: #0000ee;
        text-decoration: underline;
      }

      #u_content_heading_49 a {
        color: #1c9ca1;
      }

      #u_content_heading_50 a {
        color: #1c9ca1;
      }
      .trinel::after {
        position: absolute;
        content: "";
        width: 0px;
        height: 0px;
        border-style: solid;
        border-width: 10px 15px 10px 0;
        border-color: transparent #fefefd transparent transparent;
        display: inline-block;
        vertical-align: middle;
        margin-right: 5px;
        top: 100px;
        rotate: 32deg;
        right: 55px;
      }

      @media (max-width: 480px) {
        #u_content_button_5 .v-size-width {
          width: 76% !important;
        }
        .caleder-img {
          width: 150px !important;
          height: 150px !important;
          padding-right: 10px !important;
          margin-top: 40px !important;
        }
        .logo-img {
          max-width: 140px !important;
          padding-top: 26px !important;
          padding-left: 5px !important;
        }
        .man-img {
          width: 60% !important;
          margin-top: 40px !important;
          margin-left: 37px !important;
        }
      }
      .caleder-img {
        width: 180px;
        height: 180px;
        padding-right: 40px;
        margin-top: 40px;
      }
    </style>
  </head>

  <body
    class="clean-body u_body"
    style="
      margin: 0;
      padding: 0;
      -webkit-text-size-adjust: 100%;
      background-color: #f0f0f0;
      color: #000000;
    "
  >
    <!--[if IE]><div class="ie-container"><![endif]-->
    <!--[if mso]><div class="mso-container"><![endif]-->
    <table
      id="u_body"
      style="
        border-collapse: collapse;
        table-layout: fixed;
        border-spacing: 0;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
        vertical-align: top;
        min-width: 320px;

        margin: 0 auto;
        background-color: #f0f0f0;
        width: 100%;
      "
      cellpadding="0"
      cellspacing="0"
    >
      <tbody>
        <tr style="vertical-align: top">
          <td
            style="
              word-break: break-word;
              border-collapse: collapse !important;
              vertical-align: top;
            "
          >
            <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color: #f0f0f0;"><![endif]-->

            <div
              class="u-row-container"
              style="padding: 0px; background-color: transparent"
            >
              <div
                class="u-row"
                style="
                  margin: 0 auto;
                  min-width: 320px;
                  max-width: 600px;
                  overflow-wrap: break-word;
                  word-wrap: break-word;
                  word-break: break-word;
                  background-color: transparent;
                "
              >
                <div
                  style="
                    border-collapse: collapse;
                    display: table;
                    width: 100%;
                    height: 100%;
                    background-color: transparent;
                  "
                >
                  <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: transparent;"><![endif]-->

                  <!--[if (mso)|(IE)]><td align="center" width="600" style="background-color: #ddffe7;width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
                  <div
                    class="u-col u-col-100"
                    style="
                      max-width: 320px;
                      min-width: 600px;
                      display: table-cell;
                      vertical-align: top;
                    "
                  >
                    <div
                      style="
                        background-color: #072c50;
                        height: 100%;
                        width: 100% !important;
                        background-size: cover;
                        background-repeat: no-repeat;
                        border-radius: 20px 20px 0px 0px;
                      "
                    >
                      <!--[if (!mso)&(!IE)]><!-->
                      <div
                        style="
                          margin-top: 50px;
                          box-sizing: border-box;
                          height: 100%;
                          padding: 0px;
                          border-top: 0px solid transparent;
                          border-left: 0px solid transparent;
                          border-right: 0px solid transparent;
                          border-bottom: 0px solid transparent;
                        "
                      >
                        <!--<![endif]-->
                        <table
                          style="font-family: arial, helvetica, sans-serif"
                          role="presentation"
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          border="0"
                        >
                          <tbody>
                            <tr>
                              <td
                                style="
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 10px 10px 0px 3px;
                                  font-family: arial, helvetica, sans-serif;
                                "
                                align="left"
                              >
                                <table
                                  width="100%"
                                  cellpadding="0"
                                  cellspacing="0"
                                  border="0"
                                >
                                  <tr>
                                    <td
                                      style="
                                        padding-right: 0px;
                                        padding-left: 0px;
                                        text-align: start;
                                      "
                                      align="center"
                                    >
                                      <div>
                                        <img
                                          align="center"
                                          border="0"
                                          src="https://admin.jaldihire.com/assets/images/Jaldihire_Logo_Name_Logo.png"
                                          alt="image"
                                          title="image"
                                          class="logo-img"
                                          style="
                                            outline: none;
                                            text-decoration: none;
                                            -ms-interpolation-mode: bicubic;
                                            clear: both;
                                            display: inline-block !important;
                                            border: none;
                                            height: auto;
                                            float: none;
                                            width: 100%;
                                            max-width: 190px;
                                            padding-top: 15px;
                                            padding-left: 35px;
                                            padding-bottom: 20px;
                                          "
                                        />
                                        <div
                                          class=""
                                          style="
                                            display: flex;
                                            width: 100%;
                                            align-items: center;
                                          "
                                        >
                                          <div
                                            style="
                                              width: 100%;
                                              padding-left: 20px;
                                            "
                                          >
                                            <img
                                              src="https://admin.jaldihire.com/assets/images/image.png"
                                              alt=""
                                              style="width: 100%"
                                            />
                                          </div>
                                          <div
                                            style="
                                              width: 100%;
                                              text-align: center;
                                            "
                                          >
                                            <img
                                              src="https://admin.jaldihire.com/assets/images/image2.png"
                                              alt=""
                                              style="width: 50%"
                                            />
                                          </div>
                                          <div style="width: 100%">
                                            <img
                                              src="https://admin.jaldihire.com/assets/images/image3.png"
                                              alt=""
                                              style="width: 100%"
                                            />
                                          </div>
                                        </div>
                                      </div>
                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <!--[if (!mso)&(!IE)]><!-->
                      </div>
                      <!--<![endif]-->
                    </div>
                  </div>
                  <!--[if (mso)|(IE)]></td><![endif]-->
                  <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
                </div>
              </div>
            </div>

            <div
              class="u-row-container"
              style="padding: 0px; background-color: transparent"
            >
              <div
                class="u-row"
                style="
                  margin: 0 auto;
                  min-width: 320px;
                  max-width: 600px;
                  overflow-wrap: break-word;
                  word-wrap: break-word;
                  word-break: break-word;
                  background-color: transparent;
                "
              >
                <div
                  style="
                    border-collapse: collapse;
                    display: table;
                    width: 100%;
                    height: 100%;
                    background-color: transparent;
                  "
                >
                  <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: transparent;"><![endif]-->

                  <!--[if (mso)|(IE)]><td align="center" width="600" style="background-color: #ffffff;width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;" valign="top"><![endif]-->
                  <div
                    class="u-col u-col-100"
                    style="
                      max-width: 320px;
                      min-width: 600px;
                      display: table-cell;
                      vertical-align: top;
                    "
                  >
                    <div
                      style="
                        background-color: #f4faff;
                        height: 100%;
                        width: 100% !important;
                        border-radius: 0px;
                        -webkit-border-radius: 0px;
                        -moz-border-radius: 0px;
                      "
                    >
                      <!--[if (!mso)&(!IE)]><!-->
                      <div
                        style="
                          box-sizing: border-box;
                          height: 100%;
                          padding: 0px;
                          border-top: 0px solid transparent;
                          border-left: 0px solid transparent;
                          border-right: 0px solid transparent;
                          border-bottom: 0px solid transparent;
                          border-radius: 0px;
                          -webkit-border-radius: 0px;
                          -moz-border-radius: 0px;
                          margin-left: 30px;
                        "
                      >
                        <!--<![endif]-->
                        <table
                          style="font-family: arial, helvetica, sans-serif"
                          role="presentation"
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          border="0"
                        >
                          <tbody>
                            <tr>
                              <td
                                style="
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 30px 10px 10px;
                                  font-family: arial, helvetica, sans-serif;
                                "
                                align="left"
                              >
                                <!--[if mso]><table width="100%"><tr><td><![endif]-->
                                <h4
                                  style="
                                    margin: 0px;
                                    line-height: 140%;
                                    text-align: left;
                                    word-wrap: break-word;
                                    font-size: 12px;
                                    font-weight: 700;
                                  "
                                >
                                  <h2
                                    style="
                                      white-space: normal;
                                      background-color: #f4faff;
                                      color: #003f7d;
                                      margin: 0px;
                                    "
                                  >
                                    Advisor is on the way
                                  </h2>
                                </h4>
                                <!--[if mso]></td></tr></table><![endif]-->
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <table
                          style="font-family: arial, helvetica, sans-serif"
                          role="presentation"
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          border="0"
                        >
                          <tbody>
                            <tr>
                              <td
                                style="
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 11px;
                                  font-family: arial, helvetica, sans-serif;
                                "
                                align="left"
                              >
                                <div
                                  style="
                                    font-size: 14px;
                                    line-height: 140%;
                                    text-align: left;
                                    word-wrap: break-word;
                                  "
                                >
                                  <p
                                    style="
                                      color: #00304d;
                                      white-space: normal;
                                      background-color: #f4faff;
                                      line-height: 140%;
                                    "
                                  >
                                  Dear RM,<br/>
' . $body . '
                                    
                                  </p>
                                </div>
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <table
                          style="font-family: arial, helvetica, sans-serif"
                          role="presentation"
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          border="0"
                        >
                          <tbody>
                            <tr>
                              <td
                                style="
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 0px 10px;
                                  font-family: arial, helvetica, sans-serif;
                                "
                                align="left"
                              >
                                <div
                                  style="
                                    font-size: 14px;
                                    line-height: 140%;
                                    text-align: left;
                                    word-wrap: break-word;
                                  "
                                >
                      
                                </div>
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <!--[if (!mso)&(!IE)]><!-->
                      </div>
                      <!--<![endif]-->
                    </div>
                  </div>
                  <!--[if (mso)|(IE)]></td><![endif]-->
                  <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
                </div>
              </div>
            </div>

            <div
              class="u-row-container"
              style="padding: 0px 0px 0px; background-color: transparent"
            >
              <div
                class="u-row"
                style="
                  margin: 0 auto;
                  min-width: 320px;
                  max-width: 600px;
                  overflow-wrap: break-word;
                  word-wrap: break-word;
                  word-break: break-word;
                  background-color: #f4faff;
                  margin-bottom: 40px;
                "
              >
                <div
                  style="
                    border-collapse: collapse;
                    display: table;
                    width: 100%;
                    height: 100%;
                    background-color: transparent;
                  "
                >
                  <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 2px 0px 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: #ffffff;"><![endif]-->

                  <!--[if (mso)|(IE)]><td align="center" width="600" style="width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;" valign="top"><![endif]-->
                  <div
                    class="u-col u-col-100"
                    style="
                      max-width: 320px;
                      min-width: 600px;
                      display: table-cell;
                      vertical-align: top;
                    "
                  >
                    <div
                      style="
                        height: 100%;
                        width: 100% !important;
                        border-radius: 0px;
                        -webkit-border-radius: 0px;
                        -moz-border-radius: 0px;
                      "
                    >
                      <!--[if (!mso)&(!IE)]><!-->
                      <div
                        style="
                          box-sizing: border-box;
                          height: 100%;
                          padding: 0px;
                          border-top: 0px solid transparent;
                          border-left: 0px solid transparent;
                          border-right: 0px solid transparent;
                          border-bottom: 0px solid transparent;
                          border-radius: 0px;
                          -webkit-border-radius: 0px;
                          -moz-border-radius: 0px;
                          margin-left: 30px;
                        "
                      >
                        <!--<![endif]-->
                        <table
                          style="font-family: arial, helvetica, sans-serif"
                          role="presentation"
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          border="0"
                        >
                          <tbody>
                            <tr>
                              <td
                                style="
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 10px;
                                  font-family: arial, helvetica, sans-serif;
                                "
                                align="left"
                              >
                                <!--[if mso]><table width="100%"><tr><td><![endif]-->
                               
                                <!--[if mso]></td></tr></table><![endif]-->
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <table
                          id="u_content_button_5"
                          style="font-family: arial, helvetica, sans-serif"
                          role="presentation"
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          border="0"
                        >
                          <tbody>
                            <tr>
                              <td
                                style="
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 10px;
                                  font-family: arial, helvetica, sans-serif;
                                "
                                align="left"
                              >
                                <!--[if mso
                                  ]><style>
                                    .v-button {
                                      background: transparent !important;
                                    }
                                  </style><!
                                [endif]-->
                                <div align="left" style="text-align: center">
                                  <!--[if mso]><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="https://unlayer.com" style="height:34px; v-text-anchor:middle; width:140px;" arcsize="12%"  strokecolor="#000000" strokeweight="2px" fillcolor="#000000"><w:anchorlock/><center style="color:#ffffff;"><![endif]-->
                                  <a
                                    href="#"
                                    target="_blank"
                                    class="v-button v-size-width"
                                    style="
                                      box-sizing: border-box;
                                      display: inline-block;
                                      text-decoration: none;
                                      -webkit-text-size-adjust: none;
                                      text-align: center;
                                      color: #ffffff;
                                      background-color: #0e69c1;
                                      border-radius: 4px;
                                      -webkit-border-radius: 4px;
                                      -moz-border-radius: 4px;
                                      width: auto;
                                      max-width: 100%;
                                      overflow-wrap: break-word;
                                      word-break: break-word;
                                      word-wrap: break-word;
                                      mso-border-alt: none;
                                      border-top-width: 2px;
                                      border-top-style: solid;
                                      border-top-color: #0e69c1;
                                      border-left-width: 2px;
                                      border-left-style: solid;
                                      border-left-color: #0e69c1;
                                      border-right-width: 2px;
                                      border-right-style: solid;
                                      border-right-color: #0e69c1;
                                      border-bottom-width: 2px;
                                      border-bottom-style: solid;
                                      border-bottom-color: #0e69c1;
                                      font-size: 18px;
                                      margin-bottom: 20px;
                                    "
                                  >
                                    <span
                                      style="
                                        display: block;
                                        padding: 10px 30px 8px;
                                        line-height: 90%;
                                      "
                                      #download
                                      >Check Status
                                    </span>
                                  </a>
                                  <!--[if mso]></center></v:roundrect><![endif]-->
                                </div>
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <!--[if (!mso)&(!IE)]><!-->
                      </div>
                      <!--<![endif]-->
                    </div>
                  </div>
                  <!--[if (mso)|(IE)]></td><![endif]-->
                  <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
                </div>
              </div>
            </div>

            <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
          </td>
        </tr>
      </tbody>
    </table>
    <!--[if mso]></div><![endif]-->
    <!--[if IE]></div><![endif]-->
  </body>
</html>

  ';
}
function agentwithjob($body)
{
  return '
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <!DOCTYPE html>
  <!DOCTYPE html>
 <html
  xmlns="http://www.w3.org/1999/xhtml"
  xmlns:v="urn:schemas-microsoft-com:vml"
  xmlns:o="urn:schemas-microsoft-com:office:office"
>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="x-apple-disable-message-reformatting" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <title></title>
    <!--[if !mso]><!-->
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
      rel="stylesheet"
    />
    <!--<![endif]-->
    <style type="text/css">
      @import url("https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap");
      body {
        margin: 0;
        padding: 0;
        font-family: "Roboto", sans-serif !important;
      }
      @media only screen and (min-width: 620px) {
        .u-row {
          width: 600px !important;
        }

        .u-row .u-col {
          vertical-align: top;
        }

        .u-row .u-col-33p33 {
          width: 199.98px !important;
        }

        .u-row .u-col-66p67 {
          width: 400.02px !important;
        }

        .u-row .u-col-100 {
          width: 600px !important;
        }
      }

      @media (max-width: 620px) {
        .u-row-container {
          max-width: 100% !important;
          padding-left: 0px !important;
          padding-right: 0px !important;
        }

        .u-row .u-col {
          min-width: 320px !important;
          max-width: 100% !important;
          display: block !important;
        }

        .u-row {
          width: 100% !important;
        }

        .u-col {
          width: 100% !important;
        }

        .u-col > div {
          margin: 0 auto;
        }
      }

      body {
        margin: 0;
        padding: 0;
        font-family: "Roboto", sans-serif;
      }

      table,
      tr,
      td {
        vertical-align: top;
        border-collapse: collapse;
      }

      p {
        margin: 0;
      }

      .ie-container table,
      .mso-container table {
        table-layout: fixed;
      }

      * {
        line-height: inherit;
      }

      a[x-apple-data-detectors="true"] {
        color: inherit !important;
        text-decoration: none !important;
      }

      table,
      td {
        color: #000000;
      }

      #u_body a {
        color: #0000ee;
        text-decoration: underline;
      }

      #u_content_heading_49 a {
        color: #1c9ca1;
      }

      #u_content_heading_50 a {
        color: #1c9ca1;
      }
      .trinel::after {
        position: absolute;
        content: "";
        width: 0px;
        height: 0px;
        border-style: solid;
        border-width: 10px 15px 10px 0;
        border-color: transparent #fefefd transparent transparent;
        display: inline-block;
        vertical-align: middle;
        margin-right: 5px;
        top: 100px;
        rotate: 32deg;
        right: 55px;
      }

      @media (max-width: 480px) {
        #u_content_button_5 .v-size-width {
          width: 76% !important;
        }
        .caleder-img {
          width: 150px !important;
          height: 150px !important;
          padding-right: 10px !important;
          margin-top: 40px !important;
        }
        .logo-img {
          max-width: 140px !important;
          padding-top: 26px !important;
          padding-left: 5px !important;
        }
        .man-img {
          width: 60% !important;
          margin-top: 40px !important;
          margin-left: 37px !important;
        }
      }
      .caleder-img {
        width: 180px;
        height: 180px;
        padding-right: 40px;
        margin-top: 40px;
      }
    </style>
  </head>

  <body
    class="clean-body u_body"
    style="
      margin: 0;
      padding: 0;
      -webkit-text-size-adjust: 100%;
      background-color: #f0f0f0;
      color: #000000;
    "
  >
    <!--[if IE]><div class="ie-container"><![endif]-->
    <!--[if mso]><div class="mso-container"><![endif]-->
    <table
      id="u_body"
      style="
        border-collapse: collapse;
        table-layout: fixed;
        border-spacing: 0;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
        vertical-align: top;
        min-width: 320px;

        margin: 0 auto;
        background-color: #f0f0f0;
        width: 100%;
      "
      cellpadding="0"
      cellspacing="0"
    >
      <tbody>
        <tr style="vertical-align: top">
          <td
            style="
              word-break: break-word;
              border-collapse: collapse !important;
              vertical-align: top;
            "
          >
            <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color: #f0f0f0;"><![endif]-->

            <div
              class="u-row-container"
              style="padding: 0px; background-color: transparent"
            >
              <div
                class="u-row"
                style="
                  margin: 0 auto;
                  min-width: 320px;
                  max-width: 600px;
                  overflow-wrap: break-word;
                  word-wrap: break-word;
                  word-break: break-word;
                  background-color: transparent;
                "
              >
                <div
                  style="
                    border-collapse: collapse;
                    display: table;
                    width: 100%;
                    height: 100%;
                    background-color: transparent;
                  "
                >
                  <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: transparent;"><![endif]-->

                  <!--[if (mso)|(IE)]><td align="center" width="600" style="background-color: #ddffe7;width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
                  <div
                    class="u-col u-col-100"
                    style="
                      max-width: 320px;
                      min-width: 600px;
                      display: table-cell;
                      vertical-align: top;
                    "
                  >
                    <div
                      style="
                        background-color: #072c50;
                        height: 100%;
                        width: 100% !important;
                        background-size: cover;
                        background-repeat: no-repeat;
                        border-radius: 20px 20px 0px 0px;
                      "
                    >
                      <!--[if (!mso)&(!IE)]><!-->
                      <div
                        style="
                          margin-top: 50px;
                          box-sizing: border-box;
                          height: 100%;
                          padding: 0px;
                          border-top: 0px solid transparent;
                          border-left: 0px solid transparent;
                          border-right: 0px solid transparent;
                          border-bottom: 0px solid transparent;
                        "
                      >
                        <!--<![endif]-->
                        <table
                          style="font-family: arial, helvetica, sans-serif"
                          role="presentation"
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          border="0"
                        >
                          <tbody>
                            <tr>
                              <td
                                style="
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 10px 10px 10px 3px;
                                  font-family: arial, helvetica, sans-serif;
                                "
                                align="left"
                              >
                                <table
                                  width="100%"
                                  cellpadding="0"
                                  cellspacing="0"
                                  border="0"
                                >
                                  <tr>
                                    <td
                                      style="
                                        padding-right: 0px;
                                        padding-left: 0px;
                                        text-align: start;
                                      "
                                      align="center"
                                    >
                                      <div>
                                        <img
                                          align="center"
                                          border="0"
                                          src="https://admin.jaldihire.com/assets/images/Jaldihire_Logo_Name_Logo.png"
                                          alt="image"
                                          title="image"
                                          class="logo-img"
                                          style="
                                            outline: none;
                                            text-decoration: none;
                                            -ms-interpolation-mode: bicubic;
                                            clear: both;
                                            display: inline-block !important;
                                            border: none;
                                            height: auto;
                                            float: none;
                                            width: 100%;
                                            max-width: 190px;
                                            padding-top: 15px;
                                            padding-left: 35px;
                                            padding-bottom: 20px;
                                          "
                                        />
                                        <div
                                          class=""
                                          style="
                                            display: flex;
                                            width: 100%;
                                            align-items: center;
                                          "
                                        >
                                          <div style="width: 100%">
                                            <img
                                              src="https://admin.jaldihire.com/assets/images/mann-b.png"
                                              alt=""
                                              style="width: 100%"
                                            />
                                          </div>
                                          <div
                                            style="
                                              background-color: #1577d7;
                                              border-radius: 20px;
                                              width: 75%;
                                              padding: 20px 0px 20px 20px;
                                              text-align: start;
                                              margin-right: 35px;
                                            "
                                          >
                                            <div
                                              style="
                                                color: #f6c045;
                                                font-weight: 600;
                                                background-color: #072c50;
                                                padding: 10px 10px 10px 20px;
                                                border-radius: 20px 0px 0px 20px;
                                              "
                                            >
                                              <p>Hi,</p>
                                              <p>I am your Advisor</p>
                                            </div>
                                            <p
                                              style="
                                                color: #fff;
                                                line-height: 28px;
                                                margin-top: 30px;
                                              "
                                            >
                                              I am here to provide you with
                                              personalized support and guidance
                                              throughout your job posting to
                                              applicant selection process
                                            </p>
                                          </div>
                                        </div>
                                      </div>
                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <!--[if (!mso)&(!IE)]><!-->
                      </div>
                      <!--<![endif]-->
                    </div>
                  </div>
                  <!--[if (mso)|(IE)]></td><![endif]-->
                  <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
                </div>
              </div>
            </div>

            <div
              class="u-row-container"
              style="padding: 0px; background-color: transparent"
            >
              <div
                class="u-row"
                style="
                  margin: 0 auto;
                  min-width: 320px;
                  max-width: 600px;
                  overflow-wrap: break-word;
                  word-wrap: break-word;
                  word-break: break-word;
                  background-color: transparent;
                "
              >
                <div
                  style="
                    border-collapse: collapse;
                    display: table;
                    width: 100%;
                    height: 100%;
                    background-color: transparent;
                  "
                >
                  <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: transparent;"><![endif]-->

                  <!--[if (mso)|(IE)]><td align="center" width="600" style="background-color: #ffffff;width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;" valign="top"><![endif]-->
                  <div
                    class="u-col u-col-100"
                    style="
                      max-width: 320px;
                      min-width: 600px;
                      display: table-cell;
                      vertical-align: top;
                    "
                  >
                    <div
                      style="
                        background-color: #f4faff;
                        height: 100%;
                        width: 100% !important;
                        border-radius: 0px;
                        -webkit-border-radius: 0px;
                        -moz-border-radius: 0px;
                      "
                    >
                      <!--[if (!mso)&(!IE)]><!-->
                      <div
                        style="
                          box-sizing: border-box;
                          height: 100%;
                          padding: 0px;
                          border-top: 0px solid transparent;
                          border-left: 0px solid transparent;
                          border-right: 0px solid transparent;
                          border-bottom: 0px solid transparent;
                          border-radius: 0px;
                          -webkit-border-radius: 0px;
                          -moz-border-radius: 0px;
                          margin-left: 30px;
                        "
                      >
                        <!--<![endif]-->
                        <table
                          style="font-family: arial, helvetica, sans-serif"
                          role="presentation"
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          border="0"
                        >
                      <tbody>
                            <tr>
                              <td
                                style="
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 30px 10px 10px;
                                  font-family: arial, helvetica, sans-serif;
                                "
                                align="left"
                              >
                                <!--[if mso]><table width="100%"><tr><td><![endif]-->
                                <h4
                                  style="
                                    margin: 0px;
                                    line-height: 140%;
                                    text-align: left;
                                    word-wrap: break-word;
                                    font-size: 12px;
                                    font-weight: 700;
                                  "
                                >
                                 <h3>Advisor Assigned Successfully</h3>
                              
                                </h4>
                                    <p>
          Dear<span> RM,</span> I am the advisor assigned to assist you in
          managing your job requirements. Feel free to reach out to me at
          [Advisor Email Address] or [ Phone Number] to schedule our meeting or
          if you have any immediate questions. I look forward to working with
          you and helping you achieve your career goals.
        </p>
                                <!--[if mso]></td></tr></table><![endif]-->
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <table
                          style="font-family: arial, helvetica, sans-serif"
                          role="presentation"
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          border="0"
                        >
                          <tbody>
                            <tr>
                              <td
                                style="
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 11px;
                                  font-family: arial, helvetica, sans-serif;
                                "
                                align="left"
                              >
                            
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <table
                          style="font-family: arial, helvetica, sans-serif"
                          role="presentation"
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          border="0"
                        >
                          <tbody>
                            <tr>
                              <td
                                style="
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 0px 10px;
                                  font-family: arial, helvetica, sans-serif;
                                "
                                align="left"
                              >
                        
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <!--[if (!mso)&(!IE)]><!-->
                      </div>
                      <!--<![endif]-->
                    </div>
                  </div>
                  <!--[if (mso)|(IE)]></td><![endif]-->
                  <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
                </div>
              </div>
            </div>

            <div
              class="u-row-container"
              style="padding: 0px 0px 0px; background-color: transparent"
            >
              <div
                class="u-row"
                style="
                  margin: 0 auto;
                  min-width: 320px;
                  max-width: 600px;
                  overflow-wrap: break-word;
                  word-wrap: break-word;
                  word-break: break-word;
                  background-color: #f4faff;
                  margin-bottom: 40px;
                "
              >
                <div
                  style="
                    border-collapse: collapse;
                    display: table;
                    width: 100%;
                    height: 100%;
                    background-color: transparent;
                  "
                >
                  <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 2px 0px 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: #ffffff;"><![endif]-->

                  <!--[if (mso)|(IE)]><td align="center" width="600" style="width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;" valign="top"><![endif]-->
                  <div
                    class="u-col u-col-100"
                    style="
                      max-width: 320px;
                      min-width: 600px;
                      display: table-cell;
                      vertical-align: top;
                    "
                  >
                    <div
                      style="
                        height: 100%;
                        width: 100% !important;
                        border-radius: 0px;
                        -webkit-border-radius: 0px;
                        -moz-border-radius: 0px;
                      "
                    >
                      <!--[if (!mso)&(!IE)]><!-->
                      <div
                        style="
                          box-sizing: border-box;
                          height: 100%;
                          padding: 0px;
                          border-top: 0px solid transparent;
                          border-left: 0px solid transparent;
                          border-right: 0px solid transparent;
                          border-bottom: 0px solid transparent;
                          border-radius: 0px;
                          -webkit-border-radius: 0px;
                          -moz-border-radius: 0px;
                          margin-left: 30px;
                        "
                      >
                        <!--<![endif]-->
                        <table
                          style="font-family: arial, helvetica, sans-serif"
                          role="presentation"
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          border="0"
                        >
                          <tbody>
                            <tr>
                              <td
                                style="
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 10px;
                                  font-family: arial, helvetica, sans-serif;
                                "
                                align="left"
                              >
                                <!--[if mso]><table width="100%"><tr><td><![endif]-->
                 
                                <!--[if mso]></td></tr></table><![endif]-->
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <table
                          id="u_content_button_5"
                          style="font-family: arial, helvetica, sans-serif"
                          role="presentation"
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          border="0"
                        >
                          <tbody>
                            <tr>
                              <td
                                style="
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 10px;
                                  font-family: arial, helvetica, sans-serif;
                                "
                                align="left"
                              >
                                <!--[if mso
                                  ]><style>
                                    .v-button {
                                      background: transparent !important;
                                    }
                                  </style><!
                                [endif]-->
                                <div align="left" style="text-align: center">
                                  <!--[if mso]><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="https://unlayer.com" style="height:34px; v-text-anchor:middle; width:140px;" arcsize="12%"  strokecolor="#000000" strokeweight="2px" fillcolor="#000000"><w:anchorlock/><center style="color:#ffffff;"><![endif]-->
                                  <a
                                    href="https://employer.jaldihire.com/"  target="_blank"
                                    target="_blank"
                                    class="v-button v-size-width"
                                    style="
                                      box-sizing: border-box;
                                      display: inline-block;
                                      text-decoration: none;
                                      -webkit-text-size-adjust: none;
                                      text-align: center;
                                      color: #ffffff;
                                      background-color: #0e69c1;
                                      border-radius: 4px;
                                      -webkit-border-radius: 4px;
                                      -moz-border-radius: 4px;
                                      width: auto;
                                      max-width: 100%;
                                      overflow-wrap: break-word;
                                      word-break: break-word;
                                      word-wrap: break-word;
                                      mso-border-alt: none;
                                      border-top-width: 2px;
                                      border-top-style: solid;
                                      border-top-color: #0e69c1;
                                      border-left-width: 2px;
                                      border-left-style: solid;
                                      border-left-color: #0e69c1;
                                      border-right-width: 2px;
                                      border-right-style: solid;
                                      border-right-color: #0e69c1;
                                      border-bottom-width: 2px;
                                      border-bottom-style: solid;
                                      border-bottom-color: #0e69c1;
                                      font-size: 18px;
                                      margin-bottom: 20px;
                                    "
                                  >
                                    <span
                                      style="
                                        display: block;
                                        padding: 10px 30px 8px;
                                        line-height: 90%;
                                      "
                                 
                                      >Open Dashboard
                                    </span>
                                  </a>
                                  <!--[if mso]></center></v:roundrect><![endif]-->
                                </div>
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <!--[if (!mso)&(!IE)]><!-->
                      </div>
                      <!--<![endif]-->
                    </div>
                  </div>
                  <!--[if (mso)|(IE)]></td><![endif]-->
                  <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
                </div>
              </div>
            </div>

            <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
          </td>
        </tr>
      </tbody>
    </table>
    <!--[if mso]></div><![endif]-->
    <!--[if IE]></div><![endif]-->
  </body>
</html>

  ';
}
