var mongoose = require('mongoose'),
	URLSlugs = require('mongoose-url-slugs');

// my schema goes here!
var User = new mongoose.Schema({
	username: String,
	salt: String,
	hash: String
});
var UserList = new mongoose.Schema({
	user: String,
	lists: [List]
});
var List = new mongoose.Schema({
	user: String,
	listName: String,
	bookmarks: [Bookmark]
});
var Bookmark = new mongoose.Schema({
	bookmarkName: String,
	url: String
});

List.plugin(URLSlugs('listName'));

mongoose.model('User', User);
mongoose.model('UserList', UserList);
mongoose.model('List', List);
mongoose.model('Bookmark', Bookmark);

mongoose.connect('mongodb://localhost/bookmarkdb');