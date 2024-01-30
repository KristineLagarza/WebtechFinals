const { Server } = require('socket.io');

//A field
//To pass io object globally
let _io;

function setIO()
{
 _io = new Server(3001, {
  cors: {
    origin: "http://localhost",
    credentials: true
  }
 })

 return _io;
}

function getIO(){
   return _io;
}

module.exports = {setIO, getIO};