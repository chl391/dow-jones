var mongoose = require('mongoose');
var express = require('express');
var session = require("express-session");
var pass = require('pwd');
var db = require('../db.js');
var User = mongoose.model('User');
var UserList = mongoose.model('UserList');
var List = mongoose.model('List');
var Bookmark = mongoose.model('Bookmark');
var router = express.Router();

var sessionOptions = {
	secret: 'secret',
	resave: true,
	saveUninitialized: true,
	user: null,
	loginError: false,
	passwordError: false,
	registrationIncomplete: false,
	usernameTooShort: false,
	passwordTooShort: false,
	usernameExists: false,
	bookmarkListNameEmpty: false,
	bookmarkNameEmpty: false,
	bookmarkURLEmpty: false
};

router.use(session(sessionOptions));

/* GET home page. */
router.get('/', function(req, res) {
	res.render('index', { title: 'Bookmarks' });
});

router.get('/user', function(req, res) {
  	if (sessionOptions.user !== null){
  		res.redirect('/user/bookmarks');
 	}
 	else{
		res.render('user', {loginError: sessionOptions.loginError});
		sessionOptions.loginError = false;
	}
});

router.post('/user', function(req, res) {
	User.findOne({username: req.body.username}, function(err, user, count) {
	  if (user === null){
	  	sessionOptions.loginError = true;
	  	res.redirect("/user");
	  }
	  else{
		  pass.hash(req.body.password, user.salt, function(err, hash){
		  	if (user.hash == hash) {
		    	sessionOptions.user = req.body.username;
		    	res.redirect("/user/bookmarks");
		  	}
		  	else{
		  		sessionOptions.loginError = true;
		  		res.redirect("/user");
		  	}
		  })
		}
	});
});

router.get('/user/register', function(req, res) {
	if (sessionOptions.user !== null){
  		res.redirect('/user/bookmarks');
 	}
 	else{
	 	res.render('register', {passwordError: sessionOptions.passwordError, registrationIncomplete: sessionOptions.registrationIncomplete, usernameTooShort: sessionOptions.usernameTooShort, passwordTooShort: sessionOptions.passwordTooShort, usernameExists: sessionOptions.usernameExists});
	 	sessionOptions.passwordError = false;
	 	sessionOptions.registrationIncomplete = false;
	 	sessionOptions.usernameTooShort = false;
	 	sessionOptions.passwordTooShort = false;
	 	sessionOptions.usernameExists = false;
	}
});

router.post('/user/register', function(req, res) {
	if (req.body.username.length < 1 || req.body.password1.length < 1 || req.body.password2.length < 1){
		sessionOptions.registrationIncomplete = true;
		res.redirect('/user/register');
	}
	else if (req.body.password1 !== req.body.password2){
		sessionOptions.passwordError = true;
		res.redirect('/user/register');
	}
	else if (req.body.username.length < 8){
		sessionOptions.usernameTooShort = true;
		res.redirect('/user/register');
	}
	else if (req.body.password1.length < 8){
		sessionOptions.passwordTooShort = true;
		res.redirect('/user/register');
	}
	else{
		User.findOne({username: req.body.username}, function(err, user, count){
			if (user === null){
				pass.hash(req.body.password1, function(err, saveSalt, saveHash){
			  		new User({
			 	 		username: req.body.username,
				  		salt: saveSalt,
			 	 		hash: saveHash
					}).save(function(err, list, count){
				  		new UserList({
					 	 	user: req.body.username,
						  	lists: [List]
						}).save(function(err, list, count){
							sessionOptions.user = req.body.username;
						  	res.redirect('/user/bookmarks');
						});
				  	});
				})
			}
			else{
				sessionOptions.usernameExists = true;
				res.redirect('/user/register');
			}
		})
	}
});

router.get('/user/bookmarks', function(req, res) {
	if (sessionOptions.user === null){
		res.redirect('/user');
	}
	else{
		UserList.findOne({user: sessionOptions.user}, function(err, userList, count) {
			res.render('bookmarks', {user: sessionOptions.user, lists: userList.lists, bookmarkListNameEmpty: sessionOptions.bookmarkListNameEmpty, bookmarkNameEmpty: sessionOptions.bookmarkNameEmpty, bookmarkURLEmpty: sessionOptions.bookmarkURLEmpty});
			sessionOptions.bookmarkListNameEmpty = false;
			sessionOptions.bookmarkNameEmpty = false;
			sessionOptions.bookmarkURLEmpty = false;
		});
	}
});

