const https = require("https");

async function handler() {
  console.log("domain name: " + process.env.BP_API_HOST);
  const hostName = process.env.BP_API_HOST;
  try {
    // get user mail list
    var countMailSend = 0;
    var date = new Date();
    var n = date.toString();
    console.log("Starttime<>:", n);
    var is_done = false;
    var runTimess = 0;

    function runMinutes() {
      var d = new Date();
      return (d.getTime() - date.getTime()) / (60 * 1000);
    }

    // function get option get list emails
    function options_get_list_emails(count = 5) {
      //count is user count
      return {
        hostname: hostName,
        port: 443,
        path: "/api/get_email_user_list?count=" + count,
        method: "GET",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
      };
    }
    function sleep(ms) {
      return new Promise((resolve) => setTimeout(resolve, ms));
    }
    var mailSent = 0;
    // START FUNCTION SEND MAIL
    async function send_mail(user_list) {
      console.log("--" + runTimess + "--START send mail: ");
      user_list =
        typeof user_list.length !== "undefined" && user_list.length
          ? user_list
          : [];
      console.log("--" + runTimess + "--<----- send_mail ------> ");
      console.log(
        "--" + runTimess + "--Param user_list: ",
        JSON.stringify(user_list)
      );
      console.log("--" + runTimess + "--Count user list: ", user_list.length);

      var user_list_block_10 = user_list.splice(0, 10);
      if (user_list_block_10.length < 1) {
        console.log("--" + runTimess + "--No need to send mail:");
        return false;
      }

      console.log("user_list_block_10", user_list_block_10);

      for (var i = 0; i < user_list_block_10.length; i++) {
        var user_i = user_list_block_10[i];
        console.log(
          "--" + runTimess + "--Start block user_id:--" + JSON.stringify(user_i)
        );
        if (user_i.self_mail != null && user_i.self_mail != "") {
          countMailSend += 1;
        }
        countMailSend += user_i.send_emails.length;
        console.log(
          "--" +
            runTimess +
            "--user_id:--" +
            user_i.user_id +
            "--countMailSend:",
          countMailSend
        );
        if (user_i.send_status == 3) {
          console.log(
            "--" +
              runTimess +
              "--user_id:--" +
              user_i.user_id +
              "--No AccessToken",
            user_i.user_id
          );
          continue;
        }
        if (
          (user_i.self_mail == "" || user_i.self_mail == null) &&
          user_i.send_emails.length == 0
        ) {
          console.log(
            "--" +
              runTimess +
              "--user_id:--" +
              user_i.user_id +
              "--No need to send mail for user_id:",
            user_i.user_id
          );
          continue;
        }
        user_i.send_emails = JSON.stringify(user_i.send_emails);
        var postData = user_i;
        var postDataStr = JSON.stringify(postData);
        console.log("postDataStr:", postDataStr);
        console.log(
          "--" +
            runTimess +
            "--user_id:--" +
            user_i.user_id +
            "--Email will send",
          postData
        );

        var options = {
          hostname: hostName,
          port: 443,
          path: "/api/send_email",
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
        };

        var req = https.request(options, (res) => {
          if (res.statusCode !== 200) {
            console.error(
              "Error call api send_email, status code: " +
                res.statusCode +
                ", dataPost: " +
                postDataStr
            );
          }
          res.setEncoding("utf8");
          res.on("data", (d) => {
            console.log(
              "--" +
                runTimess +
                "--================================================="
            );
            console.log(
              "--" + runTimess + "--Response send mail - options: ",
              JSON.stringify(options)
            );
            console.log(
              "--" + runTimess + "--Response send mail - data: ",
              JSON.stringify(d)
            );
          });
        });

        req.on("error", (e) => {
          console.error("problem with request: ", e);
          console.error("------ End: fail to notify to leader web app ------");
        });
        mailSent++;
        console.log("--" + runTimess + "--MailSent num:" + mailSent);
        console.log(
          "--" + runTimess + "--MailSent start:" + new Date().toString()
        );
        req.write(postDataStr);
        req.end();
        console.log(
          "--" + runTimess + "--MailSent end:" + new Date().toString()
        );
        await sleep(300);
      }

      if (user_list.length < 1) {
        return false;
      }
      return await send_mail(user_list);
    }
    // END FUNCTION SEND MAIL

    // Function get list emails and send
    async function getListEmail() {
      console.log("--" + runTimess + "--getListEmail");
      const req = https.request(options_get_list_emails(10), (res) => {
        if (res.statusCode !== 200) {
          console.error("getListEmail Error: " + res.statusCode);
        }

        var data = "";
        res.on("data", function (chunk) {
          data += chunk;
        });

        res.on("end", async () => {
          console.log("--" + runTimess + "--GET LIST: ", data.toString());
          var d = data;

          // send mail
          try {
            d = JSON.parse(d);
            if (d && d.data && d.data.user_list && d.data.user_list.length) {
              await send_mail(d.data.user_list);
            } else {
              // check for send status 「0:unprocessed」
              if (
                d &&
                d.data &&
                d.data.send_status &&
                d.data.send_status.hasOwnProperty("unprocessed") &&
                d.data.send_status.unprocessed != 0
              ) {
                // If the send status flag is "0: Unprocessed" in the DB
                // Because there are users who have not been sent, the alert target log is output.
                console.log("「Send E-mail Uncompleted」");
                console.log(
                  "Remaining users:" + d.data.send_status.unprocessed
                );
              } else {
                console.log("「Send E-mail Completed」");
              }
              is_done = true;
            }
          } catch (err) {
            console.error("--" + runTimess + "--error:", err);
            console.error("--" + runTimess + "--d:", d);
          }
        }); // end res.on(data)
      }); // end req

      req.on("error", (e) => {
        console.error(e);
      });
      req.end();
    }

    // call here
    console.log("--" + runTimess + "--is_done: ", is_done);
    console.log(
      "--" + runTimess + "--RunTimes from here:" + new Date().toString()
    );
    while (runMinutes() < 14 && is_done === false) {
      runTimess++;
      await getListEmail();
      console.log(
        "--" +
          runTimess +
          "--RunTimes: " +
          runTimess +
          " End:" +
          new Date().toString()
      );
      await sleep(8000);
    }
    console.log(
      "--" +
        runTimess +
        "--RunTimes: " +
        runTimess +
        " Finished:" +
        new Date().toString()
    );

    // Lambda return
    var response = {
      statusCode: 200,
      body: JSON.stringify("Number mail sent: " + countMailSend),
    };
    return response;
  } catch (error) {
    console.error("send_mail error: ", error);
    return {
        statusCode: 500,
        message: error
    };
  }
}

exports.handler = handler;
