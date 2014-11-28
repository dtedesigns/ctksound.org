var mysql = require('mysql');
var yaml  = require('js-yaml');
var fs = require('fs');

var connection = mysql.createConnection({
  host     : 'localhost',
  user     : 'root',
  password : '',
  database : 'ctksound'
});

connection.connect();

var id = 33;

// sermons
connection.query('SELECT * FROM sermons', function(err, rows, fields) {
  if (err) { throw err; }

  fs.open('sermons.yml', 'w', function(err, fd) {
    console.log(rows);
    var written = fs.writeSync(fd, yaml.safeDump(rows));
  });
});

// aces
connection.query('SELECT * FROM aces', function(err, rows, fields) {
  if (err) { throw err; }

  fs.open('aces.yml', 'w', function(err, fd) {
    console.log(rows);
    var written = fs.writeSync(fd, yaml.safeDump(rows));
  });
});

// dedications
connection.query('SELECT * FROM dedications', function(err, rows, fields) {
  if (err) { throw err; }

  fs.open('dedications.yml', 'w', function(err, fd) {
    console.log(rows);
    var written = fs.writeSync(fd, yaml.safeDump(rows));
  });
});

// portraits
connection.query('SELECT * FROM portraits', function(err, rows, fields) {
  if (err) { throw err; }

  fs.open('portraits.yml', 'w', function(err, fd) {
    console.log(rows);
    var written = fs.writeSync(fd, yaml.safeDump(rows));
  });
});

connection.end();