router.post('/user/bookmarks', function(req, res) {
	console.log(req+res);
	if (sessionOptions.user === null){
		res.redirect('/user');
	}
	else if(req.body.listname.length < 1){
		sessionOptions.bookmarkListNameEmpty = true;
		res.redirect('/user/bookmarks');
	}
	else{
		UserList.findOne({user: sessionOptions.user}, function(err, userList, count){
			new List({
				user: sessionOptions.user,
				listName: req.body.listname,
				bookmarks: [Bookmark]
			}).save(function(err, list, count){
				userList.lists.push(list);
				//console.log("slug is " + list.slug);
				//console.log("new bookmark list created");
				userList.save(function(saveErr, saveList, saveCount){
					res.redirect('/user/bookmarks');
				});
			});
		});
	}
});

router.post('/user/bookmarks/add', function (req, res) {
	if (sessionOptions.user === null){
		res.redirect('/user');
	}
	else if (req.body.bookmarkname.length < 1){
		sessionOptions.bookmarkNameEmpty = true;
		res.redirect('/user/bookmarks');
	}
	else if (req.body.bookmarkurl.length < 1){
		sessionOptions.bookmarkURLEmpty = true;
		res.redirect('/user/bookmarks');
	}
	else{
		List.findOne({user:sessionOptions.user,slug:req.body.slug}, function(err, list, count){
			new Bookmark({
				bookmarkName: req.body.bookmarkname,
				url: req.body.bookmarkurl
			}).save(function(error, bookmark, count){
				list.bookmarks.push(bookmark);
				list.save(function(err, lst, cnt){
					UserList.findOne({user:sessionOptions.user}, function(err, userlist, count){
						var currentLists = userlist.lists;
						var x = 0;
						for (var i in currentLists){
							if (currentLists[i].slug === req.body.slug){
								var x = i;
							}
						}
						//console.log("x is " + x);
						userlist.lists.set(x,lst);
						userlist.save(function(saveErr, saveList, saveCount) {
							res.redirect('/user/bookmarks');
						});
					});
				});
			});
		});
	}
});

router.post('/user/bookmarks/remove', function (req, res) {
	if (sessionOptions.user === null){
		res.redirect('/user');
	}
	else{
		UserList.findOneAndUpdate({user:sessionOptions.user}, {$pull: {lists: {slug: req.body.slug}}}, function(err, data){
 			res.redirect('/user/bookmarks');
		});
	}
});

router.post('/user/bookmarks/removeitem', function (req, res) {
	if (sessionOptions.user === null){
		res.redirect('/user');
	}
	else{
		List.findOne({user:sessionOptions.user,slug:req.body.slug}, function(err, list, count){
			UserList.findOne({user:sessionOptions.user}, function(err, userlist, count){
				var currentLists = userlist.lists;
				var x = 0;
				for (var i in currentLists){
					if (currentLists[i].slug === req.body.slug){
						var x = i;
					}
				}
				if(req.body.checkbox instanceof Array){
					for (var k in req.body.checkbox){
						for (var j in currentLists[x].bookmarks){
							if (currentLists[x].bookmarks[j].url === req.body.checkbox[k]){
								currentLists[x].bookmarks.splice(j, 1);
							}
						}
					}
				}
				else{
					for (var j in currentLists[x].bookmarks){
						if (currentLists[x].bookmarks[j].url === req.body.checkbox){
							currentLists[x].bookmarks.splice(j, 1);
						}
					}
				}
				list.bookmarks = currentLists[x].bookmarks;
				console.log(currentLists[x].bookmarks);
				list.save();
				userlist.lists.set(x, currentLists[x]);
				userlist.save(function(saveErr, saveList, saveCount) {
					//List.findOneAndUpdate({user:sessionOptions.user,slug:req.body.slug},currentLists[x]);
					res.redirect('/user/bookmarks');
				});
			});
		});
	}
});

router.get('/user/logout', function(req, res) {
	sessionOptions.user = null;
	res.redirect('/user');
});

module.exports = router;
