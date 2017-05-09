<?php
  session_start();
  if(isset($_SESSION['id'])) unset($_SESSION['id']);
  session_destroy();

  require_once("system/data.php");

  $error = false;
  $error_msg = "";
  $success = false;
  $success_msg = "";

  if(isset($_POST['login-submit'])){
    if(!empty($_POST['username']) && !empty($_POST['password'])){

      $username = filter_inputs($_POST['username']);
      $password = filter_inputs($_POST['password']);

      $result = login($username, $password);

  		$row_count = mysqli_num_rows($result);
      if( $row_count == 1){
        session_start();
        $user = mysqli_fetch_assoc($result);
        $_SESSION['userid'] = $user['user_id'];
        header("Location:home.php");
      }
      else{
        $error = true;
        $error_msg .= "Benutzerdaten konnten nicht gefunden werden.</br>";
      }
    }
    else{
      $error = true;
      $error_msg .= "Bitte füllen Sie beide Felder aus.</br>";
    }
  }


  if(isset($_POST['register-submit'])){
  // Kontrolle mit isset, ob email und password ausgefüllt wurde
  if(!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['confirm-password'])){

    // Werte aus POST-Array auf SQL-Injections prüfen und in Variablen schreiben
    $username = $_POST['username'];
    $email = $_POST['email'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    if($password == $confirm_password){
      // register liefert bei erfolgreichem Eintrag in die DB den Wert TRUE zurück, andernfalls FALSE
      $result = register($username, $email, $firstname, $lastname, $password);
      if($result){
        $success = true;
        $success_msg = "Ihre Registrierung war erfolgreich.</br>
        Sie können sich nun einloggen.</br>";
      }else{
        $error = true;
        $error_msg .= "Es gibt ein Problem mit der Datenbankverbindung.</br>";
      }
    }else{
      $error = true;
      $error_msg .= "Die Passwörter stimmen nicht überein.</br>";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Photoloca</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/3-col-portfolio.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

  <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Photoloca</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="index.php">Finde Locations</a>
                    </li>
                    <li>
                        <a href="login.php">Login / Registration</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
      <!-- /.container -->
    </nav>

  <!-- Page Content -->
    <div class="container">

        <!-- Page Header -->
        <div class="row">
            <div class="col-lg-6">

              <!--  Neues Passwort festlegen form -->
              <h3>Neues Passwort festlegen</h3>
              <form id="login-form" action="<?PHP echo $_SERVER['PHP_SELF'] ?>" method="post" role="form">

              <fieldset>
              <div class="form-group">
            <label>Neues Password</label>
                  <div class="form-group">
                      <div class="form-group"></div>
                      <input class="form-control" data-val="true" data-val-length="password must be between 6 and 100 characters long." data-val-length-max="100" data-val-length-min="6" data-val-required="password is required" id="Password" name="Password" placeholder="Minimum 6 Zeichen" rows="12" type="password" />
                  </div>
                </div>

              <div class="form-group">
                  <label>Strength</label>
                  <div id="pw-val-container">
                      <div id="pw-val-progress"></div>
                      <div id="pw-val-verdict"></div>
                      <div id="pw-val-errors"></div>
                  </div>

              </div>
              </fieldset>

                <div class="form-group">
                  <h5>Passwort bestätigen</h5>
                  <input type="password" name="password2" id="password2" tabindex="2" class="form-control" placeholder="Passwort bestätigen">
                </div>
                <div class="form-group">
                <div class="row">
                  <div class="col-sm-6 col-sm-offset-3">
                    <input type="submit" name="password-submit" id="password-submit" tabindex="3" class="form-control btn btn-register" value="Neues Passwort speichern">
                  </div>
                </div>
              </div>
              </form>
              <!-- /Neues Passwort festlegen  -->

              <!-- Fehlermeldung: Die PW stimmen nicht überein -->

              <!-- /Fehlermeldung: Die PW stimmen nicht überein -->

            </div>
          </div>
          <!-- /.row -->
            <?php
              // Gibt es einen Erfolg zu vermelden?
              if($success == true){
            ?>
                <div class="alert alert-success" role="alert"><?php echo $success_msg; ?></div>
            <?php
              }   // schliessen von if($success == true)
              // Gibt es einen Fehler?
              if($error == true){
            ?>
                <div class="alert alert-danger" role="alert"><?php echo $error_msg; ?></div>
            <?php
              }   // schliessen von if($success == true)
            ?>

        <hr>


        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; Photoloca 2017</p>
                </div>
            </div>
            <!-- /.row -->
        </footer>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- functions.js -->
    <script src="js/functions.js"></script>

  <script>
    $(document).ready(function () {
            "use strict";
            var options = {};
           options.ui = {showErrors:false, showVerdictsInsideProgressBar:true, container:'#pw-val-container',viewports:{progress:'#pw-val-progress',verdict:'#pw-val-verdict',errors:'#pw-val-errors'}};

        $('#Password').pwstrength(options);

        });

        ! function(n) {
        var r = {},
            n, u, t, i, f;
        try {
            !n && module && module.exports && (n = require("jquery"), u = require("jsdom").jsdom, n = n(u().parentWindow))
        } catch (e) {}! function(n, t) {
            "use strict";
            var i = {};
            t.forbiddenSequences = ["0123456789", "abcdefghijklmnopqrstuvwxyz", "qwertyuiop", "asdfghjkl", "zxcvbnm", "!@#$%^&*()_+"];
            i.wordNotEmail = function(n, t, i) {
                return t.match('/^([\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+\.)*[\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+@((((([a-z0-9]{1}[a-z0-9\-]{0,62}[a-z0-9]{1})|[a-z])\.)+[a-z]{2,6})|(\d{1,3}\.){3}\d{1,3}(\:\d{1,5})?)$/i') ? i : 0
            };
            i.wordLength = function(n, t, i) {
                var r = t.length,
                    u = Math.pow(r, n.rules.raisePower);
                return r < n.common.minChar && (u += i), u
            };
            i.wordSimilarToUsername = function(t, i, r) {
                var u = n(t.common.usernameField).val();
                return u && i.toLowerCase().match(u.toLowerCase()) ? r : 0
            };
            i.wordTwoCharacterClasses = function(n, t, i) {
                return t.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/) || t.match(/([a-zA-Z])/) && t.match(/([0-9])/) || t.match(/(.[!,@,#,$,%,\^,&,*,?,_,~])/) && t.match(/[a-zA-Z0-9_]/) ? i : 0
            };
            i.wordRepetitions = function(n, t, i) {
                return t.match(/(.)\1\1/) ? i : 0
            };
            i.wordSequences = function(i, r, u) {
                var f, e = !1;
                return r.length > 2 && (n.each(t.forbiddenSequences, function(t, i) {
                    var u = [i, i.split("").reverse().join("")];
                    n.each(u, function(n, t) {
                        for (f = 0; f < r.length - 2; f += 1) t.indexOf(r.toLowerCase().substring(f, f + 3)) > -1 && (e = !0)
                    })
                }), e) ? u : 0
            };
            i.wordLowercase = function(n, t, i) {
                return t.match(/[a-z]/) && i
            };
            i.wordUppercase = function(n, t, i) {
                return t.match(/[A-Z]/) && i
            };
            i.wordOneNumber = function(n, t, i) {
                return t.match(/\d+/) && i
            };
            i.wordThreeNumbers = function(n, t, i) {
                return t.match(/(.*[0-9].*[0-9].*[0-9])/) && i
            };
            i.wordOneSpecialChar = function(n, t, i) {
                return t.match(/.[!,@,#,$,%,\^,&,*,?,_,~]/) && i
            };
            i.wordTwoSpecialChar = function(n, t, i) {
                return t.match(/(.*[!,@,#,$,%,\^,&,*,?,_,~].*[!,@,#,$,%,\^,&,*,?,_,~])/) && i
            };
            i.wordUpperLowerCombo = function(n, t, i) {
                return t.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/) && i
            };
            i.wordLetterNumberCombo = function(n, t, i) {
                return t.match(/([a-zA-Z])/) && t.match(/([0-9])/) && i
            };
            i.wordLetterNumberCharCombo = function(n, t, i) {
                return t.match(/([a-zA-Z0-9].*[!,@,#,$,%,\^,&,*,?,_,~])|([!,@,#,$,%,\^,&,*,?,_,~].*[a-zA-Z0-9])/) && i
            };
            t.validation = i;
            t.executeRules = function(i, r) {
                var u = 0;
                return n.each(i.rules.activated, function(f, e) {
                    if (e) {
                        var o, h, c = i.rules.scores[f],
                            s = t.validation[f];
                        n.isFunction(s) || (s = i.rules.extra[f]);
                        n.isFunction(s) && (o = s(i, r, c), o && (u += o), (0 > o || !n.isNumeric(o) && !o) && (h = i.ui.spanError(i, f), h.length > 0 && i.instances.errors.push(h)))
                    }
                }), u
            }
        }(n, r);
        try {
            module && module.exports && (module.exports = r)
        } catch (e) {}
        t = {};
        t.common = {};
        t.common.minChar = 6;
        t.common.usernameField = "#username";
        t.common.userInputs = [];
        t.common.onLoad = void 0;
        t.common.onKeyUp = void 0;
        t.common.zxcvbn = !1;
        t.common.debug = !1;
        t.rules = {};
        t.rules.extra = {};
        t.rules.scores = {
            wordNotEmail: -100,
            wordLength: -50,
            wordSimilarToUsername: -100,
            wordSequences: -50,
            wordTwoCharacterClasses: 2,
            wordRepetitions: -25,
            wordLowercase: 1,
            wordUppercase: 3,
            wordOneNumber: 3,
            wordThreeNumbers: 5,
            wordOneSpecialChar: 3,
            wordTwoSpecialChar: 5,
            wordUpperLowerCombo: 2,
            wordLetterNumberCombo: 2,
            wordLetterNumberCharCombo: 2
        };
        t.rules.activated = {
            wordNotEmail: !0,
            wordLength: !0,
            wordSimilarToUsername: !0,
            wordSequences: !0,
            wordTwoCharacterClasses: !1,
            wordRepetitions: !1,
            wordLowercase: !0,
            wordUppercase: !0,
            wordOneNumber: !0,
            wordThreeNumbers: !0,
            wordOneSpecialChar: !0,
            wordTwoSpecialChar: !0,
            wordUpperLowerCombo: !0,
            wordLetterNumberCombo: !0,
            wordLetterNumberCharCombo: !0
        };
        t.rules.raisePower = 1.4;
        t.ui = {};
        t.ui.bootstrap2 = !1;
        t.ui.showProgressBar = !0;
        t.ui.showPopover = !1;
        t.ui.showStatus = !1;
        t.ui.spanError = function(n, t) {
            "use strict";
            var i = n.ui.errorMessages[t];
            return i ? '<span style="color: #d52929">' + i + "<\/span>" : ""
        };
        t.ui.errorMessages = {
            wordLength: "Dein Passwort ist zu kurz",
            wordNotEmail: "Bitte verwende keine Email Adresse als Passwort",
            wordSimilarToUsername: "Your password cannot contain your username",
            wordTwoCharacterClasses: "Use different character classes",
            wordRepetitions: "Too many repetitions",
            wordSequences: "Your password contains sequences"
        };
        t.ui.verdicts = ["Weak", "Normal", "Medium", "Strong", "Very Strong"];
        t.ui.showVerdicts = !0;
        t.ui.showVerdictsInsideProgressBar = !1;
        t.ui.showErrors = !1;
        t.ui.container = void 0;
        t.ui.viewports = {
            progress: void 0,
            verdict: void 0,
            errors: void 0
        };
        t.ui.scores = [14, 26, 38, 50];
        i = {};
        ! function(n, t) {
            "use strict";
            var r = ["danger", "warning", "success"],
                i = ["error", "warning", "success"];
            t.getContainer = function(t, i) {
                var r;
                return r = n(t.ui.container), r && 1 === r.length || (r = i.parent()), r
            };
            t.findElement = function(n, t, i) {
                return t ? n.find(t).find(i) : n.find(i)
            };
            t.getUIElements = function(n, i) {
                var u, r;
                return n.instances.viewports ? n.instances.viewports : (u = t.getContainer(n, i), r = {}, r.$progressbar = t.findElement(u, n.ui.viewports.progress, "div.progress"), n.ui.showVerdictsInsideProgressBar && (r.$verdict = r.$progressbar.find("span.password-verdict")), n.ui.showPopover || (n.ui.showVerdictsInsideProgressBar || (r.$verdict = t.findElement(u, n.ui.viewports.verdict, "span.password-verdict")), r.$errors = t.findElement(u, n.ui.viewports.errors, "ul.error-list")), n.instances.viewports = r, r)
            };
            t.initProgressBar = function(i, r) {
                var f = t.getContainer(i, r),
                    u = "<div class='progress'><div class='";
                i.ui.bootstrap2 || (u += "progress-");
                u += "bar'>";
                i.ui.showVerdictsInsideProgressBar && (u += "<span class='password-verdict'><\/span>");
                u += "<\/div><\/div>";
                i.ui.viewports.progress ? f.find(i.ui.viewports.progress).append(u) : n(u).insertAfter(r)
            };
            t.initHelper = function(i, r, u, f) {
                var e = t.getContainer(i, r);
                f ? e.find(f).append(u) : n(u).insertAfter(r)
            };
            t.initVerdict = function(n, i) {
                t.initHelper(n, i, "<span class='password-verdict'><\/span>", n.ui.viewports.verdict)
            };
            t.initErrorList = function(n, i) {
                t.initHelper(n, i, "<ul class='error-list'><\/ul>", n.ui.viewports.errors)
            };
            t.initPopover = function(n, t) {
                t.popover("destroy");
                t.popover({
                    html: !0,
                    placement: "bottom",
                    trigger: "manual",
                    content: " "
                })
            };
            t.initUI = function(n, i) {
                n.ui.showPopover ? t.initPopover(n, i) : (n.ui.showErrors && t.initErrorList(n, i), n.ui.showVerdicts && !n.ui.showVerdictsInsideProgressBar && t.initVerdict(n, i));
                n.ui.showProgressBar && t.initProgressBar(n, i)
            };
            t.possibleProgressBarClasses = ["danger", "warning", "success"];
            t.updateProgressBar = function(i, u, f, e) {
                var h = t.getUIElements(i, u).$progressbar,
                    o = h.find(".progress-bar"),
                    s = "progress-";
                i.ui.bootstrap2 && (o = h.find(".bar"), s = "");
                n.each(t.possibleProgressBarClasses, function(n, t) {
                    o.removeClass(s + "bar-" + t)
                });
                o.addClass(s + "bar-" + r[f]);
                o.css("width", e + "%")
            };
            t.updateVerdict = function(n, i, r) {
                var u = t.getUIElements(n, i).$verdict;
                u.text(r)
            };
            t.updateErrors = function(i, r) {
                var f = t.getUIElements(i, r).$errors,
                    u = "";
                n.each(i.instances.errors, function(n, t) {
                    u += "<li>" + t + "<\/li>"
                });
                f.html(u)
            };
            t.updatePopover = function(t, i, r) {
                var f = i.data("bs.popover"),
                    u = "",
                    e = !0;
                return t.ui.showVerdicts && !t.ui.showVerdictsInsideProgressBar && r.length > 0 && (u = "<h5><span class='password-verdict'>" + r + "<\/span><\/h5>", e = !1), t.ui.showErrors && (u += "<div>Errors:<ul class='error-list' style='margin-bottom: 0;'>", n.each(t.instances.errors, function(n, t) {
                    u += "<li>" + t + "<\/li>";
                    e = !1
                }), u += "<\/ul><\/div>"), e ? void i.popover("hide") : (t.ui.bootstrap2 && (f = i.data("popover")), void(f.$arrow && f.$arrow.parents("body").length > 0 ? i.find("+ .popover .popover-content").html(u) : (f.options.content = u, i.popover("show"))))
            };
            t.updateFieldStatus = function(t, r, u) {
                var e = t.ui.bootstrap2 ? ".control-group" : ".form-group",
                    f = r.parents(e).first();
                n.each(i, function(n, i) {
                    t.ui.bootstrap2 || (i = "has-" + i);
                    f.removeClass(i)
                });
                u = i[u];
                t.ui.bootstrap2 || (u = "has-" + u);
                f.addClass(u)
            };
            t.percentage = function(n, t) {
                var i = Math.floor(100 * n / t);
                return i = 0 > i ? 0 : i, i = i > 100 ? 100 : i
            };
            t.getVerdictAndCssClass = function(n, t) {
                var i, r, u;
                return 0 >= t ? (i = 0, u = -1, r = n.ui.verdicts[0]) : t < n.ui.scores[0] ? (i = 0, u = 0, r = n.ui.verdicts[0]) : t < n.ui.scores[1] ? (i = 0, u = 1, r = n.ui.verdicts[1]) : t < n.ui.scores[2] ? (i = 1, u = 2, r = n.ui.verdicts[2]) : t < n.ui.scores[3] ? (i = 1, u = 3, r = n.ui.verdicts[3]) : (i = 2, u = 4, r = n.ui.verdicts[4]), [r, i, u]
            };
            t.updateUI = function(n, i, r) {
                var u, e, f;
                u = t.getVerdictAndCssClass(n, r);
                f = u[0];
                u = u[1];
                n.ui.showProgressBar && (e = t.percentage(r, n.ui.scores[3]), t.updateProgressBar(n, i, u, e), n.ui.showVerdictsInsideProgressBar && t.updateVerdict(n, i, f));
                n.ui.showStatus && t.updateFieldStatus(n, i, u);
                n.ui.showPopover ? t.updatePopover(n, i, f) : (n.ui.showVerdicts && !n.ui.showVerdictsInsideProgressBar && t.updateVerdict(n, i, f), n.ui.showErrors && t.updateErrors(n, i))
            }
        }(n, i);
        f = {};
        ! function(n, u) {
            "use strict";
            var f, e;
            f = function(t) {
                var o, f, h, e, s = n(t.target),
                    u = s.data("pwstrength-bootstrap"),
                    c = s.val();
                void 0 !== u && (u.instances.errors = [], u.common.zxcvbn ? (o = [], n.each(u.common.userInputs, function(t, i) {
                    o.push(n(i).val())
                }), o.push(n(u.common.usernameField).val()), e = zxcvbn(c, o).entropy) : e = r.executeRules(u, c), i.updateUI(u, s, e), f = i.getVerdictAndCssClass(u, e), h = f[2], f = f[0], u.common.debug && console.log(e + " - " + f), n.isFunction(u.common.onKeyUp) && u.common.onKeyUp(t, {
                    score: e,
                    verdictText: f,
                    verdictLevel: h
                }))
            };
            u.init = function(r) {
                return this.each(function(u, e) {
                    var h = n.extend(!0, {}, t),
                        s = n.extend(!0, h, r),
                        o = n(e);
                    s.instances = {};
                    o.data("pwstrength-bootstrap", s);
                    o.on("keyup", f);
                    o.on("change", f);
                    o.on("onpaste", f);
                    i.initUI(s, o);
                    n.trim(o.val()) && o.trigger("keyup");
                    n.isFunction(s.common.onLoad) && s.common.onLoad()
                }), this
            };
            u.destroy = function() {
                this.each(function(t, r) {
                    var u = n(r),
                        e = u.data("pwstrength-bootstrap"),
                        f = i.getUIElements(e, u);
                    f.$progressbar.remove();
                    f.$verdict.remove();
                    f.$errors.remove();
                    u.removeData("pwstrength-bootstrap")
                })
            };
            u.forceUpdate = function() {
                this.each(function(n, t) {
                    var i = {
                        target: t
                    };
                    f(i)
                })
            };
            u.addRule = function(t, i, r, u) {
                this.each(function(f, e) {
                    var o = n(e).data("pwstrength-bootstrap");
                    o.rules.activated[t] = u;
                    o.rules.scores[t] = r;
                    o.rules.extra[t] = i
                })
            };
            e = function(t, i, r) {
                this.each(function(u, f) {
                    n(f).data("pwstrength-bootstrap").rules[i][t] = r
                })
            };
            u.changeScore = function(n, t) {
                e.call(this, n, "scores", t)
            };
            u.ruleActive = function(n, t) {
                e.call(this, n, "activated", t)
            };
            n.fn.pwstrength = function(t) {
                var i;
                return u[t] ? i = u[t].apply(this, Array.prototype.slice.call(arguments, 1)) : "object" != typeof t && t ? n.error("Method " + t + " does not exist on jQuery.pwstrength-bootstrap") : i = u.init.apply(this, arguments), i
            }
        }(n, f)
    }(jQuery)

  </script>

</body>

</html>
