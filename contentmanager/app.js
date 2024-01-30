var createError = require("http-errors");
var express = require("express");
var path = require("path");
var cookieParser = require("cookie-parser");
var logger = require("morgan");
const session = require("express-session");
var indexRouter = require("./routes/index");
var usersRouter = require("./routes/users");
var dashboardRouter = require("./routes/dashboard");
var historyRouter = require("./routes/history");
var liveRouter = require("./routes/live");
var recordRouter = require("./routes/record");
var uploadRouter = require("./routes/upload");
var arrangementRouter = require("./routes/arrangement");
var durationRouter = require("./routes/duration");
var videoLogRouter = require("./routes/logs");
const logoutRouter = require("./routes/logout");
const playVideoRouter = require("./routes/playVideo");
const crypto = require("crypto");
const { setIO } = require('./src/socket.io/socket-setup')
const { loadVideo } = require('./src/services/fileTransferService')

const generateSecretKey = () => {
  return crypto.randomBytes(32).toString("hex");
};
const secretKey = generateSecretKey();
const cors = require("cors");

// const { v4: uuidv4 } = require("uuid");
var app = express();

// view engine setup
app.set("views", path.join(__dirname, "views"));
app.set("view engine", "ejs");

app.use(
  session({
    secret: secretKey,
    resave: false,
    saveUninitialized: false,
    cookie: {
      maxAge: 60 * 60 * 1000, // 30 minutes
    },
  })
);

app.use(logger("dev"));
app.use(express.json());
app.use(express.urlencoded({ extended: false }));
app.use(cookieParser());
app.use(express.static(path.join(__dirname, "public")));
app.use(cors());
app.use("/", indexRouter);
app.use("/login", indexRouter);
app.use("/users", usersRouter);
app.use("/dashboard", dashboardRouter);
app.use("/history", historyRouter);
app.use("/live", liveRouter);
app.use("/record", recordRouter);
app.use("/upload", uploadRouter);
app.use("/arrangement", arrangementRouter);
app.use("/duration", durationRouter);
app.use("/logout", logoutRouter);
app.use("/playVideo", playVideoRouter);
app.use("/logs", videoLogRouter);


// catch 404 and forward to error handler
app.use(function (req, res, next) {
  next(createError(404));
});

// error handler
app.use(function (err, req, res, next) {
  // set locals, only providing error in development
  res.locals.message = err.message;
  res.locals.error = req.app.get("env") === "development" ? err : {};

  if (err) {
    console.log(err);
  }
  // render the error page
  res.status(err.status || 500);
  res.render("error");
});

let io = setIO();
io.on('connection', socket => {
  console.log('websocket established');
  socket.emit('testing', 'Hello world');
  loadVideo();
})

app.listen(3000, ()=>{
  console.log('Node Server Running::Port: 3000')
})


module.exports = app;
