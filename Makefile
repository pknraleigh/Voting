all: server

server:
	php -S 127.0.0.1:8090

talentJSON:
	php talent.php > data/talent.json

combineJS:
	cat js/json2.js js/jquery-1.10.2.min.js js/jstorage.js js/jquery.mustache.js js/voting.js > js/voting.min.js

.PHONY: server talentJSON combineJS